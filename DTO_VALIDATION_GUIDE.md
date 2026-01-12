# DTO Structure & Validation Guide

## DTO Organization

All DTOs are organized by entity and purpose:

```
backend/src/Dto/
├── Common/
│   └── ErrorResponseDto.php          # Validation error structure
│
└── User/
    ├── Request/                       # Input DTOs
    │   └── LoginRequestDto.php       # ✅ With validation
    ├── Response/                      # Output DTOs
    │   └── UserResponseDto.php       # ✅ With validation
    └── Internal/                      # Internal operation DTOs
        └── UserInternalDto.php       # Business logic
```

## DTO Example with Validation

### Request DTO (User Input)

```php
<?php

namespace App\Dto\User\Request;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequestDto
{
    #[Assert\NotBlank(message: 'Login is required')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Login must be at least {{ limit }} characters',
        maxMessage: 'Login cannot be longer than {{ limit }} characters'
    )]
    private string $login;

    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Length(
        min: 6,
        minMessage: 'Password must be at least {{ limit }} characters'
    )]
    private string $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
```

### Response DTO (API Output)

```php
<?php

namespace App\Dto\User\Response;

use Symfony\Component\Validator\Constraints as Assert;

class UserResponseDto
{
    #[Assert\Positive]
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $login;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['ROLE_ADMIN', 'ROLE_USER'])]
    private string $role;

    public function __construct(int $id, string $name, string $login, string $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->login = $login;
        $this->role = $role;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'login' => $this->login,
            'role' => $this->role,
        ];
    }
}
```

## API Response Format

All API responses follow this structure:

```json
{
  "success": true|false,
  "message": "Human readable message",
  "data": {} | null,
  "errors": [
    {
      "field": "login",
      "message": "Login is required"
    }
  ] | null
}
```

### Success Response Example

```json
{
  "success": true,
  "message": "Authentication successful",
  "data": {
    "id": 1,
    "name": "Admin User",
    "login": "admin",
    "role": "ROLE_ADMIN"
  },
  "errors": null
}
```

### Validation Error Response (422)

```json
{
  "success": false,
  "message": "Validation failed",
  "data": null,
  "errors": [
    {
      "field": "login",
      "message": "Login must be at least 3 characters"
    },
    {
      "field": "password",
      "message": "Password is required"
    }
  ]
}
```

### Generic Error Response

```json
{
  "success": false,
  "message": "Authentication failed: Invalid credentials",
  "data": null,
  "errors": null
}
```

## Frontend Error Handling

### TypeScript Types

```typescript
interface ValidationError {
  field: string;
  message: string;
}

interface ApiResponse<T = any> {
  success: boolean;
  message: string;
  data: T | null;
  errors: ValidationError[] | null;
}
```

### Login Page with Error Display

The LoginPage component now:
- ✅ Displays general error messages
- ✅ Shows field-specific validation errors
- ✅ Highlights invalid fields with red border
- ✅ Shows error text below each invalid field

### Example Usage

```tsx
import { ApiError } from '../../services/apiClient';

try {
  await login(credentials);
} catch (err: any) {
  if (err instanceof ApiError && err.validationErrors) {
    // Handle validation errors
    err.validationErrors.forEach((error) => {
      console.log(`${error.field}: ${error.message}`);
    });
  } else {
    // Handle general errors
    console.error(err.message);
  }
}
```

## Common Validation Constraints

### Available Constraints

```php
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\NotBlank]                    // Field cannot be empty
#[Assert\Length(min: 3, max: 50)]     // String length
#[Assert\Email]                       // Valid email format
#[Assert\Positive]                    // Positive number
#[Assert\Choice(choices: ['A', 'B'])] // Must be one of choices
#[Assert\Regex(pattern: '/^\d+$/')]   // Regex pattern
#[Assert\GreaterThan(value: 0)]       // Numeric comparison
#[Assert\LessThanOrEqual(value: 100)] // Numeric comparison
#[Assert\Url]                         // Valid URL
#[Assert\Unique]                      // Unique in database
```

## Creating New DTOs

### 1. Create Request DTO

```php
<?php

namespace App\Dto\Product\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductRequestDto
{
    #[Assert\NotBlank(message: 'Product name is required')]
    #[Assert\Length(max: 100)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Positive]
    private float $price;

    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    // Getters...
}
```

### 2. Create Response DTO

```php
<?php

namespace App\Dto\Product\Response;

class ProductResponseDto
{
    private int $id;
    private string $name;
    private float $price;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
        ];
    }
}
```

### 3. Use in Controller

```php
use App\Service\Validation\ValidationService;
use App\Dto\Product\Request\CreateProductRequestDto;

public function __invoke(Request $request): JsonResponse
{
    $data = $request->toArray();
    $dto = new CreateProductRequestDto($data['name'], $data['price']);
    
    // Validate
    $errors = $this->validationService->getErrorsArray($dto);
    if (!empty($errors)) {
        return $this->jsonResponseService->validationError($errors);
    }
    
    // Process...
    return $this->jsonResponseService->success($result);
}
```

## Testing Validation

### Test Invalid Login (too short)

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"login": "ab", "password": "123"}'
```

Expected response (422):
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": [
    {"field": "login", "message": "Login must be at least 3 characters"},
    {"field": "password", "message": "Password must be at least 6 characters"}
  ]
}
```

### Test Empty Fields

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"login": "", "password": ""}'
```

Expected response (422):
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": [
    {"field": "login", "message": "Login is required"},
    {"field": "password", "message": "Password is required"}
  ]
}
```

## Services

### ValidationService

```php
// Check for errors
if ($this->validationService->hasErrors($dto)) {
    $errors = $this->validationService->getErrorsArray($dto);
    return $this->jsonResponseService->validationError($errors);
}
```

### JsonResponseService

```php
// Success
$this->jsonResponseService->success($data, 'Operation successful');

// Validation error (422)
$this->jsonResponseService->validationError($errors);

// Generic error
$this->jsonResponseService->error('Error message', 400);
```

## Files Created

✅ `Dto/Common/ErrorResponseDto.php` - Validation error structure
✅ `Service/Validation/ValidationService.php` - Validation logic
✅ `Service/Validation/ValidationServiceInterface.php` - Interface
✅ Updated `JsonResponseService` - Added `validationError()` method
✅ Updated `LoginRequestDto` - Added validation constraints
✅ Updated `UserResponseDto` - Added validation constraints
✅ Updated frontend `apiClient.ts` - Added `ValidationError` type
✅ Updated `LoginPage.tsx` - Display validation errors
✅ Created `ErrorAlert.tsx` - Reusable error component

## Next Steps

1. **Add validation to other DTOs** following the same pattern
2. **Create custom validators** for complex business rules
3. **Add client-side validation** for better UX
4. **Create error boundary** for global error handling
