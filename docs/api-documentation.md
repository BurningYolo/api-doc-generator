# My Awesome API

This is a comprehensive API for managing users, posts, and comments.

**Version:** 2.0.0

**Base URL:** `https://api.example.com/v2`

---

## Table of Contents

1. [Get All Users](#get-all-users)
2. [Get User by ID](#get-user-by-id)
3. [Create New User](#create-new-user)
4. [Update User](#update-user)
5. [Delete User](#delete-user)

---

## Get All Users

Retrieve a paginated list of all users in the system.

**Endpoint:** `GET /users`

### Headers

| Name | Type | Required | Description |
|------|------|----------|-------------|
| Accept | string | Yes | application/json |

### Query Parameters

| Name | Type | Required | Default | Description |
|------|------|----------|---------|-------------|
| page | integer | No | 1 | Page number for pagination |
| limit | integer | No | 10 | Number of items per page |
| sort | string | No | created_at | Sort field (name, email, created_at) |

### Responses

#### 200 - Success

```json
{
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2024-01-15T10:30:00Z"
        }
    ],
    "meta": {
        "page": 1,
        "limit": 10,
        "total": 150
    }
}
```

#### 500 - Internal Server Error

```json
{
    "error": "An unexpected error occurred"
}
```

---

## Get User by ID

Retrieve detailed information about a specific user.

**Endpoint:** `GET /users/{id}`

### Headers

| Name | Type | Required | Description |
|------|------|----------|-------------|
| Accept | string | Yes | application/json |

### Path Parameters

| Name | Type | Description |
|------|------|-------------|
| id | integer | The unique identifier of the user |

### Responses

#### 200 - Success

```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "bio": "Software developer",
    "created_at": "2024-01-15T10:30:00Z"
}
```

#### 404 - User Not Found

```json
{
    "error": "User not found"
}
```

---

## Create New User

Create a new user account in the system.

**Endpoint:** `POST /users`

### Authentication

This endpoint requires authentication.

- **Type:** Bearer
- **Description:** Admin access token required

### Headers

| Name | Type | Required | Description |
|------|------|----------|-------------|
| Authorization | string | Yes | Bearer token |
| Content-Type | string | Yes | application/json |

### Request Body

| Name | Type | Required | Description | Example |
|------|------|----------|-------------|----------|
| name | string | Yes | Full name of the user | John Doe |
| email | string | Yes | Email address (must be unique) | john@example.com |
| password | string | Yes | Password (min 8 characters) | SecurePass123! |
| role | string | No | User role (admin, user) | user |

**Example Request:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePass123!",
    "role": "user"
}
```

### Responses

#### 201 - User Created Successfully

```json
{
    "id": 123,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    "created_at": "2024-01-15T10:30:00Z"
}
```

#### 400 - Validation Error

```json
{
    "error": "Validation failed",
    "details": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

#### 401 - Unauthorized

```json
{
    "error": "Invalid or missing authentication token"
}
```

---

## Update User

Update an existing user's information.

**Endpoint:** `PUT /users/{id}`

### Authentication

This endpoint requires authentication.

- **Type:** Bearer
- **Description:** User must be authenticated and authorized

### Headers

| Name | Type | Required | Description |
|------|------|----------|-------------|
| Authorization | string | Yes | Bearer token |
| Content-Type | string | Yes | application/json |

### Path Parameters

| Name | Type | Description |
|------|------|-------------|
| id | integer | User ID to update |

### Request Body

| Name | Type | Required | Description | Example |
|------|------|----------|-------------|----------|
| name | string | No | Updated full name | Jane Doe |
| email | string | No | Updated email address | jane@example.com |
| bio | string | No | User biography | Updated bio text |

**Example Request:**

```json
{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "bio": "Updated bio text"
}
```

### Responses

#### 200 - User Updated Successfully

```json
{
    "id": 123,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "bio": "Updated bio text",
    "updated_at": "2024-01-15T11:30:00Z"
}
```

#### 403 - Forbidden

```json
{
    "error": "You do not have permission to update this user"
}
```

#### 404 - Not Found

```json
{
    "error": "User not found"
}
```

---

## Delete User

Permanently delete a user from the system.

**Endpoint:** `DELETE /users/{id}`

### Authentication

This endpoint requires authentication.

- **Type:** Bearer
- **Description:** Admin access token required

### Headers

| Name | Type | Required | Description |
|------|------|----------|-------------|
| Authorization | string | Yes | Bearer token |

### Path Parameters

| Name | Type | Description |
|------|------|-------------|
| id | integer | User ID to delete |

### Responses

#### 204 - User Deleted Successfully (No Content)

#### 403 - Forbidden

```json
{
    "error": "Only admins can delete users"
}
```

#### 404 - Not Found

```json
{
    "error": "User not found"
}
```

---


---

*Documentation generated on 2025-10-05 09:30:53*
