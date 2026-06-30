import http from 'k6/http';
import { check, sleep } from 'k6';

// 1. Konfigurasi Beban (Load Profile)
export const options = {
    stages: [
        { duration: '30s', target: 20 },  // Ramp-up: Perlahan naik ke 20 Virtual Users (VU) dalam 30 detik
        { duration: '1m', target: 50 },   // Load: Gaspol naik ke 50 VU dan tahan selama 1 menit (Jam sibuk)
        { duration: '30s', target: 0 },   // Ramp-down: Perlahan turun ke 0 VU
    ],
    thresholds: {
        http_req_failed: ['rate<0.01'],    // Toleransi error (500/504) maksimal cuma 1%
        http_req_duration: ['p(95)<1000'], // 95% response time harus di bawah 1 detik (1000ms)
    },
};

export default function () {
    const BASE_URL = 'http://localhost:8000';

    // =======================================================
    // SIMULASI USER JOURNEY
    // =======================================================

    // Langkah 1: User buka halaman utama (Homepage)
    let resHome = http.get(`${BASE_URL}/`);
    check(resHome, {
        'Homepage status 200': (r) => r.status === 200,
    });
    
    // User baca-baca landing page selama 1-2 detik
    sleep(Math.random() * 2 + 1); 

    // Langkah 2: User ngecek daftar kelas / jadwal
    // PENTING: Ganti endpoint '/schedules' atau '/classes' sesuai dengan route asli web lu!
    let resSchedules = http.get(`${BASE_URL}/register`);
    check(resSchedules, {
        'Schedules status 200': (r) => r.status === 200,
    });

    // User mikir-mikir milih jadwal selama 1-2 detik
    sleep(Math.random() * 2 + 1);
}