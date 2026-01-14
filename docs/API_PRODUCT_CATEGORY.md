# Product Category API Documentation

## Overview
This document describes all available endpoints for Product Category management.

**Access Control:** All Create, Update, and Delete operations require `ROLE_ADMIN`.

---

## Endpoints

### 1. Create Category
**Path:** `POST /api/categories`

**Description:** Creates a new product category. **Admin only.**

**Access:** Requires `ROLE_ADMIN` role.

**Request Headers:**
```
Content-Type: application/json
Authorization: Required (admin user)
```

**Request Body:**
```json
{
  "name": "Electronics"
}
```

**Request Attributes:**
| Field | Type | Required | Validation | Description |
|-------|------|----------|------------|-------------|
| name | string | Yes | Max 50 chars, Unique | Category name |

**Success Response (201 Created):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 1,
    "name": "Electronics"
  },
  "errors": null
}
```

**Error Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "Category with this name already exists",
  "data": null,
  "errors": null
}
```

**Error Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "Access Denied",
  "data": null,
  "errors": null
}
```

---

### 2. Update Category
**Path:** `PUT /api/categories/{id}` or `PATCH /api/categories/{id}`

**Description:** Updates an existing category. **Admin only.**

**Access:** Requires `ROLE_ADMIN` role.

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Category ID |

**Request Headers:**
```
Content-Type: application/json
Authorization: Required (admin user)
```

**Request Body:**
```json
{
  "name": "Updated Electronics"
}
```

**Request Attributes:**
| Field | Type | Required | Validation | Description |
|-------|------|----------|------------|-------------|
| name | string | Yes | Max 50 chars, Unique | Category name |

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 1,
    "name": "Updated Electronics"
  },
  "errors": null
}
```

**Error Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "Category with this name already exists",
  "data": null,
  "errors": null
}
```

**Error Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "Access Denied",
  "data": null,
  "errors": null
}
```

**Error Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Category not found",
  "data": null,
  "errors": null
}
```

---

### 3. Get Single Category
**Path:** `GET /api/categories/{id}`

**Description:** Retrieves a single category by ID. **Public access.**

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Category ID |

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 1,
    "name": "Electronics"
  },
  "errors": null
}
```

**Error Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Category not found",
  "data": null,
  "errors": null
}
```

---

### 4. List All Categories
**Path:** `GET /api/categories`

**Description:** Retrieves all categories. **Public access.**

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Success",
  "data": [
    {
      "id": 1,
      "name": "Electronics"
    },
    {
      "id": 2,
      "name": "Furniture"
    },
    {
      "id": 3,
      "name": "Clothing"
    }
  ],
  "errors": null
}
```

---

### 5. Delete Category
**Path:** `DELETE /api/categories/{id}`

**Description:** Deletes a category. **Admin only.** Cannot delete if category has associated products.

**Access:** Requires `ROLE_ADMIN` role.

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Category ID |

**Request Headers:**
```
Authorization: Required (admin user)
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "message": "Category deleted successfully"
  },
  "errors": null
}
```

**Error Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "Cannot delete category with existing products",
  "data": null,
  "errors": null
}
```

**Error Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "Access Denied",
  "data": null,
  "errors": null
}
```

**Error Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Category not found",
  "data": null,
  "errors": null
}
```

---

## Data Model

### Product Category Entity
| Field | Type | Nullable | Description |
|-------|------|----------|-------------|
| id | integer | No | Primary key |
| name | string(50) | No | Unique category name |

---

## Security

### Role-Based Access Control

| Endpoint | HTTP Method | Required Role | Description |
|----------|-------------|---------------|-------------|
| POST /api/categories | POST | ROLE_ADMIN | Create category |
| PUT /api/categories/{id} | PUT/PATCH | ROLE_ADMIN | Update category |
| GET /api/categories/{id} | GET | None | Get single category |
| GET /api/categories | GET | None | List all categories |
| DELETE /api/categories/{id} | DELETE | ROLE_ADMIN | Delete category |

### Authentication
- Admin-protected endpoints return `403 Forbidden` if accessed without proper authentication or role.
- Users must be authenticated with `ROLE_ADMIN` to perform Create, Update, and Delete operations.
- Read operations (GET) are publicly accessible.

---

## Error Codes

| HTTP Status | Description |
|-------------|-------------|
| 200 | Success |
| 201 | Created successfully |
| 400 | Bad request (validation or business logic error) |
| 403 | Forbidden (insufficient permissions) |
| 404 | Resource not found |
| 422 | Validation error |
| 500 | Internal server error |

---

## Business Rules

1. **Unique Names:** Category names must be unique across the system.

2. **Protected Delete:** Categories with associated products cannot be deleted. Remove all products from the category first.

3. **Admin Only Modifications:** Only users with `ROLE_ADMIN` can create, update, or delete categories.

4. **Public Read Access:** All users can view categories and category details.

---

## Notes

1. **Simple CRUD:** Unlike products, categories use direct updates without history tracking.

2. **Cascade Protection:** The system prevents deletion of categories that have products to maintain data integrity.

3. **Name Uniqueness:** The system enforces unique category names to prevent confusion.
