
# Firesell todo app (Laravel+Vue )


## Installation

- `git clone https://github.com/amanullah-1/firesell-todo.git`
- `cd firesell-todo/`
- `composer install`
- `cp .env.example .env`
- Update `.env` and set your database credentials
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan passport:install`
- `npm install`
- `npm run dev`
- `php artisan serve`


## APi End points for local run

- `http://127.0.0.1:8000/api/register`
- `http://127.0.0.1:8000/api/login`


# `(Accept all the methods GOT/POST/PUT/DELETE)`
- `http://127.0.0.1:8000/api/todos/?token=eyJ0eXAiOiJKV1Qi....`
- `http://127.0.0.1:8000/api/user/?token=eyJ0eXAiOiJKV1Qi....`


## APi End points for user front and backend
- `http://127.0.0.1:8000/todos`
- `http://127.0.0.1:8000/users`
