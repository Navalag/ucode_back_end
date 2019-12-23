## Project description

The idea of the project is to create basic API for Q&A service, something like Stack Overflow.

This API allow users to register, view posts, like and comment any of them. 

#### Used technologies:
- Laravel
 
## Installation

Clone the repository
```
git clone https://github.com/Navalag/ucode_back_end.git
```

Install dependencies
```
composer install
```

Copy .env.example to .env. 
Create database and update database and email credentials.
```
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Run migration and seeder
```
php artisan migrate --seed
```

Run server
```
php artisan serve
```
