# MonLogiciel

A desktop application built with Tauri + React frontend and Symfony backend.

## Project Structure

```
MonLogiciel/
 ├─ app/                     → Tauri + React frontend
 ├─ backend/                 → Symfony backend
 │   ├─ bin/
 │   ├─ config/
 │   ├─ public/
 │   ├─ src/
 │   ├─ vendor/
 │   └─ .env.local
 ├─ runtime/
 │   ├─ php/                 → PHP portable (php.exe etc.)
 │   └─ tools/               → Optional: caddy/nginx portable
 ├─ storage/
 │   ├─ logs/
 │   └─ cache/
 └─ config/
     └─ app.config.json
```

## Prerequisites

- Node.js (v18+)
- npm or yarn
- Rust (for Tauri)
- Composer (for Symfony)

## Setup

### 1. PHP Runtime
Download PHP 8.2+ from https://windows.php.net/download/ and extract it to `runtime/php/`

### 2. Backend (Symfony)
```bash
cd backend
composer install
php bin/console doctrine:migrations:migrate
php -S localhost:8000 -t public
```

### 3. Frontend (Tauri + React)
```bash
cd app
npm install
npm run tauri:dev
```

## Development

### Run Symfony Backend
```bash
cd backend
php -S localhost:8000 -t public
```

### Run Tauri App
```bash
cd app
npm run tauri:dev
```

### Build for Production
```bash
cd app
npm run tauri:build
```

## Configuration

Edit `config/app.config.json` to customize application settings.

## License

ISC
