# PageFlash REST API Documentation

## Overview

The PageFlash REST API provides endpoints for managing landmarks (performance optimization features) in the PageFlash WordPress plugin. All endpoints use the `/wp-json/pageflash/v1` namespace.

## Base URL

```
https://your-site.com/wp-json/pageflash/v1
```

## Authentication

- **GET** requests: No authentication required (public access)
- **PUT/POST/DELETE** requests: Requires WordPress authentication with `manage_options` capability
- Include `X-WP-Nonce` header for authenticated requests

## Endpoints

### 1. Get All Landmarks

Retrieve all registered landmarks with their configuration.

#### Request

```
GET /wp-json/pageflash/v1/landmark
```

#### Parameters

None

#### Response

**Success (200 OK)**

```json
{
  "version": "1.0.0",
  "author": "PageFlash Team",
  "url": "https://github.com/theaminuli/pageflash/",
  "message": "PageFlash Dashboard Data",
  "data": {
    "quicklink": {
      "id": "pf-1",
      "label": "Quicklink",
      "description": "Quicklink, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading.",
      "active": true,
      "slug": "quicklink",
      "tabs": "general",
      "package": "free"
    },
    "noreload": {
      "id": "pf-2",
      "label": "NoReload",
      "description": "NoReload prevents page reloads by intercepting navigation and loading content dynamically.",
      "active": false,
      "slug": "noreload",
      "package": "free"
    },
    "instantpage": {
      "id": "pf-3",
      "label": "InstantPage",
      "description": "InstantPage uses just-in-time preloading — it preloads a page right before a user clicks on it.",
      "active": false,
      "slug": "instantpage",
      "tabs": "general",
      "package": "free"
    }
  }
}
```

#### Example

```bash
curl -X GET https://your-site.com/wp-json/pageflash/v1/landmark
```

```javascript
// Using fetch API
fetch('https://your-site.com/wp-json/pageflash/v1/landmark')
  .then(response => response.json())
  .then(data => console.log(data));
```

---

### 2. Get Landmark by ID

Retrieve a specific landmark by its ID.

#### Request

```
GET /wp-json/pageflash/v1/landmark/:id
```

#### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | The unique ID of the landmark |

#### Response

**Success (200 OK)**

```json
{
  "message": "Landmark retrieved successfully",
  "data": {
    "id": "pf-1",
    "label": "Quicklink",
    "description": "Quicklink, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading.",
    "active": true,
    "slug": "quicklink",
    "tabs": "general",
    "package": "free"
  }
}
```

**Error Responses**

**404 Not Found**
```json
{
  "code": "not_found",
  "message": "Landmark not found",
  "data": {
    "status": 404
  }
}
```

**500 Internal Server Error**
```json
{
  "code": "invalid_data",
  "message": "Landmark data is not properly formatted",
  "data": {
    "status": 500
  }
}
```

#### Example

```bash
curl -X GET https://your-site.com/wp-json/pageflash/v1/landmark/1
```

```javascript
// Using fetch API
fetch('https://your-site.com/wp-json/pageflash/v1/landmark/1')
  .then(response => response.json())
  .then(data => console.log(data));
```

---

### 3. Update Landmark by ID

Update a specific landmark's configuration (requires authentication).

#### Request

```
PUT /wp-json/pageflash/v1/landmark/:id
```

#### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | The unique ID of the landmark (URL parameter) |
| `active` | boolean | No | Enable or disable the landmark (body parameter) |

#### Headers

```
Content-Type: application/json
X-WP-Nonce: {nonce_value}
```

#### Request Body

```json
{
  "active": true
}
```

#### Response

**Success (200 OK)**

```json
{
  "message": "Landmark updated successfully",
  "data": {
    "id": "pf-1",
    "label": "Quicklink",
    "description": "Quicklink, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading.",
    "active": true,
    "slug": "quicklink",
    "tabs": "general",
    "package": "free"
  }
}
```

**Error Responses**

**400 Bad Request**
```json
{
  "code": "missing_data",
  "message": "No valid update data provided",
  "data": {
    "status": 400
  }
}
```

**403 Forbidden**
```json
{
  "code": "invalid_nonce",
  "message": "Security check failed",
  "data": {
    "status": 403
  }
}
```

**404 Not Found**
```json
{
  "code": "not_found",
  "message": "Landmark not found",
  "data": {
    "status": 404
  }
}
```

**500 Internal Server Error**
```json
{
  "code": "invalid_data",
  "message": "Landmark data is not initialized",
  "data": {
    "status": 500
  }
}
```

#### Example

```bash
# Get nonce first (if using cURL)
curl -X PUT https://your-site.com/wp-json/pageflash/v1/landmark/1 \
  -H "Content-Type: application/json" \
  -H "X-WP-Nonce: abc123xyz" \
  --cookie "wordpress_logged_in_cookie=..." \
  -d '{"active": true}'
```

