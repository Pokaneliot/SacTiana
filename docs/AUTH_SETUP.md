# Authentication System Setup Guide

## Quick Start

### 1. Backend Setup

```bash
cd backend

# Install dependencies
composer install

# Install CORS bundle (required for frontend communication)
composer require nelmio/cors-bundle

# Configure your database in .env
# DATABASE_URL="postgresql://user:password@127.0.0.1:5432/dbname"

# Create database (if not exists)
php bin/console doctrine:database:create

# Run the SQL schema to create tables
psql -U user -d dbname -f ../database/shema.sql

# Or create a migration from the User entity
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# Create a test user
php bin/console app:create-user admin password123 "Admin User" --role=ROLE_ADMIN
php bin/console app:create-user john password123 "John Doe" --role=ROLE_USER

# Start Symfony server
symfony server:start
# Or use PHP built-in server
php -S localhost:8000 -t public/
```

### 2. Frontend Setup

```bash
cd app

# Install dependencies
npm install

# Create .env file from example
cp .env.example .env

# Edit .env to set your API URL (default: http://localhost:8000)
# VITE_API_URL=http://localhost:8000

# Run development server
npm run dev

# Or run as Tauri app
npm run tauri:dev
```

### 3. Test the Authentication

1. Open your browser and navigate to the frontend URL (usually `http://localhost:5173` or Tauri window)
2. You should be redirected to `/login`
3. Enter credentials:
   - **Login**: `admin`
   - **Password**: `password123`
4. Click "Sign In"
5. You should be redirected to the dashboard

## Backend Architecture Details

### Directory Structure

```
backend/src/
├── Command/
│   └── CreateUserCommand.php          # CLI command to create users
├── Controller/
│   └── User/
│       ├── CheckAuthController.php    # Check authentication status
│       └── LogoutController.php       # Handle logout
├── Dto/
│   └── User/
│       ├── LoginRequestDto.php        # Login request data
│       ├── UserResponseDto.php        # User response data
│       └── UserInternalDto.php        # Internal user operations
├── Entity/
│   └── User.php                       # User entity (Doctrine ORM)
├── Repository/
│   └── UserRepository.php             # User database operations
├── Security/
│   ├── AuthenticationSuccessHandler.php
│   └── AuthenticationFailureHandler.php
└── Service/
    ├── JsonResponse/
    │   ├── JsonResponseService.php
    │   └── JsonResponseServiceInterface.php
    └── User/
        ├── UserService.php
        └── UserServiceInterface.php
```

### Configuration Files

- **config/packages/security.yaml**: Security configuration (authentication, authorization)
- **config/packages/nelmio_cors.yaml**: CORS configuration for frontend communication
- **config/packages/framework.yaml**: Session configuration (if needed)

## Frontend Architecture Details

### Directory Structure

```
app/src/
├── app/
│   ├── providers.tsx                  # App providers wrapper
│   └── router.tsx                     # React Router configuration
├── features/
│   └── auth/
│       ├── api.ts                     # Authentication API calls
│       ├── hooks.ts                   # Auth context and hooks
│       ├── types.ts                   # TypeScript types
│       ├── LoginPage.tsx              # Login page component
│       └── LoginPage.css              # Login page styles
├── services/
│   ├── apiClient.ts                   # HTTP client wrapper
│   └── authStorage.ts                 # Session storage management
├── App.tsx                            # Root component
└── main.tsx                           # React entry point
```

## API Endpoints

### Authentication Endpoints

#### POST /api/auth/login
Login with credentials.

**Request:**
```json
{
  "login": "admin",
  "password": "password123"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Authentication successful",
  "data": {
    "id": 1,
    "name": "Admin User",
    "login": "admin",
    "role": "ROLE_ADMIN"
  }
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Authentication failed: Invalid credentials",
  "data": null
}
```

#### GET /api/auth/check
Check if user is authenticated.

**Success Response (200):**
```json
{
  "success": true,
  "message": "User is authenticated",
  "data": {
    "id": 1,
    "name": "Admin User",
    "login": "admin",
    "role": "ROLE_ADMIN"
  }
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Not authenticated",
  "data": null
}
```

