# PageFlash Architecture

Performance optimization WordPress plugin with React admin dashboard.

---

## Quick Overview

```
WordPress Core
    ↓
PHP Backend (Namespaced) ←→ REST API (pageflash/v1)
    ↓                            ↓
Landmark System              React Admin
    ↓
Feature Modules (Quicklink, NoReload, etc.)
```

**Stack:** WordPress 6.0+ | PHP 8.1+ | React 18 | REST API | Webpack

---

## Directory Structure

```
pageflash/
├── includes/              # PHP backend (namespaced)
│   ├── Admin/            # WP admin integration
│   ├── AssetsManager/    # Asset loading
│   ├── Landmark/         # Feature system (core)
│   │   ├── LandmarkAPI.php      # REST endpoints
│   │   ├── LandmarkList.php     # Registry
│   │   └── NoReload/            # Quicklink integration
│   └── Helper/           # Utilities
│
├── src/                   # Source (pre-build)
│   ├── admin/            # React dashboard
│   │   ├── components/   # UI components
│   │   ├── hooks/        # Custom hooks (useGetLandmarks, usePutLandmarkSlug)
│   │   ├── context/      # State management
│   │   └── reducers/     # State reducers
│   ├── quicklink/        # Frontend scripts
│   └── scss/             # Styles
│
├── build/                 # Compiled assets
│   ├── admin/            # React bundle
│   └── quicklink/        # Frontend bundle
│
├── assets/                # Static files
├── dev-docs/              # Documentation
├── pageflash.php          # Entry point
├── plugin.php             # Main class
└── autoload.php           # PSR-4 autoloader
```

---

## PHP Architecture

### Namespace Structure

```
PageFlash\
├── Plugin                 # Singleton entry point
├── Admin\Admin            # Admin init
├── AssetsManager          # Scripts/styles
├── Landmark\
│   ├── Landmark           # Feature orchestrator
│   ├── LandmarkList       # Registry (wp_options)
│   ├── LandmarkAPI        # REST endpoints
│   └── NoReload\Quicklink # Feature modules
└── Helper                 # Utilities
```

### Key Classes

| Class | Purpose |
|-------|---------|
| `Plugin` | Singleton orchestrator, initializes all modules |
| `AssetsManager` | Enqueues scripts/styles conditionally |
| `Admin` | Registers menu, action links |
| `Landmark` | Reads active features, instantiates modules |
| `LandmarkList` | Defines default features, stores in wp_options |
| `LandmarkAPI` | Handles GET/PUT `/landmark` endpoints |

### Landmark System

**Data Flow:**
```
LandmarkList → wp_options (pageflash_landmarks)
    ↓
Landmark reads active features
    ↓
Instantiates feature classes (Quicklink, etc.)
```

**Data Structure:**
```json
{
  "version": "1.0.0",
  "data": {
    "quicklink": {
      "id": "pf-1",
      "label": "Quicklink",
      "active": true,
      "slug": "quicklink",
      "menu": "preloading",
      "input": {
        "behavior": { "value": "disable_everywhere" }
      }
    }
  }
}
```

---

## Frontend Architecture

### React Stack

- **Router:** React Router v7 (HashRouter)
- **State:** Context API + Reducers
- **API:** `@wordpress/api-fetch`
- **Notifications:** React Toastify
- **Build:** Webpack (@wordpress/scripts)

### Component Tree

```
<AdminProvider>
  <HashRouter>
    <Routes>
      <Route element={<WithHeaderLayout />}>
        <Route path="/" element={<Welcome />} />
        <Route path="/addons" element={<Addons />} />
        <Route path="/settings" element={<Settings />} />
      </Route>
    </Routes>
  </HashRouter>
  <ToastContainer />
</AdminProvider>
```

### State Management

**Pattern:** Context + Reducer

```javascript
// Provider wraps app
<AdminProvider>
  {children}
</AdminProvider>

// Hook to access state
const { landmarks, dispatch } = usePageflashContext();

// Update state
dispatch({ type: 'UPDATE_LANDMARK', payload: data });
```

### API Communication

```javascript
import apiFetch from '@wordpress/api-fetch';

// GET landmarks
const res = await apiFetch({ path: '/pageflash/v1/landmark' });

// PUT update
await apiFetch({
  path: `/pageflash/v1/landmark/${slug}`,
  method: 'PUT',
  data: { active: true }
});
```

---

