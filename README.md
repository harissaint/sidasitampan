## Requirements
- Local Server : XAMPP or Laragon (recomeended)
- Composer
- PHP 8.1
- Node 18.8.0
- MySQL 8

## Installation
- Run ```composer install``` on your cmd or terminal
- Run ```npm install```
- Copy .env.example file to .env on the root folder. You can type copy .env.example .env if using command prompt Windows or cp .env.example .env if using terminal, Ubuntu
- Open your .env file and change the database name (DB_DATABASE) to whatever you have, username (DB_USERNAME) and password (DB_PASSWORD) field correspond to your configuration.
- Run ```php artisan key:generate```
- Run ```php artisan migrate```
- Run ```php artisan serve```
- Open new terminal and run ```npm run dev```
- Open in your browser http://127.0.0.1:8000/

misalkan
