# SETUP INSTRUCTIONS

## 🎯 Project Structure Created

Your MonLogiciel project has been successfully set up with the following structure:

```
MonLogiciel/
 ├─ app/                     ✅ Tauri + React (TypeScript)
 ├─ backend/                 ✅ Symfony (fully installed)
 ├─ runtime/
 │   ├─ php/                 ⚠️  Needs PHP installation
 │   └─ tools/               (Optional tools)
 ├─ storage/
 │   ├─ logs/
 │   └─ cache/
 └─ config/
     └─ app.config.json
```

## 📋 What's Installed

### ✅ Backend (Symfony)
- Symfony 7.x with webapp pack
- Doctrine ORM
- Twig templating
- Asset mapper
- Maker bundle (for code generation)
- All standard dependencies

### ✅ Frontend (Tauri + React)
- Tauri 2.x
- React 19.x with TypeScript
- Vite as build tool
- Pre-configured structure

### ⚠️ What You Need to Install

**PHP Runtime (Required)**
1. Download PHP 8.2+ from: https://windows.php.net/download/
2. Choose "Non Thread Safe" or "Thread Safe" version
3. Extract ZIP to `runtime/php/` folder
4. Verify: `runtime\php\php.exe -v` should work

**Rust (Required for Tauri)**
- Download from: https://rustup.rs/
- Install and restart terminal

## 🚀 Quick Start

### Option 1: Use Startup Scripts
Double-click:
- `start.bat` (Command Prompt)
- `start.ps1` (PowerShell)

### Option 2: Manual Start

**Terminal 1 - Backend:**
```bash
cd backend
php -S localhost:8000 -t public
```

**Terminal 2 - Frontend:**
```bash
cd app
npm run tauri:dev
```

## 📝 Important Files

- `config/app.config.json` - Application configuration
- `backend/.env.local` - Symfony environment config
- `app/package.json` - Frontend dependencies
- `README.md` - Full documentation

## 🛠️ Development Commands

### Backend (Symfony)
```bash
cd backend

# Create a controller
php bin/console make:controller

# Create an entity
php bin/console make:entity

# Database migration
php bin/console doctrine:migrations:migrate

# Clear cache
php bin/console cache:clear
```

### Frontend (Tauri + React)
```bash
cd app

# Development mode
npm run tauri:dev

# Build for production
npm run tauri:build

# Run web preview
npm run dev
```

## 📦 Project URLs

- **Backend API**: http://localhost:8000
- **Frontend**: Tauri desktop window
- **Symfony Profiler**: http://localhost:8000/_profiler

## 🎨 Next Steps

1. **Install PHP in runtime/php/** (see above)
2. **Install Rust** from rustup.rs
3. **Run `start.bat` or `start.ps1`** to launch everything
4. **Start developing!**

### Create Your First Feature

Backend (API endpoint):
```bash
cd backend
php bin/console make:controller ApiController
```

Frontend (React component):
Edit `app/src/App.tsx` to add your UI

## 🔧 Troubleshooting

**"composer not found"**
- Install Composer: https://getcomposer.org/download/

**"npm not found"**
- Install Node.js: https://nodejs.org/

**"cargo not found"**
- Install Rust: https://rustup.rs/

**Backend errors**
- Check `backend/var/log/` for Symfony logs
- Ensure PHP extensions are enabled in `runtime/php/php.ini`

**Frontend errors**
- Check browser console (F12)
- Run `npm install` in app/ folder

## 📚 Documentation

- Symfony: https://symfony.com/doc
- Tauri: https://tauri.app/
- React: https://react.dev/

---

✨ Your project is ready! Happy coding!
