# PageFlash REST API Documentation

Quick reference for PageFlash REST API v1.

---

## Base URL

```
https://your-site.com/wp-json/pageflash/v1
```

**API Version:** v1  
**WordPress Required:** 6.0+  
**PHP Required:** 8.1+

---

## Quick Reference

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/landmark` | No | Get all landmarks |
| GET | `/landmark/:slug` | No | Get single landmark |
| PUT | `/landmark/:slug` | Yes | Update landmark |

---

## Authentication

**Public Endpoints:** GET requests (no auth)  
**Protected Endpoints:** PUT requests (requires `manage_options`)

```javascript
// Include nonce in PUT requests
headers: {
  'Content-Type': 'application/json',
  'X-WP-Nonce': wpApiSettings.nonce
}
```

**Application Passwords (External Clients):**
```bash
curl -u "username:app-password" \
  -X PUT https://site.com/wp-json/pageflash/v1/landmark/quicklink \
  -d '{"active": true}'
```

---

## GET /landmark

Get all landmarks with complete configuration.

**Request:**
```bash
curl https://your-site.com/wp-json/pageflash/v1/landmark
```

**Response (200):**
```json
{
  "version": "1.0.0",
  "author": "PageFlash Team",
  "data": {
    "quicklink": {
      "id": "pf-123",
      "type": "switch",
      "label": "Quicklink",
      "description": "Experience 50% increase in conversions...",
      "active": true,
      "slug": "quicklink",
      "menu": "preloading",
      "package": "free"
    },
    "heartbeat": {
      "id": "pf-128",
      "active": false,
      "slug": "heartbeat",
      "input": {
        "behavior": {
          "type": "select",
          "value": "disable_everywhere",
          "options": {
            "default": "Default Behavior",
            "disable_everywhere": "Disable Everywhere",
            "allow_posts": "Only Allow When Editing"
          }
        },
        "frequency": {
          "type": "select",
          "value": 60,
          "options": { "15": "15s", "30": "30s", "60": "60s" }
        }
      }
    }
  }
}
```

**JavaScript:**
```javascript
const { data } = await fetch('/wp-json/pageflash/v1/landmark')
  .then(r => r.json());

console.log(data.quicklink.active); // true/false
```

---

## GET /landmark/:slug

Get specific landmark by slug.

**Request:**
```bash
curl https://your-site.com/wp-json/pageflash/v1/landmark/quicklink
```

**Response (200):**
```json
{
  "message": "Landmark retrieved successfully",
  "data": {
    "id": "pf-123",
    "slug": "quicklink",
    "active": true,
    "label": "Quicklink"
  }
}
```

**Error (404):**
```json
{
  "code": "not_found",
  "message": "Landmark not found",
  "data": { "status": 404 }
}
```

---

## PUT /landmark/:slug

Update landmark (requires authentication).

**Simple Toggle:**
```javascript
await fetch('/wp-json/pageflash/v1/landmark/quicklink', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  },
  body: JSON.stringify({ active: true })
});
```

**Update Nested Properties:**
```javascript
await fetch('/wp-json/pageflash/v1/landmark/heartbeat', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  },
  body: JSON.stringify({
    active: true,
    input: {
      behavior: 'allow_posts',
      frequency: 120
    }
  })
});
```

**Response (200):**
```json
{
  "message": "Landmark updated successfully",
  "status": 200,
  "data": { "slug": "quicklink", "active": true }
}
```

**Errors:**
- `400` - Missing data
- `403` - Invalid nonce or permission denied
- `404` - Landmark not found
- `500` - Data corrupted

---

## Data Structures

```typescript
interface Landmark {
  id: string;
  type: "switch" | "select" | "text";
  label: string;
  description: string;
  active: boolean;
  slug: string;
  menu: "general" | "preloading";
  package: "free" | "premium";
  input?: {
    [key: string]: {
      type: string;
      value: any;
      default: any;
      options?: object;
    };
  };
}

interface LandmarksResponse {
  version: string;
  author: string;
  url: string;
  message: string;
  data: { [slug: string]: Landmark };
}
```

---

## Available Landmarks

| Slug | Menu | Nested Input |
|------|------|--------------|
| `quicklink` | preloading | No |
| `instantpage` | preloading | No |
| `dashicons` | general | No |
| `embeds` | general | No |
| `emojis` | general | No |
| `heartbeat` | general | Yes |

---

## Error Codes

| Code | Status | Meaning | Solution |
|------|--------|---------|----------|
| `not_found` | 404 | Landmark doesn't exist | Check slug |
| `invalid_nonce` | 403 | Security failed | Refresh nonce |
| `missing_data` | 400 | No data provided | Include update fields |
| `invalid_data` | 500 | Data corrupted | Reinstall plugin |

---

## Examples

### React Toggle Component

```jsx
import { useState } from 'react';
import apiFetch from '@wordpress/api-fetch';

