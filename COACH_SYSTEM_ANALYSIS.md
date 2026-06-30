# Coach System - Complete Analysis

## Overview
This is a Laravel fitness studio management system with dedicated Coach and Admin functionality for managing fitness classes, schedules, attendance, and memberships.

---

## 1. COACH ROUTES & ENDPOINTS

### File: [routes/web.php](routes/web.php)

**Coach Routes (Protected by `auth.session` + `coach.auth` middleware):**

| Route | Endpoint | Method | Controller | Purpose |
|-------|----------|--------|-----------|---------|
| coach.dashboard | `/coach/dashboard` | GET | `CoachDashboardController@index` | Main coach dashboard with schedule list & filters |
| coach.schedule.detail | `/coach/schedule/{scheduleId}` | GET | `CoachDashboardController@scheduleDetail` | View schedule details & manage attendance |
| coach.schedule.update | `/coach/schedule/{scheduleId}/update` | POST | `CoachDashboardController@updateSchedule` | Update attendance & mark class as completed |
| coach.profile | `/coach/profile` | GET | Closure | Profile page (Coming soon) |

**Example Usage:**
```php
// Dashboard with filter
route('coach.dashboard')  // All schedules
route('coach.dashboard', ['filter' => 'week'])  // This week
route('coach.dashboard', ['filter' => 'month'])  // This month

// Schedule detail
route('coach.schedule.detail', $schedule->schedule_id)
```

---

## 2. COACH DASHBOARD CONTROLLER

### File: [app/Http/Controllers/Coach/CoachDashboardController.php](app/Http/Controllers/Coach/CoachDashboardController.php)

### Method: `index(Request $request)`

**Purpose:** Display coach's scheduled classes with optional filtering

**Logic Flow:**
```
1. Get coach_id from session (user_id → lookup coaches table)
2. Get filter parameter from request: 'all', 'week', or 'month' (default: 'all')
3. Query vw_coach_schedule view with filters:
   - coach_id matches
   - schedule_date >= today
   - ORDER BY schedule_date ASC
4. Apply date filters:
   - 'week': schedule_date <= endOfWeek()
   - 'month': schedule_date <= endOfMonth()
5. Return view with schedules & filter
```

**Data Returned:**
```php
$schedules = [
    {
        schedule_id,
        class_id,
        coach_id,
        schedule_date,
        start_time,
        end_time,
        capacity,
        available_slots,
        status,
        class_name,
        rate_per_class  // Coach's per-class rate
    }
]
$filter = 'all'|'week'|'month'
```

### Method: `scheduleDetail($scheduleId)`

**Purpose:** Show detailed attendance interface for a specific class

**Logic:**
```
1. Verify coach owns this schedule (security check)
2. Get schedule details with class info
3. Get all participants (bookings for this schedule)
4. Separate participants into two groups:
   - hadir (status = 'attended')
   - tidakHadir (all other statuses)
5. Return view with attendance form
```

**Data Returned:**
```php
$schedule = {
    schedule_id,
    schedule_date,
    start_time,
    end_time,
    capacity,
    available_slots,
    status,
    class_name,
    rate_per_class
}

$participants = [
    { booking_id, status, name },
    ...
]
```

### Method: `updateSchedule(Request $request, $scheduleId)`

**Purpose:** Save attendance and mark class as completed

**Logic:**
```
1. Verify coach owns this schedule
2. Process attendance array:
   - 'hadir' → status = 'attended'
   - 'tidak_hadir' → status = 'confirmed'
3. Handle file upload (bukti_hadir - proof of attendance)
   - Store in storage/bukti_hadir folder
4. Mark schedule status = 'completed'
5. Redirect with success message
```

**Attendance Update Code:**
```php
if ($request->has('attendance')) {
    foreach ($request->attendance as $bookingId => $status) {
        $bookingStatus = $status === 'hadir' ? 'attended' : 'confirmed';
        DB::table('bookings')
            ->where('booking_id', $bookingId)
            ->update(['status' => $bookingStatus]);
    }
}
```

---

## 3. SCHEDULE-RELATED MODELS & DATABASE TABLES

