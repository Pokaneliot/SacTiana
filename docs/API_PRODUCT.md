# Product API Documentation

## Overview
This document describes all available endpoints for Product management.

---

## Endpoints

### 1. Create Product
**Path:** `POST /api/products`

**Description:** Creates a new product in the system.

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "ref": "PROD001",
  "name": "Product Name",
  "purchasePrice": 100.50,
  "sellingPrice": 150.00,
  "categoryId": 1
}
```

**Request Attributes:**
| Field | Type | Required | Validation | Description |
|-------|------|----------|------------|-------------|
| ref | string | Yes | Max 50 chars | Product reference code |
| name | string | No | Max 50 chars | Product name |
| purchasePrice | float | Yes | Positive | Purchase price |
| sellingPrice | float | Yes | Positive | Selling price |
| categoryId | integer | Yes | Positive | Category ID |

**Success Response (201 Created):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 1,
    "ref": "PROD001",
    "name": "Product Name",
    "purchasePrice": 100.50,
    "sellingPrice": 150.00,
    "createdAt": "2026-01-14",
    "quantity": null,
    "category": {
      "id": 1,
      "name": "Electronics"
    },
    "lastUpdateAt": null
  },
  "errors": null
}
```

**Error Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "Category not found",
  "data": null,
  "errors": null
}
```

**Validation Error Response (422 Unprocessable Entity):**
```json
{
  "success": false,
  "message": "Validation failed",
  "data": null,
  "errors": {
    "ref": ["Reference is required"],
    "purchasePrice": ["Purchase price must be positive"]
  }
}
```

---

### 2. Update Product
**Path:** `PUT /api/products/{id}` or `PATCH /api/products/{id}`

**Description:** Updates an existing product. Creates a record in the `product_update` table with only the changed fields. The original product record remains unchanged.

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Product ID |

**Request Headers:**
```
Content-Type: application/json
```

**Request Body (all fields optional):**
```json
{
  "ref": "PROD001-UPDATED",
  "name": "Updated Product Name",
  "purchasePrice": 110.00,
  "sellingPrice": 160.00
}
```

**Request Attributes:**
| Field | Type | Required | Validation | Description |
|-------|------|----------|------------|-------------|
| ref | string | No | Max 50 chars | Updated product reference |
| name | string | No | Max 50 chars | Updated product name |
| purchasePrice | float | No | Positive | Updated purchase price |
| sellingPrice | float | No | Positive | Updated selling price |

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 1,
    "ref": "PROD001-UPDATED",
    "name": "Updated Product Name",
    "purchasePrice": 110.00,
    "sellingPrice": 160.00,
    "createdAt": "2026-01-14",
    "quantity": 50,
    "category": {
      "id": 1,
      "name": "Electronics"
    },
    "lastUpdateAt": "2026-01-14"
  },
  "errors": null
}
```

**Error Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Product not found",
  "data": null,
  "errors": null
}
```

**Note:** Only the fields provided in the request are stored in the `product_update` table. Other fields are set to null in the update record.

---

### 3. Get Single Product
**Path:** `GET /api/products/{id}`

**Description:** Retrieves a single product by ID. Returns product data merged with the latest update from `product_update` table and includes stock quantity from `product_stock` table.

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Product ID |

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 1,
    "ref": "PROD001-UPDATED",
    "name": "Updated Product Name",
    "purchasePrice": 110.00,
    "sellingPrice": 160.00,
    "createdAt": "2026-01-14",
    "quantity": 50,
    "category": {
      "id": 1,
      "name": "Electronics"
    },
    "lastUpdateAt": "2026-01-14"
  },
  "errors": null
}
```

