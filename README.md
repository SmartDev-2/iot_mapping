# IoT Mapping - Laravel 12 API

Backend API untuk Arduino Uno + GPS + ESP Wemos + MySQL.

## Instalasi
composer install
copy .env.example .env
php artisan key:generate

Buat database MySQL bernama `iot_mapping`, lalu sesuaikan `.env` dan jalankan:
php artisan migrate --seed
php artisan serve

## Endpoint
GET /api/devices
POST /api/location
GET /api/locations
GET /api/device/{device}/latest
GET /api/device/{device}/history

## Contoh POST
{"device_id":"GPS001","latitude":-8.1723000,"longitude":113.7001000,"timestamp":"2026-09-24 13:20:15"}
