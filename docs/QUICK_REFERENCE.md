# Authentication System - Quick Reference

## 🚀 Quick Start Commands

### Backend Setup
```bash
cd backend
composer install
composer require nelmio/cors-bundle
php bin/console doctrine:database:create
psql -U user -d dbname -f ../database/shema.sql
php bin/console app:create-user admin password123 "Admin" --role=ROLE_ADMIN
symfony server:start
```

### Frontend Setup
```bash
cd app
npm install
cp .env.example .env
npm run dev
```

## 🔑 Create Users

```bash
# Admin user
php bin/console app:create-user admin password123 "Admin User" --role=ROLE_ADMIN

# Regular user
php bin/console app:create-user john password123 "John Doe" --role=ROLE_USER
```

## 🌐 Default URLs

- **Backend API**: http://localhost:8000
- **Frontend (Vite)**: http://localhost:5173
- **Tauri App**: Desktop window

## 📡 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| /api/auth/login | POST | Login |
| /api/auth/logout | POST | Logout |
| /api/auth/check | GET | Check auth |

## 🔐 Test Credentials

After running the create user command:
- **Login**: admin
- **Password**: password123
- **Role**: ROLE_ADMIN

## 🎯 Architecture Pattern

### Backend (Symfony)
```
Controller/User/
  ├── CheckAuthController.php     # __invoke() pattern
  └── LogoutController.php        # __invoke() pattern

Service/User/
  ├── UserService.php
  └── UserServiceInterface.php

Dto/User/
  ├── LoginRequestDto.php
  ├── UserResponseDto.php
  └── UserInternalDto.php
```

### Frontend (React)
```
features/auth/
  ├── api.ts                      # API calls
  ├── hooks.ts                    # useAuth hook
  ├── types.ts                    # TypeScript types
  └── LoginPage.tsx               # UI component

services/
  ├── apiClient.ts                # HTTP client
  └── authStorage.ts              # Session storage
```

## 🐛 Common Issues

### CORS Error
```bash
composer require nelmio/cors-bundle
```

### Session Not Persisting
- Check `credentials: 'include'` in API calls
- Verify CORS allows credentials

### 404 on Login
```bash
php bin/console cache:clear
php bin/console debug:router
```

### TypeScript Errors
```bash
npm install react-router-dom
npm install
```

## 📚 Documentation Files

- **AUTH_README.md** - Full system documentation
- **AUTH_SETUP.md** - Setup guide & troubleshooting
- **IMPLEMENTATION_SUMMARY.md** - What's implemented

## 🔄 Development Workflow

1. **Start backend**: `symfony server:start`
2. **Start frontend**: `npm run dev`
3. **Open browser**: http://localhost:5173
4. **Login**: admin / password123
5. **Check network tab** if issues occur

## 📝 Environment Variables

### Backend (.env)
```env
DATABASE_URL="postgresql://user:pass@127.0.0.1:5432/db"
```

### Frontend (.env)
```env
VITE_API_URL=http://localhost:8000
```

## ✅ Verification Checklist

- [ ] Backend server running
- [ ] Database created and schema loaded
- [ ] Test user created
- [ ] Frontend dependencies installed
- [ ] .env file configured
- [ ] Can access login page
- [ ] Can login successfully
- [ ] Redirects to dashboard
- [ ] Can logout
- [ ] Session persists on refresh

## 🎨 Tech Stack

**Backend**: Symfony 7.4 + PostgreSQL  
**Frontend**: React 19 + TypeScript + Vite + Tauri  
**Auth**: Session-based (stateful)
