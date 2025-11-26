# PageFlash Project Architecture

## Overview

PageFlash is a WordPress plugin designed to optimize website performance through intelligent prefetching and page loading strategies. The plugin combines modern React-based admin interfaces with robust PHP backend architecture to deliver a seamless performance optimization experience.

**Version:** 1.2.0  
**Minimum Requirements:**
- WordPress: 6.0+
- PHP: 7.4+
- Node.js: 20.10.0+
- npm: 10.2.3+

---

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Directory Structure](#directory-structure)
3. [PHP Backend Architecture](#php-backend-architecture)
4. [Frontend Architecture](#frontend-architecture)
5. [Build System](#build-system)
6. [Data Flow](#data-flow)
7. [API Architecture](#api-architecture)
8. [Asset Management](#asset-management)
9. [Plugin Initialization Flow](#plugin-initialization-flow)
10. [Key Design Patterns](#key-design-patterns)
11. [Performance Considerations](#performance-considerations)
12. [Security Architecture](#security-architecture)

---

## Architecture Overview

PageFlash follows a modular, component-based architecture with clear separation of concerns:

```
┌─────────────────────────────────────────────────────────────┐
│                    WordPress Core                            │
└───────────────────────┬─────────────────────────────────────┘
                        │
        ┌───────────────┴────────────────┐
        │                                │
┌───────▼────────┐              ┌───────▼────────┐
│  PHP Backend   │◄────────────►│ REST API Layer │
│  (Namespaced)  │              │  (pageflash/v1)│
└───────┬────────┘              └───────┬────────┘
        │                                │
        │  ┌─────────────────────────────┘
        │  │
┌───────▼──▼─────┐
│ Admin Frontend │
│  (React/JSX)   │
└────────────────┘
```

**Core Principles:**
- **Namespace Isolation:** All PHP classes use `PageFlash` namespace
- **Singleton Pattern:** Main plugin class uses singleton pattern
- **Component Modularity:** Each feature is self-contained
- **Modern Frontend:** React-based admin dashboard with routing
- **RESTful API:** WordPress REST API for frontend-backend communication

---

## Directory Structure

```
pageflash/
├── assets/                      # Static assets (images, logos, libraries)
│   ├── libs/                   # Third-party JavaScript libraries
│   │   └── quicklink/          # Quicklink library files
│   └── logo/                   # Plugin branding assets
│
├── build/                       # Compiled/bundled production assets
│   ├── admin/                  # Compiled React admin dashboard
│   └── quicklink/              # Compiled quicklink scripts
│
├── dev-docs/                    # Developer documentation
│   ├── API-DOCUMENTATION.md    # REST API documentation
│   └── ARCHITECTURE.md         # This file
│
├── includes/                    # PHP backend source code
│   ├── Admin/                  # WordPress admin integration
│   │   ├── Admin.php           # Admin initialization
│   │   ├── AdminMenu.php       # Admin menu registration
│   │   └── ActionLinks.php     # Plugin action links
│   │
│   ├── AssetsManager/          # Asset loading and management
│   │   └── AssetsManager.php   # Scripts and styles enqueuing
│   │
│   ├── Compatibility/          # Third-party compatibility layer
│   │   └── Compatibility.php   # Compatibility checks
│   │
│   ├── Helper/                 # Utility functions and helpers
│   │   └── Helper.php          # Common helper methods
│   │
│   └── Landmark/               # Core feature modules
│       ├── Landmark.php        # Main landmark orchestrator
│       ├── LandmarkList.php    # Default landmarks registration
│       ├── LandmarkAPI.php     # REST API endpoints
│       │
│       ├── JavaScript/         # JavaScript integration
│       │   └── JavaScript.php  # JS enqueue handler
│       │
│       └── NoReload/           # NoReload feature
│           ├── NoReload.php    # NoReload implementation
│           └── Quicklink.php   # Quicklink integration
│
├── src/                         # Frontend source code (pre-build)
│   ├── admin/                  # React admin dashboard
│   │   ├── components/         # React components
│   │   │   ├── addons/         # Addons management UI
│   │   │   ├── header/         # Dashboard header
│   │   │   ├── layout/         # Layout components
│   │   │   └── settings/       # Settings UI
│   │   │
│   │   ├── context/            # React Context providers
│   │   ├── hooks/              # Custom React hooks
│   │   ├── reducers/           # State reducers
│   │   ├── actions/            # Redux-style actions
│   │   ├── utils/              # Utility functions
│   │   ├── constants/          # Constants and config
│   │   ├── provider/           # Context providers
│   │   ├── AdminDashboard.jsx  # Main dashboard component
│   │   ├── app.jsx             # App wrapper
│   │   └── index.jsx           # Entry point
│   │
│   ├── quicklink/              # Quicklink feature scripts
│   │   └── index.js            # Quicklink initialization
│   │
│   └── scss/                   # Styles (SASS/SCSS)
│       ├── admin.scss          # Admin dashboard styles
│       └── admin/              # Admin style partials
│           ├── _header.scss
│           ├── _welcome.scss
│           ├── _addons.scss
│           └── _settings.scss
│
├── vendor/                      # Composer dependencies
├── node_modules/               # npm dependencies
│
├── pageflash.php               # Main plugin file (entry point)
├── plugin.php                  # Plugin class and initialization
├── autoload.php                # Custom class autoloader
├── composer.json               # PHP dependencies
├── package.json                # Node.js dependencies
├── webpack.config.js           # Webpack build configuration
├── phpcs.xml                   # PHP CodeSniffer rules
└── README.md                   # Project documentation
```

---

## PHP Backend Architecture

### Namespace Structure

All PHP classes follow the `PageFlash\{Module}\{Class}` namespace pattern:

```php
PageFlash\
├── Plugin                      # Main plugin singleton
├── Admin\
│   ├── Admin                   # Admin initialization
│   ├── AdminMenu               # Menu registration
│   └── ActionLinks             # Plugin links
├── AssetsManager\
│   └── AssetsManager           # Asset management
├── Helper\
│   └── Helper                  # Utility functions
└── Landmark\
    ├── Landmark                # Feature orchestrator
    ├── LandmarkList            # Feature registry
    ├── LandmarkAPI             # REST API handler
    ├── JavaScript\
    │   └── JavaScript          # JS integration
    └── NoReload\
        ├── NoReload            # NoReload feature
        └── Quicklink           # Quicklink feature
```

### Class Responsibilities

#### 1. **Plugin Class** (`plugin.php`)

**Responsibility:** Main plugin orchestrator and singleton instance manager

**Key Methods:**
- `instance()`: Ensures single instance (Singleton pattern)
- `register_autoload()`: Loads autoloader (Composer or custom)
- `init_assets()`: Initializes asset manager
- `init_admin()`: Initializes admin components
- `init_landmark()`: Initializes feature modules
- `init()`: Coordinates all initialization

**Initialization Order:**
```
1. Autoloader registration
2. Assets Manager initialization
3. Admin components (admin context only)
4. Landmark features
```

#### 2. **AssetsManager** (`includes/AssetsManager/AssetsManager.php`)

**Responsibility:** Manages all CSS and JavaScript asset loading

**Key Methods:**
- `__construct()`: Hooks into WordPress enqueue actions
- Enqueues admin and frontend scripts
- Handles script dependencies and versioning
- Manages style compilation and loading

**Asset Loading Strategy:**
```php
// Admin assets loaded only in admin context
if (is_admin()) {
    wp_enqueue_script('pageflash-admin');
    wp_enqueue_style('pageflash-admin');
}

// Frontend assets loaded conditionally
if (landmark_is_active('quicklink')) {
    wp_enqueue_script('pageflash-quicklink');
}
```

#### 3. **Admin** (`includes/Admin/`)

**Responsibility:** WordPress admin integration

**Components:**
- `Admin.php`: Main admin coordinator
- `AdminMenu.php`: Registers admin menu pages
- `ActionLinks.php`: Adds plugin action links (Settings, etc.)

**Admin Menu Structure:**
```
PageFlash (Top Level)
└── Dashboard (Main page - React app loads here)
```

#### 4. **Landmark System** (`includes/Landmark/`)

**Responsibility:** Core feature/module system

**Architecture:**
```
LandmarkList (Registry)
    │
    ├── Defines default landmarks
    ├── Stores in wp_options (pageflash_landmarks)
    │
    ▼
Landmark (Orchestrator)
    │
    ├── Reads active landmarks
    ├── Instantiates feature classes
    │
    ▼
Feature Classes (NoReload, Quicklink, etc.)
    │
    └── Implement specific functionality
```

**Landmark Data Structure:**
```php
[
    'version' => '1.0.0',
    'data' => [
        'quicklink' => [
            'id' => 'pf-1',
            'label' => 'Quicklink',
            'description' => '...',
            'active' => true,
            'slug' => 'quicklink',
            'package' => 'free'
        ],
        // ... other landmarks
    ]
]
```

#### 5. **LandmarkAPI** (`includes/Landmark/LandmarkAPI.php`)

**Responsibility:** REST API endpoint handler

**Endpoints:**
```
GET    /wp-json/pageflash/v1/landmark       # Get all landmarks
GET    /wp-json/pageflash/v1/landmark/:id   # Get landmark by ID
PUT    /wp-json/pageflash/v1/landmark/:id   # Update landmark
```

**Security:**
- GET requests: Public (`__return_true`)
- PUT requests: Requires `manage_options` capability
- Nonce verification for authenticated requests

---

## Frontend Architecture

### Technology Stack

- **Framework:** React 18+
- **Routing:** React Router v7
- **State Management:** React Context API + Reducers
- **UI Notifications:** React Toastify
- **Icons:** React Icons, WordPress Icons
- **Styling:** SASS/SCSS
- **Build Tool:** Webpack (via @wordpress/scripts)

### Component Architecture

```
AdminDashboard (Root)
    │
    ├── HashRouter
    │   └── Routes
    │       ├── WithHeaderLayout (Wrapper)
    │       │   ├── Header
    │       │   └── Outlet (Nested routes render here)
    │       │
    │       ├── Route: / (Welcome)
    │       ├── Route: /addons (Addons Component)
    │       └── Route: /settings (Settings Component)
    │
    └── Providers
        ├── LandmarkProvider (Feature state)
        └── ToastContainer (Notifications)
```

### State Management Pattern

**Context + Reducer Pattern:**

```javascript
// Context Provider
<LandmarkProvider>
    <AdminDashboard />
</LandmarkProvider>

// Reducer pattern for state updates
const landmarkReducer = (state, action) => {
    switch (action.type) {
        case 'SET_LANDMARKS':
            return { ...state, landmarks: action.payload };
        case 'UPDATE_LANDMARK':
            // ... update logic
        default:
            return state;
    }
};
```

### Routing Structure

```javascript
<HashRouter>
    <Routes>
        <Route element={<WithHeaderLayout />}>
            <Route path="/" element={<Welcome />} />
            <Route path="/addons" element={<Addons />} />
            <Route path="/settings" element={<Settings />} />
        </Route>
    </Routes>
</HashRouter>
```

**Why HashRouter?**
- Works without server-side routing configuration
- Compatible with WordPress admin URL structure
- No .htaccess modifications required

### API Communication

**Fetch Pattern:**

```javascript
// Get landmarks
const response = await fetch('/wp-json/pageflash/v1/landmark');
const data = await response.json();

// Update landmark (authenticated)
const response = await fetch(`/wp-json/pageflash/v1/landmark/${id}`, {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': wpApiSettings.nonce
    },
    body: JSON.stringify({ active: true })
});
```

---

## Build System

### Webpack Configuration

**Entry Points:**
```javascript
{
    'admin/admin': './src/admin/index.jsx',
    'quicklink/quicklink': './src/quicklink/index.js'
}
```

**Build Process:**
```
src/admin/index.jsx  ──→  webpack  ──→  build/admin/admin.js
src/scss/admin.scss  ──→  sass     ──→  build/admin/admin.css
```

**Plugins:**
- `@wordpress/scripts`: WordPress-specific webpack config
- `webpack-remove-empty-scripts`: Removes empty JS from CSS-only entries
- SASS/SCSS compilation
- Asset extraction and optimization

### Build Commands

```bash
# Development build with watch
npm start

# Production build
npm run build

# Hot module replacement (HMR)
npm start:hot

# Linting
npm run lint:js          # JavaScript linting
npm run lint:css         # CSS linting
npm run lint:wpcs        # PHP WordPress Coding Standards
npm run lint:fix         # Auto-fix linting issues

# Create plugin ZIP
npm run plugin-zip
```

### SCSS Architecture

**Modern @use syntax (Sass Module System):**

```scss
// admin.scss
@use "./admin/header";
@use "./admin/welcome";
@use "./admin/addons";
@use "./admin/settings";

// Partials must be imported at the top (before any CSS rules)
```
---

## API Architecture

### REST API Design

**Base Namespace:** `pageflash/v1`

**Endpoint Pattern:**
```
/wp-json/pageflash/v1/{resource}/{id?}
```

**Authentication Strategy:**
- Public endpoints: `'permission_callback' => '__return_true'`
- Protected endpoints: `'permission_callback' => array($this, 'check_permissions')`

**Nonce Verification:**
```php
$nonce = $request->get_header('X-WP-Nonce');
if (!wp_verify_nonce($nonce, 'wp_rest')) {
    return new WP_Error('invalid_nonce', 'Security check failed', ['status' => 403]);
}
```

**Error Handling:**
```php
return new WP_Error(
    'error_code',
    __('Error message', 'pageflash'),
    ['status' => 404]
);
```

**Response Format:**
```php
return rest_ensure_response([
    'message' => __('Success message', 'pageflash'),
    'data' => $result
]);
```

### API Versioning Strategy

- Version in URL path: `/pageflash/v1/`
- Future versions: `/pageflash/v2/`
- Maintains backward compatibility
- No breaking changes in minor versions

---

## Asset Management

### Asset Loading Strategy

**Admin Assets:**
```php
// Only in admin context
if (is_admin()) {
    wp_enqueue_script(
        'pageflash-admin',
        PAGEFLASH_BUILD_URL . 'admin/admin.js',
        ['wp-element', 'wp-i18n'],
        PAGEFLASH_VERSION,
        true
    );
}
```

**Frontend Assets:**
```php
// Conditional loading based on active landmarks
if (landmark_is_active('quicklink')) {
    wp_enqueue_script(
        'pageflash-quicklink',
        PAGEFLASH_BUILD_URL . 'quicklink/quicklink.js',
        [],
        PAGEFLASH_VERSION,
        true
    );
}
```

**Localization:**
```php
wp_localize_script('pageflash-admin', 'pageflashData', [
    'apiUrl' => rest_url('pageflash/v1'),
    'nonce' => wp_create_nonce('wp_rest')
]);
```

### Asset Constants

```php
PAGEFLASH_URL           // Plugin root URL
PAGEFLASH_PATH          // Plugin root path
PAGEFLASH_ASSETS_URL    // /assets/ URL
PAGEFLASH_ASSETS_PATH   // /assets/ path
PAGEFLASH_BUILD_URL     // /build/ URL
PAGEFLASH_BUILD_PATH    // /build/ path
```

---

## Plugin Initialization Flow

### Detailed Initialization Sequence

```
1. WordPress loads plugin
   pageflash.php
       │
       ├── Version check (PHP 7.0+, WP 5.9+)
       ├── Define constants
       │   ├── PAGEFLASH_VERSION
       │   ├── PAGEFLASH_DIR
       │   ├── PAGEFLASH_PATH
       │   └── PAGEFLASH_URL
       │
       ▼
2. Load plugin.php
   Plugin::instance()
       │
       ▼
3. register_autoload()
   │
   ├── Check environment (development vs production)
   ├── Load Composer autoloader OR custom autoloader
   │
   ▼
4. init_assets()
   │
   └── new AssetsManager()
       │
       ├── Hook: admin_enqueue_scripts
       ├── Hook: wp_enqueue_scripts
       │
       ▼
5. init_admin() [if is_admin()]
   │
   └── new Admin()
       │
       ├── new AdminMenu()
       │   └── Registers admin pages
       │
       └── new ActionLinks()
           └── Adds plugin action links
       │
       ▼
6. init_landmark()
   │
   └── new Landmark()
       │
       ├── new LandmarkList()
       │   └── Registers default landmarks to wp_options
       │
       ├── new LandmarkAPI()
       │   └── Registers REST API routes
       │
       ├── Reads active landmarks from wp_options
       │
       └── Instantiates active feature classes
           ├── new Quicklink() [if active]
           ├── new NoReload() [if active]
           └── ... [other active landmarks]
```

---

## Key Design Patterns

### 1. Singleton Pattern

**Used in:** `Plugin` class

```php
private static $_instance = null;

public static function instance() {
    if (is_null(self::$_instance)) {
        self::$_instance = new self();
    }
    return self::$_instance;
}
```

**Benefits:**
- Single instance throughout plugin lifecycle
- Global access point
- Prevents multiple initializations

### 2. Registry Pattern

**Used in:** `LandmarkList`

```php
// Registers landmarks in a central registry (wp_options)
$landmarks = [
    'quicklink' => [...],
    'noreload' => [...],
];
update_option('pageflash_landmarks', $landmarks);
```

**Benefits:**
- Centralized feature registration
- Easy to add new landmarks
- Persistent storage

### 3. Factory Pattern

**Used in:** `Landmark` class

```php
switch ($slug) {
    case 'quicklink':
        new Quicklink();
        break;
    case 'noreload':
        new NoReload();
        break;
}
```

**Benefits:**
- Dynamic object creation
- Easy to extend with new features
- Conditional instantiation

### 4. Dependency Injection

**Used in:** Constructor injection throughout

```php
class Admin {
    public function __construct() {
        new AdminMenu();
        new ActionLinks();
    }
}
```

### 5. Context Provider Pattern (Frontend)

**Used in:** React state management

```javascript
<LandmarkProvider>
    <AdminDashboard />
</LandmarkProvider>
```

**Benefits:**
- Centralized state management
- Props drilling avoidance
- Separation of concerns

---

## Performance Considerations

### PHP Performance

1. **Autoloading:** Uses Composer autoloader in production for optimized class loading
2. **Conditional Loading:** Admin classes only load in admin context
3. **Lazy Initialization:** Features instantiate only when active
4. **Efficient Queries:** Minimal database queries (mostly option reads)

### Frontend Performance

1. **Code Splitting:** Separate bundles for admin and frontend
2. **Lazy Loading:** React components load on demand
3. **Minification:** Production builds are minified
4. **Caching:** Browser caching via versioned assets

### Asset Loading Optimization

```php
// Conditional asset loading
if (!is_admin() && landmark_is_active('quicklink')) {
    wp_enqueue_script('pageflash-quicklink');
}
```

**Benefits:**
- Reduces HTTP requests
- Smaller page payload
- Faster page loads

---

## Security Architecture

### Authentication & Authorization

**REST API Security:**
```php
// Public read access
'permission_callback' => '__return_true'

// Admin write access
'permission_callback' => array($this, 'check_permissions')

public function check_permissions() {
    return current_user_can('manage_options');
}
```

### Nonce Verification

```php
$nonce = $request->get_header('X-WP-Nonce');
if (!wp_verify_nonce($nonce, 'wp_rest')) {
    return new WP_Error('invalid_nonce', 'Security check failed');
}
```

### Input Validation & Sanitization

```php
'args' => [
    'id' => [
        'required' => true,
        'validate_callback' => function($param) {
            return is_numeric($param);
        },
        'sanitize_callback' => 'absint'
    ],
    'active' => [
        'validate_callback' => function($param) {
            return is_bool($param);
        },
        'sanitize_callback' => 'rest_sanitize_boolean'
    ]
]
```

### Output Escaping

```php
echo esc_html($landmark['label']);
echo esc_url($landmark['url']);
echo wp_kses_post($landmark['description']);
```

### Direct Access Prevention

```php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
```

---

## Extension Points

### Hooks & Filters

**Filters:**
```php
// Modify default landmarks
apply_filters('pageflash_landmarks', $defaults);
```

**Actions:**
```php
// Before landmark initialization
do_action('pageflash_before_landmark_init');

// After landmark initialization
do_action('pageflash_after_landmark_init');
```

### Adding New Landmarks

1. **Create Feature Class:**
```php
namespace PageFlash\Landmark\NewFeature;

class NewFeature {
    public function __construct() {
        // Initialize feature
    }
}
```

2. **Register in LandmarkList:**
```php
'newfeature' => [
    'id' => wp_unique_id('pf-'),
    'label' => __('New Feature', 'pageflash'),
    'description' => __('Feature description', 'pageflash'),
    'active' => false,
    'slug' => 'newfeature',
    'package' => 'free'
]
```

3. **Add to Landmark Switch:**
```php
case 'newfeature':
    new NewFeature();
    break;
```

---

## Testing Strategy

### PHP Testing
- **Tool:** PHPUnit
- **Standards:** WordPress Coding Standards (WPCS)
- **Linting:** PHP CodeSniffer (phpcs.xml)

### JavaScript Testing
- **Tool:** @wordpress/scripts (includes Jest)
- **Linting:** ESLint
- **Style:** WordPress JavaScript Coding Standards

### Commands
```bash
npm run lint:wpcs       # PHP linting
npm run lint:js         # JavaScript linting
npm run lint:css        # CSS linting
npm run lint:fix        # Auto-fix issues
```

---

## Development Workflow

### Local Development

1. **Install Dependencies:**
```bash
npm install
composer install
```

2. **Start Development Server:**
```bash
npm start
```

3. **Watch for Changes:**
- Webpack watches `src/` directory
- Auto-rebuilds on file changes
- Hot Module Replacement (HMR) available with `npm start:hot`

4. **Code Quality:**
```bash
npm run lint:js
npm run lint:wpcs
npm run format
```

### Production Build

```bash
npm run build
```

**Output:**
- Minified JavaScript
- Compiled and minified CSS
- Optimized assets in `build/` directory

---

## Future Enhancements

### Planned Features
- Additional landmark modules (InstantPage, etc.)
- Advanced caching strategies
- Performance analytics dashboard
- A/B testing capabilities
- CDN integration

### Architecture Improvements
- GraphQL API layer
- TypeScript migration
- Unit test coverage
- E2E testing with Playwright
- Component library documentation (Storybook)

---

## Troubleshooting

### Common Issues

**1. Admin Dashboard Not Loading**
- Check if `build/admin/admin.js` exists
- Run `npm run build`
- Verify browser console for errors
- Check nonce verification

**2. REST API 403 Errors**
- Verify user has `manage_options` capability
- Check nonce header in requests
- Ensure user is logged in

**3. Landmarks Not Activating**
- Check `wp_options` table for `pageflash_landmarks`
- Verify landmark slug matches case statement
- Check PHP error logs

**4. Build Errors**
- Clear `node_modules` and reinstall
- Check Node.js version (20.10.0+)
- Verify webpack.config.js syntax

---

## Contributing

### Code Standards
- **PHP:** WordPress Coding Standards
- **JavaScript:** WordPress JavaScript Coding Standards
- **CSS:** WordPress CSS Coding Standards

### Pull Request Process
1. Create feature branch
2. Write/update tests
3. Run linters
4. Update documentation
5. Submit PR with clear description

---

## Resources

- **WordPress Plugin Handbook:** https://developer.wordpress.org/plugins/
- **React Documentation:** https://react.dev/
- **WordPress REST API:** https://developer.wordpress.org/rest-api/
- **@wordpress/scripts:** https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/

---

## Changelog

### Version 1.2.0
- Initial architecture documentation
- REST API implementation
- React admin dashboard
- HashRouter routing fix
- SCSS @use migration

---

## License

GPL-3.0-or-later

---

## Contact

- **Author:** theaminul
- **GitHub:** https://github.com/theaminuli/pageflash
- **Support:** https://wordpress.org/support/plugin/pageflash/
