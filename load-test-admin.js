import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    stages: [
        { duration: '30s', target: 20 },  // Ramp-up ke 20 VU
        { duration: '1m', target: 50 },   // Tahan di 50 VU
        { duration: '30s', target: 0 },   // Ramp-down
    ],
    thresholds: {
        http_req_failed: ['rate<0.01'],
        http_req_duration: ['p(95)<1500'], // Dilonggarkan dikit jadi 1.5s karena query admin lebih berat
    },
};

export default function () {
    const BASE_URL = 'http://localhost:8000';

    // =======================================================
    // LANGKAH 1: PERSIAPAN LOGIN (AMBIL CSRF TOKEN)
    // =======================================================
    
    // k6 pura-pura buka halaman login dulu buat ngambil cookie & token
    let resLoginGet = http.get(`${BASE_URL}/login`);
    
    // Ekstrak CSRF Token dari input hidden HTML bawaan Laravel
    const csrfMatch = resLoginGet.body.match(/name="_token" value="(.*?)"/);
    const csrfToken = csrfMatch ? csrfMatch[1] : '';

    // =======================================================
    // LANGKAH 2: EKSEKUSI LOGIN
    // =======================================================
    
    let resLoginPost = http.post(`${BASE_URL}/login`, {
        _token: csrfToken,
        username: 'yrahayu_999698@admin.com', // GANTI dengan username admin lu di database
        password: 'password123', // GANTI dengan password aslinya
    });

    // Biasanya kalau login Laravel sukses, statusnya 302 (Redirect) ke halaman dashboard
    check(resLoginPost, {
        'Login berhasil': (r) => r.status === 302 || r.status === 200,
    });

    // Wajib dikasih sleep agar CPU Docker lu nggak meledak karena hashing bcrypt barengan
    sleep(Math.random() * 2 + 1);

    // =======================================================
    // LANGKAH 3: SIMULASI USER JOURNEY DI DASHBOARD ADMIN
    // =======================================================

    // User ngecek Dashboard Utama Admin (yang query-nya berat)
    // PENTING: Sesuaikan '/admin/dashboard' dengan endpoint asli lu!
    let resAdminDashboard = http.get(`${BASE_URL}/admin/dashboard`);
    check(resAdminDashboard, {
        'Admin Dashboard status 200': (r) => r.status === 200,
    });
    
    sleep(Math.random() * 2 + 1); 

    // User ngecek laporan/jadwal spesifik
    let resAdminSchedules = http.get(`${BASE_URL}/admin/coaches`);
    check(resAdminSchedules, {
        'Admin Schedules status 200': (r) => r.status === 200,
    });

    sleep(Math.random() * 2 + 1);
}