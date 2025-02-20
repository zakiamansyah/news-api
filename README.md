# News API

This is a Laravel-based API for managing news articles and comments. Below are the instructions for setting up and using the project, along with the available endpoints and their parameters.

## Setup

### Clone the repository:
```sh
git clone git@github.com:zakiamansyah/news-api.git
cd news-api
```

### Install dependencies:
```sh
composer install
```

### Copy the example environment file and configure the environment variables:
```sh
cp .env.example .env
```

### Generate an application key:
```sh
php artisan key:generate
```

### Run the database migrations:
```sh
php artisan migrate
```

### Start the development server:
```sh
php artisan serve
```

### Start Redis server:
```sh
redis-server
```

### Start the queue worker:
```sh
php artisan queue:work
```

---

## Available Endpoints

### Authentication

#### Register
- **URL:** `/api/register`
- **Method:** `POST`
- **Parameters:**
  - `name` (string, required)
  - `email` (string, required)
  - `password` (string, required)
  - `password_confirmation` (string, required)

#### Login
- **URL:** `/api/login`
- **Method:** `POST`
- **Parameters:**
  - `email` (string, required)
  - `password` (string, required)

#### Logout
- **URL:** `/api/logout`
- **Method:** `POST`
- **Headers:**
  - `Authorization: Bearer {token}`

---

### News

#### Get all news
- **URL:** `/api/news`
- **Method:** `GET`
- **Headers:**
  - `Authorization: Bearer {token}`

#### Create news
- **URL:** `/api/news`
- **Method:** `POST`
- **Headers:**
  - `Authorization: Bearer {token}`
- **Parameters:**
  - `title` (string, required)
  - `content` (string, required)

#### Get news by ID
- **URL:** `/api/news/{id}`
- **Method:** `GET`
- **Headers:**
  - `Authorization: Bearer {token}`

#### Update news
- **URL:** `/api/news/{id}`
- **Method:** `PUT`
- **Headers:**
  - `Authorization: Bearer {token}`
- **Parameters:**
  - `title` (string, optional)
  - `content` (string, optional)

#### Delete news
- **URL:** `/api/news/{id}`
- **Method:** `DELETE`
- **Headers:**
  - `Authorization: Bearer {token}`

---

### Comments

#### Create comment
- **URL:** `/api/news/{newsId}/comments`
- **Method:** `POST`
- **Headers:**
  - `Authorization: Bearer {token}`
- **Parameters:**
  - `content` (string, required)

---

## Environment Variables

Ensure the following environment variables are set in your `.env` file:

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:NdA78R/PE6ZtbLwCLDVPj9ScXuJMHDuonK1Vdyyoxig=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=news_api
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis

CACHE_STORE=database
CACHE_PREFIX=
CACHE_DRIVER=redis

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

---
