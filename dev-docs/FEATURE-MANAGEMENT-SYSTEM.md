# PageFlash Feature Management System

**Version:** 1.3.0  
**Last Updated:** 2024

## Overview

This document describes the new OAuth-based Feature Management System for PageFlash. This architecture provides centralized feature registration, license validation, and automatic feature loading with support for Pro and Agency tiers.

## Architecture

### Core Components

1. **LicenseManager.php** - OAuth token-based license validation
2. **Boot.php** - Central feature registration system
3. **BootManager.php** - Abstract base class for all feature managers
4. **Advanced/Advanced.php** - Manager for Pro/Agency features
5. **NoReload/NoReload.php** - Manager for preloading features (refactored)
6. **General/General.php** - Manager for general optimization features (refactored)

### File Structure

```
includes/Landmark/
├── LicenseManager.php          # OAuth license management (470 lines)
├── Boot.php         # Central registration (140 lines)
├── BootManager.php          # Abstract base class (250 lines)
├── Landmark.php                # Main orchestrator (updated)
├── LandmarkAPI.php             # REST API with license checks (updated)
├── NoReload/
│   ├── NoReload.php           # Preloading manager (20 lines, refactored)
│   ├── Quicklink.php
│   └── InstantPage.php
├── General/
│   ├── General.php            # General manager (70 lines, refactored)
│   ├── DisableEmojis.php
│   ├── DisableEmbeds.php
│   ├── DisableDashicons.php
│   ├── RemoveJQueryMigrate.php
│   ├── DisableXMLRPC.php
│   ├── HideWPVersion.php
│   ├── DisableRestAPI.php
│   └── DisableHeartbeat.php
└── Advanced/
    └── Advanced.php           # Pro features manager (30 lines, new)
```

## License Tiers

| Tier | Features | License Required |
|------|----------|------------------|
| **Free** | All current features (quicklink, instantpage, emojis, embeds, dashicons, etc.) | ❌ No |
| **Pro** | Advanced optimization features (Critical CSS, etc.) | ✅ Yes |
| **Agency** | All features + white-label | ✅ Yes |

## How It Works

### 1. Feature Registration (Landmark.php)

All features are registered centrally in `Landmark::register_features()`:

```php
Boot::register('quicklink', [
    'class'     => Quicklink::class,
    'namespace' => 'noreload',
    'package'   => 'free',      // ← License tier
    'priority'  => 10,
]);

Boot::register('critical_css', [
    'class'     => CriticalCSS::class,
    'namespace' => 'advanced',
    'package'   => 'pro',       // ← Requires Pro license
    'requires'  => ['emojis'],  // ← Optional dependency
    'priority'  => 20,
]);
```

### 2. Feature Loading (BootManager.php)

Each manager extends `BootManager` and automatically:

1. Reads settings from `Helper::get_settings()` (pageflash_landmarks)
2. Filters features by namespace
3. Checks license for Pro/Agency features
4. Instantiates enabled features with constructor parameters

```php
class Advanced extends BootManager {
    protected $namespace = 'advanced';
    
    // Features automatically loaded by parent class
}
```

### 3. OAuth License Validation (LicenseManager.php)

The license system uses OAuth-style tokens:

- **Access Token** - Short-lived (1 hour), used for feature checks
- **Refresh Token** - Long-lived (30 days), used to get new access tokens

```php
// Activate license
$result = LicenseManager::activate_license('LICENSE-KEY-HERE');

// Check if user can use a feature
if (LicenseManager::can_use_feature('pro')) {
    // Load Pro feature
}

// Automatic token refresh
LicenseManager::refresh_access_token();
```

### 4. REST API License Checks (LandmarkAPI.php)

When updating features via API:

```php
PUT /pageflash/v1/landmark/critical_css
{
    "active": true  // Trying to enable Pro feature
}
```

The API automatically:

1. Checks if feature is registered in `Boot`
2. Gets package tier (free/pro/agency)
3. Validates license using `LicenseManager::can_use_feature()`
4. Returns 403 error if license invalid