function Toggle({ slug, initialActive, label }) {
  const [active, setActive] = useState(initialActive);

  const handleToggle = async () => {
    try {
      const res = await apiFetch({
        path: `/pageflash/v1/landmark/${slug}`,
        method: 'PUT',
        data: { active: !active }
      });
      setActive(res.data.active);
    } catch (error) {
      console.error('Update failed:', error);
    }
  };

  return (
    <label>
      <input type="checkbox" checked={active} onChange={handleToggle} />
      {label}
    </label>
  );
}
```

### Filter by Menu

```javascript
async function getLandmarksByMenu(menu) {
  const { data } = await fetch('/wp-json/pageflash/v1/landmark')
    .then(r => r.json());
  
  return Object.values(data).filter(l => l.menu === menu);
}

const general = await getLandmarksByMenu('general');
```

### Bulk Update

```javascript
async function bulkUpdate(updates) {
  const results = await Promise.allSettled(
    updates.map(({ slug, data }) =>
      fetch(`/wp-json/pageflash/v1/landmark/${slug}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': wpApiSettings.nonce
        },
        body: JSON.stringify(data)
      })
    )
  );

  const success = results.filter(r => r.status === 'fulfilled').length;
  console.log(`Updated ${success}/${updates.length} landmarks`);
}

await bulkUpdate([
  { slug: 'quicklink', data: { active: true } },
  { slug: 'dashicons', data: { active: false } }
]);
```

---

## Testing

### Manual Testing

```bash
# GET all landmarks
curl https://site.local/wp-json/pageflash/v1/landmark | jq

# GET specific landmark
curl https://site.local/wp-json/pageflash/v1/landmark/quicklink | jq

# PUT update (with auth)
curl -X PUT https://site.local/wp-json/pageflash/v1/landmark/quicklink \
  -H "Content-Type: application/json" \
  -H "X-WP-Nonce: your-nonce" \
  --cookie "wordpress_logged_in_=..." \
  -d '{"active": true}' | jq
```

### Browser Console

```javascript
// In WordPress admin, open console

// Test GET
fetch('/wp-json/pageflash/v1/landmark')
  .then(r => r.json())
  .then(console.log);

// Test PUT
fetch('/wp-json/pageflash/v1/landmark/quicklink', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  },
  body: JSON.stringify({ active: true })
})
  .then(r => r.json())
  .then(console.log);
```

## Best Practices

### Caching

```javascript
const cache = new Map();
const CACHE_TTL = 5 * 60 * 1000; // 5 min

async function getCached() {
  const cached = cache.get('landmarks');
  if (cached && Date.now() - cached.time < CACHE_TTL) {
    return cached.data;
  }

  const res = await fetch('/wp-json/pageflash/v1/landmark');
  const data = await res.json();
  
  cache.set('landmarks', { data, time: Date.now() });
  return data;
}
```

### Optimistic Updates

```javascript
async function optimisticToggle(slug, currentActive) {
  setActive(!currentActive); // Update UI first

  try {
    await updateLandmark(slug, { active: !currentActive });
  } catch (error) {
    setActive(currentActive); // Revert on error
    showError('Failed to update');
  }
}
```

### Nonce Refresh

```javascript
async function apiRequest(path, options = {}) {
  try {
    return await apiFetch({ path, ...options });
  } catch (error) {
    if (error.code === 'invalid_nonce') {
      window.location.reload(); // Refresh to get new nonce
    }
    throw error;
  }
}
```

---

## Support

**GitHub:** https://github.com/theaminuli/pageflash/  
**Issues:** https://github.com/theaminuli/pageflash/issues

**Related Files:**
- `includes/Landmark/LandmarkAPI.php` - API endpoints
- `includes/Landmark/LandmarkList.php` - Landmark registration
- `src/admin/hooks/useGetLandmark.js` - Fetch hook
- `src/admin/hooks/usePutLandmarkSlug.js` - Update hook

---

## Changelog

**v1.0.0** (Current)
- GET /landmark - Retrieve all landmarks
- GET /landmark/:slug - Retrieve by slug
- PUT /landmark/:slug - Update with deep merge support
- Nonce security & permission checks

---

*Last Updated: December 2, 2025*
