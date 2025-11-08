# Translation Management API

![Laravel Logo](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

This is a **Laravel-based API** for managing translations across multiple locales. The API allows for **creating, updating, fetching**, and **exporting translations** in various languages and includes **Passport Authentication** for secure API access.

[![Build Status](https://github.com/laravel/framework/workflows/tests/badge.svg)](https://github.com/laravel/framework/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/framework)

## Requirements

- PHP >= 8.2
- Composer
- MySQL (or MariaDB)
- Laravel 12
- Passport (for API Authentication)

---

## Installation

### 1. Clone the Repository

Clone the repository to your local machine and navigate into the project directory:

git clone https://github.com/Sagar158/translation-management-digital-tolk.git
### 2. Go to Project
cd translation-management-digital-tolk.git

### 3. Install Composer
composer install

### 4. Set up the Environment File
cp .env.example .env

### 5. Configure Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=localhost
DB_USERNAME=root
DB_PASSWORD=

### 6. Generate Application Key
php artisan key:generate

### 7. Install Passport
composer require laravel/passport

### 8. Run Passport Install Command
php artisan passport:install


### 9. Add Passport's Service Provider
'guards' => [
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],

### 10. Run the migration with seeder
php artisan migrate:fresh --seed


