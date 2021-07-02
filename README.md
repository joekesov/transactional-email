# Laravel Transactional email

## Instalation
To build the containers
```bash
docker-compose up -d --build
```

To enter the container
```bash
docker-compose exec server bash
```

To install the project
```bash
composer install
```

To migrate the Database 
```bash
php artisan migrate
```

To run the queue
```bash
php artisan queue:work
```

To start the queue
```bash
php artisan queue:work
```

To create a new project
```bash
composer create-project laravel/laravel .
```