## REST API Endpoints

### Landmark Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/pageflash/v1/landmark` | Get all landmarks |
| GET | `/pageflash/v1/landmark/:slug` | Get landmark by slug |
| PUT | `/pageflash/v1/landmark/:slug` | Update landmark (with license check) |

### License Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/pageflash/v1/license/status` | Get license status |
| POST | `/pageflash/v1/license/activate` | Activate license key |
| POST | `/pageflash/v1/license/deactivate` | Deactivate license |
| POST | `/pageflash/v1/license/refresh` | Refresh access token |

## Field Types

The system supports three field types:

### 1. Switch Field (Boolean)

```php
$settings = [
    'emojis' => [
        'active' => true  // On/Off toggle
    ]
];
```

### 2. Value Field (String/Number)

```php
$settings = [
    'quicklink' => [
        'input' => [
            'value' => 'viewport'  // Select, Input, or Textarea
        ]
    ]
];
```

### 3. Nested Field (Multiple Values)

```php
$settings = [
    'heartbeat' => [
        'input' => [
            'behavior' => [
                'value' => 'modify'  // Select field
            ],
            'frequency' => [
                'value' => '60'  // Input field
            ]
        ]
    ]
];
```

## Adding a New Feature

### Step 1: Create Feature Class

```php
// includes/Landmark/Advanced/CriticalCSS.php
namespace TheAminul\PageFlash\Landmark\Advanced;

class CriticalCSS {
    public function __construct() {
        add_action('wp_head', [$this, 'inject_critical_css'], 1);
    }
    
    public function inject_critical_css() {
        // Implementation
    }
}
```

### Step 2: Register in Landmark.php

```php
use TheAminul\PageFlash\Landmark\Advanced\CriticalCSS;

// In register_features() method:
Boot::register('critical_css', [
    'class'     => CriticalCSS::class,
    'namespace' => 'advanced',
    'package'   => 'pro',  // ← Requires Pro license
    'priority'  => 20,
]);
```

### Step 3: Add to LandmarkList.php

```php
[
    'slug' => 'critical_css',
    'label' => __('Critical CSS', 'pageflash'),
    'badge' => 'pro',  // Shows Pro badge in UI
    'active' => false,
],
```

That's it! The system automatically:
- Loads the feature when enabled
- Validates license before activation
- Blocks API updates without valid license

## License Validation Flow

```
User Activates License
    ↓
POST /license/activate { license_key }
    ↓
LicenseManager contacts license server
    ↓
Server returns:
- access_token (1 hour)
- refresh_token (30 days)
- license_data (tier, expiry, site_url)
    ↓
Tokens stored in wp_options
    ↓
User tries to enable Pro feature
    ↓
PUT /landmark/critical_css { active: true }
    ↓
API checks Boot → package: 'pro'
    ↓
LicenseManager::can_use_feature('pro')
    ↓
Validates access_token
    ↓
If expired → refresh_access_token()
    ↓
If valid → Feature enabled ✅
If invalid → 403 Error ❌
```

## Migration from Old System

### Before (Hardcoded)

```php
class General {
    public function init_features() {
        $settings = get_option('pageflash_options'); // ← WRONG
        
        if (isset($settings['emojis']['active']) && $settings['emojis']['active']) {
            new DisableEmojis();
        }
        
        if (isset($settings['embeds']['active']) && $settings['embeds']['active']) {
            new DisableEmbeds();
        }
        // ... 8 more hardcoded if statements
    }
}
```

### After (Dynamic)

```php
class General extends BootManager {
    protected $namespace = 'general';
    
    // Features automatically loaded by parent class
    // No hardcoded if statements needed
}
```

**Result:**
- 140 lines → 70 lines
- 60 lines → 20 lines (NoReload.php)
- All features centrally registered
- License validation automatic
- Bug fixed: General.php now uses correct option

## Configuration

### License Server (Optional)

Set license server URL in wp-config.php:

