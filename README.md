# Xlife admin panel backend setup

## Requirements

- PHP 8.1 or higher
- Composer for dependency management
- SQLite as database engine

## Install dependencies

From the project root, run:

```bash
composer install
```

## Configure `.env` file

Run this command to create the `.env` file:

```bash
cp .env.example .env
php artisan key:generate
````

In production, the application must not expose internal error details or stack traces.

So if you run it in production please make sure the following environment variables are set:

- `APP_ENV=production`
- `APP_DEBUG=false`

## Configure SQLite database connection

To configure SQLite, create the database file:

```bash
touch ./database/database.sqlite
```

Then set the absolute path in your `.env` file.

```text
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/project/database/database.sqlite
```

### Populate database

We must run the migrations to create the database tables and relations
and then run the seeds to populate it.

You can do this by running this command:

```bash
php artisan migrate --seed
```

### Run the application

```bash
composer run dev
```

This will run the application in development mode. It will be available at:

`http://localhost:8000`

The API documentation will be available at:

`http://localhost/api/documentation`.

### Run the application tests

```bash
php artisan test
```