#### POST /api/auth/logout
Logout current user.

**Success Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully",
  "data": null
}
```

## Common Issues & Solutions

### Issue: CORS Errors

**Symptom**: Browser console shows CORS policy errors

**Solution**:
1. Ensure `nelmio/cors-bundle` is installed:
   ```bash
   composer require nelmio/cors-bundle
   ```

2. Check `config/packages/nelmio_cors.yaml` includes your frontend URL

3. Make sure `credentials: 'include'` is set in frontend API calls

### Issue: Session Not Persisting

**Symptom**: User gets logged out on page refresh

**Solution**:
1. Check that `credentials: 'include'` is set in fetch requests
2. Verify backend allows credentials in CORS config:
   ```yaml
   allow_credentials: true
   ```
3. Ensure session cookie is being set (check browser DevTools > Application > Cookies)

### Issue: 404 on Login

**Symptom**: POST to /api/auth/login returns 404

**Solution**:
1. Clear Symfony cache:
   ```bash
   php bin/console cache:clear
   ```
2. Check routes:
   ```bash
   php bin/console debug:router
   ```
3. Verify security.yaml has correct `check_path: /api/auth/login`

### Issue: Password Not Working

**Symptom**: Login fails even with correct password

**Solution**:
1. Ensure password was hashed when creating user
2. Use the command to create users (it handles hashing automatically):
   ```bash
   php bin/console app:create-user username password "Name" --role=ROLE_USER
   ```

### Issue: TypeScript Errors in Frontend

**Symptom**: TypeScript compilation errors

**Solution**:
1. Install all dependencies:
   ```bash
   npm install
   ```
2. Ensure `react-router-dom` is installed:
   ```bash
   npm install react-router-dom
   ```
3. Check `tsconfig.json` has correct paths

## Database Commands

### Create Database
```bash
php bin/console doctrine:database:create
```

### Run Schema
```bash
psql -U user -d dbname -f database/shema.sql
```

### Create User via Command
```bash
# Create admin user
php bin/console app:create-user admin mypassword "Admin User" --role=ROLE_ADMIN

# Create regular user
php bin/console app:create-user john password123 "John Doe" --role=ROLE_USER
```

### Check Existing Users (PostgreSQL)
```sql
SELECT id, name, login, role, created_at FROM users;
```

## Environment Variables

### Backend (.env)
```env
APP_ENV=dev
APP_SECRET=your-secret-key-here
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/dbname"
```

### Frontend (.env)
```env
VITE_API_URL=http://localhost:8000
```

## Production Deployment

### Backend

1. Set environment to production:
   ```env
   APP_ENV=prod
   APP_DEBUG=0
   ```

2. Clear and warm up cache:
   ```bash
   php bin/console cache:clear --env=prod
   php bin/console cache:warmup --env=prod
   ```

3. Update CORS origins to include production frontend URL

4. Use HTTPS for secure cookie transmission

5. Configure session settings in `framework.yaml`:
   ```yaml
   framework:
       session:
           cookie_secure: true
           cookie_samesite: lax
   ```

### Frontend

1. Build the application:
   ```bash
   npm run build
   ```

2. Update API URL in production .env:
   ```env
   VITE_API_URL=https://api.yourdomain.com
   ```

3. For Tauri app:
   ```bash
   npm run tauri:build
   ```

## Next Steps

After setting up authentication, you can:

1. **Add more features** following the same architecture:
   - Create feature directories in `app/src/features/`
   - Create controller directories in `backend/src/Controller/`
   - Use the established service and DTO patterns

2. **Add role-based access control**:
   - Create AdminRoute component
   - Add access_control rules in security.yaml

3. **Add user management**:
   - Create user CRUD operations
   - Add user list and edit pages

4. **Add password reset functionality**:
   - Email verification
   - Password reset tokens

5. **Add remember me functionality**:
   - Already configured in security.yaml
   - Just needs UI checkbox

## Support

For issues or questions:
1. Check this documentation
2. Review AUTH_README.md for detailed architecture
3. Check Symfony and React Router documentation
4. Review browser console and network tab for errors
