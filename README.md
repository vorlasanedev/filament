<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Tutorial 
1. installation
```
https://www.youtube.com/watch?v=ibDPgHNyM5s

```
2. Crearte CRUD
```
https://www.youtube.com/watch?v=H6oj3zWr2gE
```
3. Installation & Setup Guide - FilamentPHP v4 (Ep 1)
```
https://www.youtube.com/watch?v=Fy0XCg1XBjQ
```
## Create Resource
```
php artisan make:filament-resource Employee
```
## Create view on record table
```
php artisan make:filament-page ViewEmployee --resource=EmployeeResource --type=ViewRecord
```

--> continue filament validation field 26:06
## Exportor
https://www.youtube.com/watch?v=P3ektl0bBTs
```
php artisan make:queue-batches-table
php artisan make:notifications-table
php artisan vendor:publish --tag=filament-actions-migrations
php artisan migrate

php artisan make:filament-exporter Employee --generate
php artisan make:filament-importer Employee --generate
```
## filament invoice
```
https://www.youtube.com/watch?v=IQ6DQ3F9CMc
https://www.youtube.com/watch?v=bb6Iz_OOs3c
```

## Localization & Translations

### 1. Language Switcher
- The language switcher is located in the top navigation bar.
- Supported languages: **English** (en) and **Lao** (lo).

### 2. Translation Files
- **English**: `lang/en/`
- **Lao**: `lang/lo/`

### 3. How to Translate
To add a new translation:
1.  Open the appropriate file in `lang/en/` (e.g., `fields.php`, `navigation.php`) and add your key-value pair.
    ```php
    // lang/en/fields.php
    return [
       'new_field' => 'New Field Name',
    ];
    ```
2.  Open the corresponding file in `lang/lo/` and add the translation.
    ```php
    // lang/lo/fields.php
    return [
       'new_field' => 'ຊື່ຟິວໃໝ່',
    ];
    ```
3.  Use the key in your code (Filament Resource, Form, or Table):
    ```php
    ->label(__('fields.new_field'))
    ```

