# SETUP

## Install dependencies
```bash
  git clone https://github.com/viktortoman/blog-be.git
  cd blog-be
  composer install
  php artisan key:generate
  php artisan migrate
  php artisan db:seed
```

## Run the server
```bash
  php artisan serve
```

## Login on /api/login
```json
{
  "email": "test@example.com",
  "password": "password"
}
```
  
## Use the token as Bearer token in the header to access protected routes, e.g. /api/user