```php
define('PAGEFLASH_LICENSE_SERVER', 'https://yourdomain.com');
```

Default: `https://pageflash.aminul.store`

### Agency License (wp-config.php)

For agency deployments, set license in wp-config.php:

```php
define('PAGEFLASH_LICENSE_KEY', 'AGENCY-LICENSE-KEY');
```

This bypasses the UI license activation and uses a fixed license key.

### Grace Period

If license server is unreachable, features continue working for 24 hours. After that, they're disabled until server is reachable again.

## API Examples

### Activate License

```javascript
fetch('/wp-json/pageflash/v1/license/activate', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': wpApiSettings.nonce
    },
    body: JSON.stringify({
        license_key: 'YOUR-LICENSE-KEY'
    })
});
```

### Check License Status

```javascript
fetch('/wp-json/pageflash/v1/license/status', {
    headers: {
        'X-WP-Nonce': wpApiSettings.nonce
    }
})
.then(res => res.json())
.then(data => {
    console.log('License Status:', data.status);
    console.log('Tier:', data.license_data?.tier);
});
```

### Enable Pro Feature (Requires License)

```javascript
fetch('/wp-json/pageflash/v1/landmark/critical_css', {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': wpApiSettings.nonce
    },
    body: JSON.stringify({
        active: true
    })
});

// Response (without license):
// {
//   "code": "license_required",
//   "message": "This feature requires an active Pro license.",
//   "data": { "status": 403 }
// }
```

## Benefits

✅ **Centralized Management** - All features registered in one place  
✅ **License Validation** - Real-time OAuth token checks  
✅ **No Code Duplication** - Abstract base class eliminates repeated code  
✅ **Automatic Loading** - Features load dynamically based on settings  
✅ **Type Safe** - Handles switch, value, and nested fields  
✅ **Extensible** - Easy to add Pro features  
✅ **Secure** - License checks in API prevent unauthorized access  
✅ **Bug Fixed** - General.php now reads correct option (pageflash_landmarks)

## Testing

### Test License Activation

```php
// Test activation
$result = LicenseManager::activate_license('test-license-key');
if (is_wp_error($result)) {
    echo $result->get_error_message();
} else {
    echo 'License activated!';
}

// Check license data
$data = LicenseManager::get_license_data();
print_r($data);
```

### Test Feature Loading

```php
// Check if Pro feature can be used
if (LicenseManager::can_use_feature('pro')) {
    echo 'Pro features available';
} else {
    echo 'Pro license required';
}

// Get all Pro features
$pro_features = Boot::get_features(null, 'pro');
foreach ($pro_features as $slug => $config) {
    echo $slug . ' (' . $config['class'] . ')';
}
```

## Troubleshooting

### Features Not Loading

1. Check if feature is registered in `Landmark::register_features()`
2. Verify settings in `pageflash_landmarks` option
3. Check license status: `GET /pageflash/v1/license/status`

### License Validation Failing

1. Check access token expiry: `get_option('pageflash_access_token_expires')`
2. Try refreshing token: `POST /pageflash/v1/license/refresh`
3. Check license server connectivity
4. Review error logs for API responses

### General Features Not Working (Bug Fixed)

**Problem:** General.php was using `get_option('pageflash_options')` which doesn't exist.  
**Solution:** Now extends `BootManager` which uses `Helper::get_settings()` (reads `pageflash_landmarks`).

## Future Enhancements

- [ ] Add React component for license activation UI
- [ ] Create admin notice for license expiration
- [ ] Add telemetry for feature usage (opt-in)
- [ ] Create migration script for existing users
- [ ] Add unit tests for LicenseManager
- [ ] Document license server API endpoints
- [ ] Add webhook support for license updates

## Support

For questions about the Feature Management System, please:

1. Check this documentation
2. Review the code comments in core files
3. Test with the provided API examples
4. Contact support with specific error messages

---

**Created:** PageFlash v1.3.0  
**Author:** The PageFlash Team  
**License:** Proprietary
