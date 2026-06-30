import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    stages: [
        { duration: '30s', target: 20 },  // Ramp-up: 20 Virtual Users
        { duration: '1m', target: 50 },   // Load: 50 Virtual Users (Simulasi banyak customer login bareng)
        { duration: '30s', target: 0 },   // Ramp-down
    ],
    thresholds: {
        http_req_failed: ['rate<0.01'],    
        http_req_duration: ['p(95)<1000'], // Ekspektasi untuk customer harus lebih cepat dari admin (< 1 detik)
    },
};

export default function () {
    const BASE_URL = 'http://localhost:8000';

    // =======================================================
    // LANGKAH 1: BUKA HALAMAN LOGIN & AMBIL CSRF TOKEN
    // =======================================================
    
    let resLoginGet = http.get(`${BASE_URL}/login`);
    
    const csrfMatch = resLoginGet.body.match(/name="_token" value="(.*?)"/);
    const csrfToken = csrfMatch ? csrfMatch[1] : '';

    // =======================================================
    // LANGKAH 2: EKSEKUSI LOGIN CUSTOMER
    // =======================================================
    
    let resLoginPost = http.post(`${BASE_URL}/login`, {
        _token: csrfToken,
        email: 'nurhax', // GANTI dengan email customer yang ada di database hasil seed
        password: 'test123',    // GANTI dengan password aslinya
    });

    check(resLoginPost, {
        'Login Customer berhasil': (r) => r.status === 302 || r.status === 200,
    });

    // Jeda sejenak setelah login
    sleep(Math.random() * 2 + 1);

    // =======================================================
    // LANGKAH 3: SIMULASI USER JOURNEY (CUSTOMER)
    // =======================================================

    // Customer otomatis diarahkan atau membuka halaman /home
    let resCustomerHome = http.get(`${BASE_URL}/home`);
    check(resCustomerHome, {
        'Customer Home status 200': (r) => r.status === 200,
    });
    
    sleep(Math.random() * 2 + 1); 

    // Customer mengecek jadwal kelas untuk di-booking
    // (Bisa sesuaikan route-nya misal /schedules atau /bookings)
    let resCustomerSchedules = http.get(`${BASE_URL}/member`);
    check(resCustomerSchedules, {
        'Customer Schedules status 200': (r) => r.status === 200,
    });

    sleep(Math.random() * 2 + 1);
}