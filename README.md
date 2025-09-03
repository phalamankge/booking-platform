Booking Platform

A simple booking platform built with Laravel 12, PHP 8.2+, and MySQL.  
This project was developed as part of a technical test.

Features
- Create bookings linked to a user and client
- Prevent overlapping bookings per user
- Filter bookings by week, user, and client
- Frontend with Blade + Vanilla JS
- Database seeders with demo users & clients
- Feature tests for API

Installation

1. Clone the repo
    git clone https://github.com/phalamankge/booking-platform.git
    cd booking-platform
2. composer install
3. Set up the environment
   Copy the example environment file and update database credentials:
   cp .env.example .env
   
    Edit .env:
   
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=booking_db
    DB_USERNAME=root
    DB_PASSWORD=
4. Run migrations and seed the database
   php artisan migrate:fresh --seed
   This creates demo data:
   Users: Katlego Phala, Bridget Mametsa, Mpho Mankge 
   Clients: SA Corp, Lim Ltd, Technerd
5. Run server
   php artisan serve
6. Run Feature test
   php artisan test