### Table: `schedules`
```sql
schedule_id (PK)
class_id (FK → classes)
coach_id (FK → coaches)
schedule_date (DATE)
start_time (TIME)
end_time (TIME)
capacity (INT)
available_slots (INT)
status (ENUM: 'upcoming', 'completed')
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### Table: `classes`
```sql
class_id (PK)
class_name (VARCHAR) - e.g., "Hatha Yoga", "Vinyasa Flow"
description (TEXT)
level (ENUM: 'beginner', 'intermediate', 'advanced')
duration_minutes (INT)
created_at (TIMESTAMP)
```

### Table: `coaches`
```sql
coach_id (PK)
user_id (FK → users)
specialization (VARCHAR)
bio (TEXT)
rate_per_class (DECIMAL) - Price per class
years_experience (INT)
created_at (TIMESTAMP)
```

### Table: `bookings` (Attendance tracking)
```sql
booking_id (PK)
user_id (FK → users)
schedule_id (FK → schedules)
booking_date (DATE)
status (ENUM: 'confirmed', 'attended', 'cancelled')
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### View: `vw_coach_schedule`
**Purpose:** Join schedules with class and coach information for coach dashboard
**Used in:** `CoachDashboardController@index()` line 24

---

## 4. FILTER IMPLEMENTATION: "Minggu Jadwal" (Week) & "Bulan Ini" (Month)

### Location: [app/Http/Controllers/Coach/CoachDashboardController.php](app/Http/Controllers/Coach/CoachDashboardController.php#L17-L31)

**Filter Logic:**
```php
$filter = $request->get('filter', 'all');  // Default: 'all'

$query = DB::table('vw_coach_schedule')
    ->where('coach_id', $coachId)
    ->where('schedule_date', '>=', now()->toDateString())
    ->orderBy('schedule_date', 'asc');

// Apply filter
if ($filter === 'week') {
    // Show schedules up to end of this week (Sunday)
    $query->where('schedule_date', '<=', now()->endOfWeek()->toDateString());
} elseif ($filter === 'month') {
    // Show schedules up to end of this month
    $query->where('schedule_date', '<=', now()->endOfMonth()->toDateString());
}

$schedules = $query->get();
```

