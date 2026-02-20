# Deploy Instructions

## 1. Environment
- Copy `.env.production.example` to `.env`
- Fill in all secrets (APP_KEY, DB, MAIL)

## 2. Generate app key
```
php artisan key:generate
```

## 3. Migrate database
```
php artisan migrate --force
```

## 4. Build assets
```
npm install
npm run build
```

## 5. Cache for production
```
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 6. Queue worker (required for email)
```
php artisan queue:work --sleep=3 --tries=3
```
