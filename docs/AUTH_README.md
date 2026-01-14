# Authentication System

Complete authentication system using Symfony Security with session-based (stateful) authentication.

## Backend (Symfony)

### Architecture

The backend follows a clean architecture pattern with:

- **Controllers**: One action per controller using `__invoke` pattern
  - `CheckAuthController`: Verify authentication status
  - `LogoutController`: Handle user logout

- **Services**: Business logic separated from controllers
  - `UserService`: User-related operations
  - `JsonResponseService`: Standardized JSON responses

- **DTOs**: Data Transfer Objects for type safety (organized by type)
  - `request/LoginRequestDto`: Login credentials
  - `response/UserResponseDto`: User data response
  - `internal/UserInternalDto`: Internal user operations

- **Entities**: Doctrine ORM entities
  - `User`: User entity implementing Symfony's UserInterface

- **Security Handlers**:
  - `AuthenticationSuccessHandler`: Custom success response
  - `AuthenticationFailureHandler`: Custom failure response

### Security Configuration

The system uses:
- **JSON Login**: Form-based authentication with JSON payloads
- **Sessions**: Stateful authentication using server-side sessions
- **Password Hashing**: Automatic password hashing with bcrypt
- **CSRF Protection**: Built-in Symfony CSRF protection

### API Endpoints

```
POST /api/auth/login    - Login with credentials
POST /api/auth/logout   - Logout current user
GET  /api/auth/check    - Check authentication status
```

### User Roles

- `ROLE_ADMIN`: Administrator access
- `ROLE_USER`: Standard user access

Only one role per user is supported.

## Frontend (React + TypeScript + Tauri)

### Architecture

Feature-based architecture with:

```
src/
├─ app/                    # App configuration
│  ├─ router.tsx           # React Router setup with protected routes
│  └─ providers.tsx        # App providers wrapper
│
├─ features/               # Domain-driven features
│  └─ auth/
│     ├─ api.ts            # Auth API calls
│     ├─ hooks.ts          # Auth context and hooks
│     ├─ types.ts          # Type definitions
│     ├─ LoginPage.tsx     # Login UI
│     └─ LoginPage.css     # Login styles
│
└─ services/               # Technical services
   ├─ apiClient.ts         # HTTP client with fetch wrapper
   └─ authStorage.ts       # Session management
```

### Features

- **AuthProvider**: React Context for global auth state
- **useAuth hook**: Access authentication state anywhere
- **Protected Routes**: Automatic redirect for unauthenticated users
- **Public Routes**: Redirect to dashboard if already authenticated
- **Session Persistence**: Auto-check auth on app load
- **Error Handling**: User-friendly error messages

### Usage Example

```tsx
import { useAuth } from './features/auth/hooks';

function MyComponent() {
  const { user, isAuthenticated, logout } = useAuth();

  return (
    <div>
      {isAuthenticated ? (
        <>
          <p>Welcome, {user?.name}!</p>
          <p>Role: {user?.role}</p>
          <button onClick={logout}>Logout</button>
        </>
      ) : (
        <p>Please login</p>
      )}
    </div>
  );
}
```

## Database Schema

```sql
CREATE TABLE users(
   id SERIAL,
   name VARCHAR(50) NOT NULL,
   login VARCHAR(50) NOT NULL,
   password VARCHAR(255) NOT NULL,
   role VARCHAR(20) CHECK (role IN ('ROLE_ADMIN', 'ROLE_USER')) NOT NULL,
   created_at DATE NOT NULL,
   PRIMARY KEY(id),
   UNIQUE(login)
);
```

## Setup Instructions

### Backend Setup

1. Install dependencies:
```bash
cd backend
composer install
```

2. Configure database in `.env`:
```
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/dbname"
```

3. Run migrations (create users table from schema.sql)

4. Start Symfony server:
```bash
symfony server:start
```

### Frontend Setup

1. Install dependencies:
```bash
cd app
npm install
```

2. Configure API URL in `.env`:
```
VITE_API_URL=http://localhost:8000
```

3. Run development server:
```bash
npm run dev
```

Or run Tauri app:
```bash
npm run tauri:dev
```

## Security Considerations

- Passwords are hashed using Symfony's automatic password hasher (bcrypt)
- Sessions are stored server-side with secure cookie settings
- CSRF protection is enabled for all forms
- API credentials are sent via session cookies (HttpOnly, Secure, SameSite)
- No tokens stored in localStorage (session-based authentication)

## Testing Authentication

### Create a Test User

```php
// In Symfony console or migration:
$user = new User();
$user->setName('Admin User');
$user->setLogin('admin');
$user->setPassword($passwordHasher->hashPassword($user, 'password123'));
$user->setRole('ROLE_ADMIN');
$user->setCreatedAt(new \DateTime());
$entityManager->persist($user);
$entityManager->flush();
```

### Test Login

1. Navigate to `/login`
2. Enter credentials:
   - Login: `admin`
   - Password: `password123`
3. Click "Sign In"
4. Should redirect to `/dashboard`

## Extending the System

### Add New Protected Route

```tsx
// In app/router.tsx
<Route
  path="/settings"
  element={
    <ProtectedRoute>
      <SettingsPage />
    </ProtectedRoute>
  }
/>
```

### Add Role-Based Access

```tsx
function AdminRoute({ children }: { children: React.ReactNode }) {
  const { user, isAuthenticated } = useAuth();

  if (!isAuthenticated || user?.role !== 'ROLE_ADMIN') {
    return <Navigate to="/dashboard" replace />;
  }

  return <>{children}</>;
}
```

### Add New API Endpoint

1. Create Controller in `backend/src/Controller/[Entity]/`
2. Create Service in `backend/src/Service/[Entity]/`
3. Create DTOs in `backend/src/Dto/[Entity]/`
4. Use `JsonResponseService` for standardized responses

## Troubleshooting

### CORS Issues

Add to `backend/config/packages/nelmio_cors.yaml`:
```yaml
nelmio_cors:
    defaults:
        origin_regex: true
        allow_origin: ['http://localhost:1420']
        allow_methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']
        allow_headers: ['Content-Type', 'Authorization']
        expose_headers: ['Content-Type']
        allow_credentials: true
        max_age: 3600
```

### Session Not Persisting

Ensure:
1. `credentials: 'include'` is set in fetch requests
2. Backend allows credentials in CORS configuration
3. Cookie settings are correct in `framework.yaml`

### Login Not Working

Check:
1. User exists in database
2. Password is properly hashed
3. Backend server is running
4. Network tab shows 200 response from `/api/auth/login`
5. Session cookie is set in browser
