# Running this application

### Prerequisites
- Docker (Docker Desktop for Windows)

### Steps
1. `git clone https://github.com/MachiBytes/ISKOpe-API.git`
2. Make sure to open Docker Desktop on Windows to run the Docker Daemon
3. Fix your .env
4. Run on your terminal the following
    - `docker-compose build`
    - `docker-compose up -d`
    - `docker exec -it php /bin/sh` - Run this in powershell if you encounter an error
    - `composer install`
    - `chmod -R 777 storage`
    - `php artisan migrate`
    - `php artisan db:seed`
    - `php artisan serve`