```javascript
// Using fetch API with WordPress nonce
const nonce = wpApiSettings.nonce; // WordPress provides this

fetch('https://your-site.com/wp-json/pageflash/v1/landmark/1', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': nonce
  },
  credentials: 'same-origin',
  body: JSON.stringify({
    active: true
  })
})
  .then(response => response.json())
  .then(data => console.log(data));
```

---

## Data Models

### Landmark Object

| Field | Type | Description |
|-------|------|-------------|
| `id` | string | Unique identifier for the landmark |
| `label` | string | Display name of the landmark |
| `description` | string | Detailed description of the landmark's functionality |
| `active` | boolean | Whether the landmark is currently active/enabled |
| `slug` | string | URL-friendly identifier for the landmark |
| `tabs` | string | (Optional) Tab category for UI organization |
| `package` | string | Package type: "free" or "premium" |

### Landmarks Collection

| Field | Type | Description |
|-------|------|-------------|
| `version` | string | API version |
| `author` | string | Plugin author |
| `url` | string | Plugin URL |
| `message` | string | Informational message |
| `data` | object | Object containing all landmark configurations keyed by slug |

---

## Error Handling

All endpoints return standard WordPress REST API error responses with the following structure:

```json
{
  "code": "error_code",
  "message": "Human-readable error message",
  "data": {
    "status": 400
  }
}
```

### Common Error Codes

| Code | Status | Description |
|------|--------|-------------|
| `not_found` | 404 | Landmark with specified ID not found |
| `invalid_data` | 500 | Landmark data is corrupted or not properly formatted |
| `invalid_nonce` | 403 | Security nonce verification failed |
| `missing_data` | 400 | Required data not provided in request |
| `rest_forbidden` | 403 | User lacks required permissions |

---

## Security & Permissions

### Public Endpoints
- `GET /landmark` - No authentication required
- `GET /landmark/:id` - No authentication required

### Protected Endpoints
- `PUT /landmark/:id` - Requires `manage_options` capability (typically Administrator role)

### Nonce Verification

For authenticated requests, the WordPress nonce must be:
1. Obtained from WordPress (e.g., `wpApiSettings.nonce`)
2. Included in the `X-WP-Nonce` header
3. Valid and not expired

---

## Rate Limiting

WordPress does not implement rate limiting by default. Consider using a security plugin or server-level rate limiting for production environments.

---

## Versioning

Current API version: **v1**

The API version is included in the endpoint path: `/wp-json/pageflash/v1/`

---

## Best Practices

1. **Always check response status codes** before processing data
2. **Cache GET responses** when appropriate to reduce server load
3. **Include error handling** for network failures and API errors
4. **Use proper authentication** for write operations
5. **Validate data** before sending PUT requests
6. **Handle nonce expiration** by refreshing when needed

---

## Integration Examples

### React Integration

```jsx
import { useState, useEffect } from 'react';

function LandmarkManager() {
  const [landmarks, setLandmarks] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchLandmarks();
  }, []);

  const fetchLandmarks = async () => {
    try {
      const response = await fetch('/wp-json/pageflash/v1/landmark');
      const data = await response.json();
      setLandmarks(data);
    } catch (error) {
      console.error('Error fetching landmarks:', error);
    } finally {
      setLoading(false);
    }
  };

  const toggleLandmark = async (id, currentStatus) => {
    try {
      const response = await fetch(`/wp-json/pageflash/v1/landmark/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': wpApiSettings.nonce
        },
        credentials: 'same-origin',
        body: JSON.stringify({ active: !currentStatus })
      });

      if (response.ok) {
        fetchLandmarks(); // Refresh data
      }
    } catch (error) {
      console.error('Error updating landmark:', error);
    }
  };

  if (loading) return <div>Loading...</div>;

  return (
    <div>
      {landmarks?.data && Object.values(landmarks.data).map(landmark => (
        <div key={landmark.id}>
          <h3>{landmark.label}</h3>
          <p>{landmark.description}</p>
          <button onClick={() => toggleLandmark(landmark.id, landmark.active)}>
            {landmark.active ? 'Disable' : 'Enable'}
          </button>
        </div>
      ))}
    </div>
  );
}
```

---

## Support

For issues or questions:
- **GitHub**: https://github.com/theaminuli/pageflash/
- **Documentation**: Check the plugin's README and dev-docs folder

---

## Changelog

### Version 1.0.0
- Initial API implementation
- GET /landmark - Retrieve all landmarks
- GET /landmark/:id - Retrieve landmark by ID
- PUT /landmark/:id - Update landmark by ID
