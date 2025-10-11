# PageFlash REST API Documentation

This document describes the REST API endpoints available for managing PageFlash landmarks.

## Base URL

```
/wp-json/pageflash/v1
```

## Authentication

- **GET endpoints**: No authentication required
- **POST/PUT endpoints**: Requires WordPress authentication and `manage_options` capability
- **Nonce verification**: POST/PUT endpoints require `X-WP-Nonce` header with a valid WordPress REST API nonce

## Endpoints

### 1. Get All Landmarks

Retrieves all landmarks registered in PageFlash.

**Endpoint:** `GET /landmark`

**Request:**
```bash
curl -X GET "https://example.com/wp-json/pageflash/v1/landmark"
```

**Response:**
```json
{
  "version": "1.0.0",
  "author": "PageFlash Team",
  "url": "https://github.com/theaminuli/pageflash/",
  "message": "PageFlash Dashboard Data",
  "data": {
    "quicklink": {
      "id": 1001,
      "label": "Quicklink",
      "description": "Quicklink, an active plugin...",
      "active": true,
      "slug": "quicklink",
      "package": "free"
    },
    "instantpage": {
      "id": 1002,
      "label": "InstantPage",
      "description": "InstantPage uses just-in-time preloading...",
      "active": true,
      "slug": "instantpage",
      "package": "free"
    }
  }
}
```

---

### 2. Get Landmark by ID

Retrieves a specific landmark by its ID.

**Endpoint:** `GET /landmark/:id`

**Parameters:**
- `id` (required): The landmark ID (e.g., 1001, 1002)

**Request:**
```bash
curl -X GET "https://example.com/wp-json/pageflash/v1/landmark/1001"
```

**Success Response (200):**
```json
{
  "id": 1001,
  "label": "Quicklink",
  "description": "Quicklink, an active plugin...",
  "active": true,
  "slug": "quicklink",
  "package": "free"
}
```

**Error Response (404):**
```json
{
  "code": "not_found",
  "message": "Landmark not found",
  "data": {
    "status": 404
  }
}
```

---

### 3. Update Landmark by ID

Updates a landmark's properties (currently supports updating the `active` field).

**Endpoint:** `PUT /landmark/:id`

**Parameters:**
- `id` (required): The landmark ID (e.g., 1001, 1002)

**Request Body:**
```json
{
  "active": false
}
```

**Authentication Required:**
- User must have `manage_options` capability
- Request must include `X-WP-Nonce` header with valid nonce

**Request Example (JavaScript):**
```javascript
const response = await fetch('https://example.com/wp-json/pageflash/v1/landmark/1001', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce // WordPress provides this in wp.apiFetch
  },
  body: JSON.stringify({
    active: false
  })
});
```

**Request Example (cURL):**
```bash
# First, get the nonce from WordPress
# Then make the request:
curl -X PUT "https://example.com/wp-json/pageflash/v1/landmark/1001" \
  -H "Content-Type: application/json" \
  -H "X-WP-Nonce: YOUR_NONCE_HERE" \
  -H "Cookie: YOUR_AUTH_COOKIE_HERE" \
  -d '{"active": false}'
```

**Success Response (200):**
```json
{
  "message": "Landmark updated successfully",
  "data": {
    "id": 1001,
    "label": "Quicklink",
    "description": "Quicklink, an active plugin...",
    "active": false,
    "slug": "quicklink",
    "package": "free"
  }
}
```

**Error Responses:**

**403 - Security Check Failed:**
```json
{
  "code": "invalid_nonce",
  "message": "Security check failed",
  "data": {
    "status": 403
  }
}
```

**400 - Missing Data:**
```json
{
  "code": "missing_data",
  "message": "No valid update data provided",
  "data": {
    "status": 400
  }
}
```

**404 - Not Found:**
```json
{
  "code": "not_found",
  "message": "Landmark not found",
  "data": {
    "status": 404
  }
}
```

---

### 4. Legacy POST Endpoint (Deprecated)

Legacy endpoint maintained for backward compatibility. Updates all landmarks or a specific landmark.

**Endpoint:** `POST /landmarks`

**Note:** This endpoint is deprecated. Use the PUT endpoint for updating landmarks.

---

## WordPress Integration Examples

### Using wp.apiFetch (Recommended for WordPress)

```javascript
// Get all landmarks
wp.apiFetch({
  path: '/pageflash/v1/landmark'
}).then(data => {
  console.log('All landmarks:', data);
});

// Get landmark by ID
wp.apiFetch({
  path: '/pageflash/v1/landmark/1001'
}).then(data => {
  console.log('Landmark 1001:', data);
});

// Update landmark
wp.apiFetch({
  path: '/pageflash/v1/landmark/1001',
  method: 'PUT',
  data: {
    active: false
  }
}).then(response => {
  console.log('Updated:', response);
});
```

### Using Fetch API

```javascript
// Get nonce from WordPress
const nonce = document.getElementById('_wpnonce').value; // Or from wpApiSettings

// Update landmark
fetch('/wp-json/pageflash/v1/landmark/1001', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': nonce
  },
  body: JSON.stringify({
    active: false
  })
})
.then(response => response.json())
.then(data => console.log('Success:', data))
.catch(error => console.error('Error:', error));
```

---

## Security Features

1. **Nonce Verification**: All POST/PUT endpoints verify WordPress nonces via `X-WP-Nonce` header
2. **Capability Checks**: Modifying endpoints require `manage_options` capability
3. **Input Sanitization**: All input is sanitized using WordPress core functions
4. **Input Validation**: Request parameters are validated before processing
5. **Output Escaping**: All text output is internationalized and properly escaped

---

## Error Handling

All endpoints follow WordPress REST API error handling conventions:

- Successful responses return HTTP 200 with JSON data
- Error responses include:
  - `code`: Machine-readable error code
  - `message`: Human-readable error message
  - `data.status`: HTTP status code

---

## Testing

### Testing GET Endpoints

```bash
# Test getting all landmarks
curl -i "https://your-site.com/wp-json/pageflash/v1/landmark"

# Test getting landmark by ID
curl -i "https://your-site.com/wp-json/pageflash/v1/landmark/1001"

# Test non-existent landmark (should return 404)
curl -i "https://your-site.com/wp-json/pageflash/v1/landmark/9999"
```

### Testing PUT Endpoint (Requires Authentication)

1. Log in to WordPress admin
2. Open browser developer console
3. Run the following JavaScript:

```javascript
wp.apiFetch({
  path: '/pageflash/v1/landmark/1001',
  method: 'PUT',
  data: {
    active: false
  }
}).then(console.log).catch(console.error);
```

---

## Version History

- **1.0.0** (2025-10-11)
  - Initial REST API implementation
  - Added GET /landmark endpoint
  - Added GET /landmark/:id endpoint
  - Added PUT /landmark/:id endpoint
  - Implemented nonce verification for security
  - Added comprehensive input sanitization and validation
  - Added WordPress coding standards compliance

---

## Support

For issues or questions, please visit:
- GitHub: https://github.com/theaminuli/pageflash/
- Issues: https://github.com/theaminuli/pageflash/issues