**Error Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Product not found",
  "data": null,
  "errors": null
}
```

**Response Attributes:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Product ID |
| ref | string | Current product reference (from latest update or original) |
| name | string | Current product name (from latest update or original) |
| purchasePrice | float | Current purchase price (from latest update or original) |
| sellingPrice | float | Current selling price (from latest update or original) |
| createdAt | string | Product creation date (YYYY-MM-DD) |
| quantity | integer/null | Current stock quantity |
| category | object | Category information |
| category.id | integer | Category ID |
| category.name | string | Category name |
| lastUpdateAt | string/null | Date of last update (YYYY-MM-DD) |

---

### 4. List Products (with Search & Filter)
**Path:** `GET /api/products`

**Description:** Retrieves all products with optional search and filtering. Each product includes merged data from latest updates and stock quantity.

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| ref | string | No | Search by product reference (partial match) |
| sellingPrice | float | No | Filter by exact selling price |
| createdAt | string | No | Filter by creation date (YYYY-MM-DD) |
| categoryId | integer | No | Filter by category ID |

**Example Requests:**
```
GET /api/products
GET /api/products?ref=PROD
GET /api/products?categoryId=1
GET /api/products?ref=PROD&categoryId=1&sellingPrice=150.00
GET /api/products?createdAt=2026-01-14
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Success",
  "data": [
    {
      "id": 1,
      "ref": "PROD001",
      "name": "Product 1",
      "purchasePrice": 100.50,
      "sellingPrice": 150.00,
      "createdAt": "2026-01-14",
      "quantity": 50,
      "category": {
        "id": 1,
        "name": "Electronics"
      },
      "lastUpdateAt": "2026-01-14"
    },
    {
      "id": 2,
      "ref": "PROD002",
      "name": "Product 2",
      "purchasePrice": 200.00,
      "sellingPrice": 300.00,
      "createdAt": "2026-01-14",
      "quantity": 25,
      "category": {
        "id": 2,
        "name": "Furniture"
      },
      "lastUpdateAt": null
    }
  ],
  "errors": null
}
```

**Response Attributes:**
Same as "Get Single Product" response, but returns an array of products.

---

### 5. Delete Product
**Path:** `DELETE /api/products/{id}`

**Description:** Deletes a product from the system. Cascade deletes all related records in `product_update` and `product_stock` tables.

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Product ID |

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "message": "Product deleted successfully"
  },
  "errors": null
}
```

**Error Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Product not found",
  "data": null,
  "errors": null
}
```

---

## Data Models

### Product Entity
| Field | Type | Nullable | Description |
|-------|------|----------|-------------|
| id | integer | No | Primary key |
| ref | string(50) | No | Product reference code |
| name | string(50) | Yes | Product name |
| purchasePrice | decimal(15,2) | No | Purchase price |
| sellingPrice | decimal(15,2) | No | Selling price |
| createdAt | date | No | Creation date |
| id_category | integer | No | Foreign key to category |

### Product Update Entity
| Field | Type | Nullable | Description |
|-------|------|----------|-------------|
| id | integer | No | Primary key |
| ref | string(50) | Yes | Updated reference (null if unchanged) |
| name | string(50) | Yes | Updated name (null if unchanged) |
| purchasePrice | decimal(15,2) | Yes | Updated purchase price (null if unchanged) |
| sellingPrice | decimal(15,2) | Yes | Updated selling price (null if unchanged) |
| updateAt | date | No | Update date |
| id_product | integer | No | Foreign key to product |

### Product Stock Entity
| Field | Type | Nullable | Description |
|-------|------|----------|-------------|
| id | integer | No | Primary key |
| quantity | integer | Yes | Stock quantity |
| id_product | integer | No | Foreign key to product |

---

## Error Codes

| HTTP Status | Description |
|-------------|-------------|
| 200 | Success |
| 201 | Created successfully |
| 400 | Bad request (runtime error) |
| 404 | Resource not found |
| 422 | Validation error |
| 500 | Internal server error |

---

## Notes

1. **Update Behavior:** When updating a product, the original product record is NOT modified. Instead, a new record is created in the `product_update` table containing only the changed fields. When retrieving product data, the system automatically merges the original product data with the latest update.

2. **Stock Quantity:** The `quantity` field is retrieved from the `product_stock` table through a join operation. It may be `null` if no stock record exists for the product.

3. **Cascade Delete:** Deleting a product will automatically delete all related records in `product_update` and `product_stock` tables.

4. **Search vs Filter:** 
   - `ref` parameter performs a partial match search (LIKE %value%)
   - Other parameters perform exact match filtering

5. **Date Format:** All dates in requests and responses use the ISO 8601 format: `YYYY-MM-DD`
