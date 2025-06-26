<?php

return [
    'paths' => ['api/*'], // Tentukan path route API yang diizinkan
    'allowed_methods' => ['*'], // Izinkan semua metode HTTP (GET, POST, PUT, DELETE)
    'allowed_origins' => ['*'], // Izinkan semua asal/URL atau bisa disesuaikan dengan domain
    'allowed_headers' => ['*'], // Izinkan semua header
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // Menonaktifkan cookies, session, dll
];