**UI Implementation:** [resources/views/coach/coach_dashboard.blade.php](resources/views/coach/coach_dashboard.blade.php#L137-L157)

```blade
{{-- Filter buttons --}}
<a href="{{ route('coach.dashboard', ['filter' => 'all']) }}">Semua</a>
<a href="{{ route('coach.dashboard', ['filter' => 'week']) }}">Minggu Ini</a>
<a href="{{ route('coach.dashboard', ['filter' => 'month']) }}">Bulan Ini</a>
```

**Date Range Behavior:**
- **Semua (All):** Today onwards, no end date limit
- **Minggu Ini (This Week):** From today until Sunday (endOfWeek)
- **Bulan Ini (This Month):** From today until last day of month (endOfMonth)

---

## 5. ATTENDANCE FUNCTIONALITY

### Schedule Detail Page
**File:** [resources/views/coach/coach_schedule_detail.blade.php](resources/views/coach/coach_schedule_detail.blade.php)

**UI Components:**

#### 1. **Tidak Hadir (Absent) Table**
- Shows participants NOT marked as attended
- Can click checkmark icon to mark as attended
- Status badge: Red/danger color

#### 2. **Hadir (Present) Table**
- Shows participants marked as attended
- Can click X icon to unmark (revert to confirmed)
- Status badge: Green/success color

#### 3. **Interactive Buttons**
- **✓ (Check):** Mark participant as attended
- **✗ (X):** Mark participant as not attended
- Both are toggle buttons that update hidden input field

#### 4. **Proof Upload Section**
- File input for `bukti_hadir` (proof of attendance)
- Drag-and-drop area (accepts images)
- Files stored in `storage/bukti_hadir`

#### 5. **Update Button**
- Submits form with attendance data
- Saves all changes to database
- Updates schedule status to 'completed'

### Attendance Status Mapping

| Value | Status | Meaning |
|-------|--------|---------|
| `hadir` | `attended` | Present/Attended the class |
| `tidak_hadir` | `confirmed` | Booked but not marked as attended |

### Form Submission Example
```html
<input type="hidden" name="attendance[booking_id]" value="hadir">
<input type="file" name="bukti_hadir">
```

### Backend Processing
```php
foreach ($request->attendance as $bookingId => $status) {
    $bookingStatus = $status === 'hadir' ? 'attended' : 'confirmed';
    DB::table('bookings')
        ->where('booking_id', $bookingId)
        ->update(['status' => $bookingStatus]);
}

// Mark class as completed
DB::table('schedules')
    ->where('schedule_id', $scheduleId)
    ->update(['status' => 'completed']);
```

---

## 6. ADMIN SCHEDULE MANAGEMENT

### File: [app/Http/Controllers/Admin/ScheduleController.php](app/Http/Controllers/Admin/ScheduleController.php)

### Key Methods:

#### `index()`
- List all upcoming schedules
- Join with classes, coaches, users
- Returns: schedules, classes, coaches, scheduleDates (for calendar)

#### `store(Request $request)`
- Create new schedule
- Validate: class_id, coach_id, date, times, capacity
- Max 240 minutes duration check
- Initialize `available_slots = capacity`

#### `viewJadwal($scheduleId)` 
- View participants for a schedule
- Join bookings with transactions
- Show payment status & amount

#### `addPeserta(Request $request, $scheduleId)`
- Add walk-in customer to schedule
- Create user if doesn't exist
- Validate phone number format
- Create booking + transaction
- Decrement available_slots

#### `confirmBooking($scheduleId, $bookingId)`
- Confirm pending bookings

---

## 7. KEY DATA RELATIONSHIPS

```
Coach (coaches table)
  ↓ (user_id)
  User (users table - role: 'coach')
  
Coach (coach_id)
  ↓ (has many)
  Schedule (schedules table)
    ↓ (class_id)
    Class (classes table)
    
Schedule (schedule_id)
  ↓ (has many)
  Booking (bookings table)
    ↓ (user_id)
    User (users table - role: 'customer')
    
Booking (booking_id)
  ↓ (has one)
  Transaction (transactions table)
```

---

## 8. COACH DASHBOARD STATS

**Displayed in Coach Dashboard:**
```php
$totalSchedules = DB::table('schedules')
    ->where('coach_id', $coachId)
    ->where('status', 'upcoming')
    ->count();

$completedClasses = DB::table('schedules')
    ->where('coach_id', $coachId)
    ->where('status', 'completed')
    ->count();

$totalBookings = DB::table('bookings')
    ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
    ->where('schedules.coach_id', $coachId)
    ->count();

$totalEarnings = $completedClasses * $coach->rate_per_class;
```

---

## 9. KEY FILES SUMMARY

| File | Purpose |
|------|---------|
| [routes/web.php](routes/web.php) | Coach routes definition |
| [app/Http/Controllers/Coach/CoachDashboardController.php](app/Http/Controllers/Coach/CoachDashboardController.php) | Coach dashboard & schedule management |
| [app/Http/Controllers/Admin/ScheduleController.php](app/Http/Controllers/Admin/ScheduleController.php) | Admin schedule management |
| [resources/views/coach/coach_dashboard.blade.php](resources/views/coach/coach_dashboard.blade.php) | Coach dashboard UI with filters |
| [resources/views/coach/coach_schedule_detail.blade.php](resources/views/coach/coach_schedule_detail.blade.php) | Attendance management UI |
| [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php) | Database schema & sample data |

---

## 10. ATTENDANCE WORKFLOW

```
1. Coach logs in → coach.dashboard
   ↓
2. Chooses filter (Semua/Minggu/Bulan)
   ↓
3. Sees filtered schedule list
   ↓
4. Clicks "Cek Jadwal" button
   ↓
5. Opens coach.schedule.detail view
   ↓
6. System separates participants into:
   - Tidak Hadir (not attended)
   - Hadir (already attended)
   ↓
7. Coach toggles attendance status with buttons
   ↓
8. Coach uploads proof image (optional)
   ↓
9. Clicks "Update Kelas"
   ↓
10. POST to coach.schedule.update
   ↓
11. Database updates:
    - bookings.status for each participant
    - schedules.status = 'completed'
    - File stored in storage/bukti_hadir
   ↓
12. Redirect to coach.dashboard with success message
```
