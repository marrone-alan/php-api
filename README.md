# Instructions

## Docker

> docker-compose up -d

> docker exec app composer install

> cp .env.example .env

Find the block that specifies DB_CONNECTION and update it to reflect the specifics in docker-compose file. You will modify the following fields:

```
DB_CONNECTION=mysql
DB_HOST=db *
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laraveluser *
DB_PASSWORD=your_laravel_db_password *
```

> docker-compose exec app php artisan key:generate

> docker-compose exec app php artisan config:cache

## Mysql

> docker-compose exec db bash

> mysql -u root -p

You will be prompted for the password for the MySQL root account in docker-compose file.

> GRANT ALL ON laravel.\* TO 'laraveluser'@'%' IDENTIFIED BY 'your_laravel_db_password';
> FLUSH PRIVILEGES;

> EXIT;

## Migrate

> docker-compose exec app php artisan migrate