## REST API

**Base:** `/wp-json/pageflash/v1`

| Endpoint | Method | Auth | Purpose |
|----------|--------|------|---------|
| `/landmark` | GET | No | Get all landmarks |
| `/landmark/:slug` | GET | No | Get single landmark |
| `/landmark/:slug` | PUT | Yes | Update landmark |

**Auth:** PUT requires `manage_options` + `X-WP-Nonce` header

**Response:**
```json
{
  "message": "Landmark updated successfully",
  "data": { "slug": "quicklink", "active": true }
}
```

---

## Build System

### Webpack Config

**Entry Points:**
```javascript
{
  'admin/admin': './src/admin/index.jsx',
  'quicklink/quicklink': './src/quicklink/index.js'
}
```

**Output:**
```
src/admin/index.jsx  → build/admin/admin.js
src/scss/admin.scss  → build/admin/admin.css
```

### Commands

```bash
npm start          # Dev build + watch
npm run build      # Production build
npm run lint:js    # ESLint
npm run lint:wpcs  # PHP CodeSniffer
npm run plugin-zip # Create release ZIP
```

---

## Plugin Initialization

```
pageflash.php (Entry)
    ↓
Plugin::instance() (Singleton)
    ↓
┌─────────────┬────────────┬───────────────┐
│ Autoloader  │  Assets    │  Admin (is_admin)  │
└─────────────┴────────────┴───────────────┘
    ↓
Landmark::__construct()
    ↓
┌──────────────┬───────────────┬─────────────┐
│ LandmarkList │  LandmarkAPI  │  Read Active │
└──────────────┴───────────────┴─────────────┘
    ↓
Instantiate Active Features (Quicklink, NoReload, etc.)
```

---

## Design Patterns

| Pattern | Where | Why |
|---------|-------|-----|
| **Singleton** | `Plugin` | Single instance |
| **Registry** | `LandmarkList` | Central feature storage |
| **Context Provider** | React state | Avoid prop drilling |
| **Dependency Injection** | Constructors | Loose coupling |

---

## Asset Loading

### Admin Assets

```php
// Only in admin
if (is_admin()) {
  wp_enqueue_script('pageflash-admin', 
    PAGEFLASH_BUILD_URL . 'admin/admin.js',
    ['wp-element', 'wp-i18n'],
    PAGEFLASH_VERSION
  );
}
```

### Frontend Assets

```php
// Conditional loading
if (landmark_is_active('quicklink')) {
  wp_enqueue_script('pageflash-quicklink',
    PAGEFLASH_BUILD_URL . 'quicklink/quicklink.js'
  );
}
```

---

## Adding New Features

### 1. Create Feature Class

```php
namespace PageFlash\Landmark\NewFeature;

class NewFeature {
  public function __construct() {
    // Initialize
  }
}
```

### 2. Register in LandmarkList

```php
'newfeature' => [
  'id' => wp_unique_id('pf-'),
  'label' => __('New Feature', 'pageflash'),
  'active' => false,
  'slug' => 'newfeature'
]
```

### 3. Add to Landmark Switch

```php
case 'newfeature':
  new NewFeature();
  break;
```

---

## Performance

**PHP:**
- Autoloader for efficient class loading
- Conditional loading (admin/frontend)
- Lazy feature initialization
- Minimal DB queries (wp_options reads)

**Frontend:**
- Code splitting (admin/frontend bundles)
- Minified production builds
- Browser caching via versioned assets
- Conditional script loading

---

## Constants

```php
PAGEFLASH_VERSION       # Plugin version
PAGEFLASH_PATH          # /wp-content/plugins/pageflash/
PAGEFLASH_URL           # https://site.com/wp-content/plugins/pageflash/
PAGEFLASH_BUILD_URL     # {URL}/build/
PAGEFLASH_ASSETS_URL    # {URL}/assets/
```

---

## Hooks & Filters

```php
// Modify landmarks
apply_filters('pageflash_landmarks', $defaults);

// Before init
do_action('pageflash_before_landmark_init');

// After init
do_action('pageflash_after_landmark_init');
```

---

## Resources

- **WordPress:** https://developer.wordpress.org/plugins/
- **REST API:** https://developer.wordpress.org/rest-api/
- **React:** https://react.dev/
- **@wordpress/scripts:** https://developer.wordpress.org/block-editor/packages/packages-scripts/

---

*Version: 1.0.0*