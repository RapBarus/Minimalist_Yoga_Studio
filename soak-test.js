import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    stages: [
        { duration: '10m', target: 50 },  // Ramp-up: perlahan ke 50 VU
        { duration: '1h', target: 50 },  // Soak: Tahan 50 VU selama 1 JAM (bisa lu ganti jadi 2h/3h)
        { duration: '5m', target: 0 },   // Ramp-down
    ],
};

export default function () {
    const BASE_URL = 'http://localhost:8000';

    // 1. Home Page
    const resHome = http.get(`${BASE_URL}/`);
    check(resHome, { 'Home status 200': (r) => r.status === 200 });
    sleep(1);

    // 2. Register Page (Gunakan POST kalau mau simulasi daftar)
    // Untuk soak testing, sekedar get halaman register pun sudah cukup untuk ngetes kestabilan session
    const resReg = http.get(`${BASE_URL}/register`);
    check(resReg, { 'Register status 200': (r) => r.status === 200 });
    sleep(1);
}