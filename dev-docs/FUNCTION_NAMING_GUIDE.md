# PageFlash Function Naming & Consistency Guidelines

> **Issue Reference**: [#106](https://github.com/theaminuli/pageflash/issues/106)  
> **Status**: Open  
> **Label**: Good first issue  
> **Last Updated**: December 2024

---

## 📋 Table of Contents

- [Overview](#overview)
- [Why This Matters](#why-this-matters)
- [Current Problems](#current-problems)
- [Naming Standards](#naming-standards)
- [Code Quality Requirements](#code-quality-requirements)
- [Documentation Standards](#documentation-standards)
- [Examples](#examples)
- [Refactoring Checklist](#refactoring-checklist)
- [Quick Reference](#quick-reference)

---

## Overview

The PageFlash project currently has inconsistent function naming conventions with varying scope prefixes and generic names. This document establishes standardized naming patterns using the **`pageflash_`** prefix to ensure better maintainability, readability, and conflict prevention.

---

## Why This Matters

Consistent function naming provides:

✅ **Readability** – Developers instantly understand function purpose and scope  
✅ **Maintainability** – Easier debugging, testing, and code extension  
✅ **Conflict Prevention** – No collisions with WordPress core or other plugins  
✅ **Scalability** – Clean foundation for adding new features  
✅ **Team Collaboration** – Standardized patterns improve onboarding

---

## Current Problems

### ❌ Problem 1: Generic Function Names

**Issue**: Functions lack plugin identification

```php
// Bad - No plugin context
async_script_loader()
register_post_type()
enqueue_styles()
```

**Impact**: High risk of conflicts in WordPress multi-plugin environments

### ❌ Problem 2: Inconsistent Prefixing

**Issue**: Mixed use of prefixes across the codebase

```php
// Inconsistent examples
public function pageflash_async_script_loader() {}
public function async_script_loader() {}
public function pf_register_hooks() {}
```

### ❌ Problem 3: Variable Function Name Length

**Issue**: Some names are too verbose, others too generic

```php
// Too long
pageflash_initialize_and_configure_admin_settings_panel()

// Too generic
init()
process()
```

---

## Naming Standards

### Standard Prefix: `pageflash_`

All public functions **must** use the `pageflash_` prefix for proper namespacing.

### Naming Pattern

```
pageflash_{context}_{action}_{subject}
```

**Components:**
- **context** *(optional)*: Feature area (`admin`, `frontend`, `cache`, `api`)
- **action**: Verb describing the operation (`get`, `set`, `register`, `enqueue`, `render`)
- **subject**: Target of the action (`script`, `style`, `post_type`, `settings`)

### Examples by Category

#### Core Functions
```php
pageflash_init()
pageflash_activate()
pageflash_deactivate()
pageflash_uninstall()
```

#### Admin Functions
```php
pageflash_admin_enqueue_scripts()
pageflash_admin_register_settings()
pageflash_admin_render_menu_page()
pageflash_admin_save_options()
```

#### Frontend Functions
```php
pageflash_frontend_enqueue_styles()
pageflash_frontend_async_scripts()
pageflash_frontend_render_widget()
pageflash_frontend_get_options()
```

#### Cache Functions
```php
pageflash_cache_clear()
pageflash_cache_get()
pageflash_cache_set()
pageflash_cache_delete()
```

#### Utility Functions
```php
pageflash_sanitize_option()
pageflash_validate_url()
pageflash_format_bytes()
pageflash_get_version()
```

---

## Code Quality Requirements

### 1. Strict Type Comparisons

Always use strict comparison for type safety:

```php
// ✅ Correct - Strict comparison
if ( in_array( $handle, $async_handles, true ) ) {
    // Process handle
}

// ❌ Incorrect - Loose comparison
if ( in_array( $handle, $async_handles ) ) {
    // Avoid this
}
```

### 2. Modern PHP String Functions

Use PHP 8+ string functions when available:

```php
// ✅ Modern approach (PHP 8+)
if ( str_contains( $tag, 'async' ) ) {
    // Process tag
}

if ( str_starts_with( $url, 'https://' ) ) {
    // Secure URL
}

if ( str_ends_with( $file, '.php' ) ) {
    // PHP file
}

// ❌ Legacy approach (avoid)
if ( strpos( $tag, 'async' ) !== false ) {
    // Old method
}
```

### 3. Short Array Syntax

Use modern array syntax for cleaner code:

```php
// ✅ Modern syntax (PHP 5.4+)
$handles = [ 'pageflash-quicklink', 'pageflash-frontend' ];
$config = [
    'cache_enabled' => true,
    'ttl' => 3600,
];

// ❌ Legacy syntax (avoid)
$handles = array( 'pageflash-quicklink', 'pageflash-frontend' );
```

---

## Documentation Standards

Every function **must** include proper DocBlock documentation following WordPress standards:

### Complete DocBlock Example

```php
/**
 * Add async attribute to specified script tags.
 *
 * Filters the script tag HTML to add the async attribute for
 * specified script handles to improve page load performance.
 * Only adds async if not already present.
 *
 * @since 1.0.0
 * @access public
 *
 * @param string $tag    The script tag HTML.
 * @param string $handle The script handle registered with wp_enqueue_script.
 * @return string Modified script tag with async attribute if applicable.
 */
public function pageflash_async_script_loader( $tag, $handle ) {
    $async_handles = [ 'pageflash-quicklink', 'pageflash-frontend' ];
    
    if ( in_array( $handle, $async_handles, true ) && ! str_contains( $tag, 'async' ) ) {
        return str_replace( ' src=', ' async src=', $tag );
    }
    
    return $tag;
}
```

### Required DocBlock Tags

| Tag | Purpose | Required |
|-----|---------|----------|
| `@since` | Version when introduced | ✅ Yes |
| `@access` | Visibility level | ✅ Yes |
| `@param` | Parameter types & descriptions | ✅ Yes (if params exist) |
| `@return` | Return type & description | ✅ Yes (if returns value) |
| `@throws` | Exceptions thrown | If applicable |
| `@link` | Related documentation | Optional |
| `@see` | Related functions | Optional |

---

## Examples

### Example 1: Script Loader Refactoring

#### ❌ Before

```php
public function async_script_loader( $tag, $handle ) {
    $async_handles = array( 'quicklink', 'frontend' );
    if ( in_array( $handle, $async_handles ) && strpos( $tag, 'async' ) === false ) {
        return str_replace( '></script>', ' async></script>', $tag );
    }
    return $tag;
}
```

#### ✅ After

```php
/**
 * Add async attribute to PageFlash script tags.
 *
 * Improves page load performance by loading scripts asynchronously
 * for specified handles.
 *
 * @since 1.0.0
 * @access public
 *
 * @param string $tag    Script tag HTML.
 * @param string $handle Script handle.
 * @return string Modified script tag with async attribute.
 */
public function pageflash_async_script_loader( $tag, $handle ) {
    $async_handles = [ 'pageflash-quicklink', 'pageflash-frontend' ];
    
    if ( in_array( $handle, $async_handles, true ) && ! str_contains( $tag, 'async' ) ) {
        return str_replace( ' src=', ' async src=', $tag );
    }
    
    return $tag;
}
```

### Example 2: Admin Settings Registration

#### ❌ Before

```php
public function register_settings() {
    register_setting( 'pf_options', 'pf_settings' );
}
```

#### ✅ After

```php
/**
 * Register PageFlash admin settings.
 *
 * Registers plugin settings with WordPress Settings API including
 * sanitization callback for security.
 *
 * @since 1.0.0
 * @access public
 *
 * @return void
 */
public function pageflash_admin_register_settings() {
    register_setting( 
        'pageflash_options', 
        'pageflash_settings',
        [
            'type' => 'array',
            'sanitize_callback' => 'pageflash_sanitize_settings',
            'default' => [],
        ]
    );
}
```

### Example 3: Cache Management

#### ❌ Before

```php
function get_cache( $key ) {
    return get_transient( 'pf_' . $key );
}

function set_cache( $key, $value ) {
    set_transient( 'pf_' . $key, $value, 3600 );
}
```

#### ✅ After

```php
/**
 * Retrieve value from PageFlash cache.
 *
 * @since 1.0.0
 * @access public
 *
 * @param string $key Cache key identifier.
 * @return mixed|false Cached value or false if not found.
 */
function pageflash_cache_get( $key ) {
    $cache_key = 'pageflash_' . sanitize_key( $key );
    return get_transient( $cache_key );
}

/**
 * Store value in PageFlash cache.
 *
 * @since 1.0.0
 * @access public
 *
 * @param string $key        Cache key identifier.
 * @param mixed  $value      Value to cache.
 * @param int    $expiration Optional. Cache expiration in seconds. Default 3600.
 * @return bool True on success, false on failure.
 */
function pageflash_cache_set( $key, $value, $expiration = 3600 ) {
    $cache_key = 'pageflash_' . sanitize_key( $key );
    return set_transient( $cache_key, $value, $expiration );
}
```

### Example 4: Hook Registration

#### ❌ Before

```php
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );
add_filter( 'script_loader_tag', 'async_loader', 10, 2 );
```

#### ✅ After

```php
add_action( 'wp_enqueue_scripts', 'pageflash_frontend_enqueue_scripts' );
add_filter( 'script_loader_tag', 'pageflash_async_script_loader', 10, 2 );
```

## 🧪 Testing Locally

### Prerequisites

- Node.js 14+ installed
- Access to PHP files

### Basic Usage

```bash
# Check a single file
node .github/scripts/check-naming.js includes/class-admin.php

# Check multiple files
node .github/scripts/check-naming.js includes/*.php

# Check all PHP files (excluding vendor)
find . -name "*.php" -not -path "./vendor/*" | xargs node .github/scripts/check-naming.js

# Generate JSON output
node .github/scripts/check-naming.js --json includes/*.php

# Save report to file
node .github/scripts/check-naming.js includes/*.php > report.md
```

### Using npm Scripts

If you added `package.json`:

```bash
# Check specific files
npm run check includes/class-admin.php

# Check all PHP files
npm run check:all

# Generate JSON report
npm run check:json includes/*.php > report.json
```

### Testing in Docker

```bash
# Using Docker with Node.js
docker run --rm -v $(pwd):/app -w /app node:18 \
  node .github/scripts/check-naming.js includes/*.php
```

---

## 📊 Example Output

### Terminal Output

```
# Function Naming Consistency Report

## 📊 Summary

| Metric | Count | Status |
|--------|-------|--------|
| Files Checked | 3 | ℹ️ |
| Functions Checked | 12 | ℹ️ |
| Total Issues | 8 | ⚠️ |
| Missing Prefix | 3 | ❌ |
| Missing DocBlocks | 2 | ❌ |
| Loose Comparisons | 2 | ⚠️ |
| Legacy Array Syntax | 1 | ⚠️ |
| Legacy String Functions | 0 | ✅ |

## 🔍 Detailed Findings

### ❌ Functions Missing `pageflash_` Prefix (3)

- `class-admin.php:45` - `async_script_loader()` → Rename to `pageflash_async_script_loader()`
- `class-cache.php:23` - `get_cache()` → Rename to `pageflash_get_cache()`
- `functions.php:12` - `sanitize_option()` → Rename to `pageflash_sanitize_option()`

### 📝 Functions Missing DocBlocks (2)

- `class-admin.php:45` - `async_script_loader()`
- `functions.php:12` - `sanitize_option()`

## ✅ Refactoring Checklist

- [ ] All functions use `pageflash_` prefix
- [ ] All functions have proper DocBlocks
- [x] All `in_array()` calls use strict comparison
- [x] Modern `[]` array syntax used
- [x] Modern PHP 8+ string functions used
```


## 📈 Advanced Usage

### Programmatic Usage

Use the checker in your Node.js scripts:

```javascript
const PageFlashNamingChecker = require('./.github/scripts/check-naming');

const checker = new PageFlashNamingChecker();
const results = checker.run(['file1.php', 'file2.php']);

console.log(`Found ${results.stats.total_issues} issues`);

// Generate reports
const markdownReport = checker.generateReport();
const jsonReport = checker.generateJSON();

// Access specific issues
if (results.issues.missing_prefix.length > 0) {
    console.log('Functions without prefix:');
    results.issues.missing_prefix.forEach(issue => {
        console.log(`- ${issue.function} in ${issue.file}:${issue.line}`);
    });
}
```

### Custom Checks

Extend the checker with custom rules:

```javascript
class CustomPageFlashChecker extends PageFlashNamingChecker {
    checkFile(file) {
        super.checkFile(file);
        
        // Add your custom checks
        this.checkCustomRule(file);
    }
    
    checkCustomRule(file) {
        const content = fs.readFileSync(file, 'utf8');
        
        // Your custom logic here
        if (content.includes('TODO')) {
            this.issues.custom_todos = this.issues.custom_todos || [];
            this.issues.custom_todos.push({
                file: file,
                message: 'Found TODO comments'
            });
        }
    }
}
```
---

## Refactoring Checklist

### Phase 1: Audit 📊
- [ ] List all functions without `pageflash_` prefix
- [ ] Identify inconsistent naming patterns
- [ ] Find functions missing DocBlocks
- [ ] Document functions using legacy PHP syntax
- [ ] Note functions with loose type comparisons

### Phase 2: Planning 📝
- [ ] Create function mapping (old → new names)
- [ ] Identify potential breaking changes
- [ ] Plan backward compatibility strategy
- [ ] Document deprecated functions
- [ ] Set up automated testing

### Phase 3: Implementation 🔨
- [ ] Rename functions with `pageflash_` prefix
- [ ] Update all function calls throughout codebase
- [ ] Add/update DocBlocks for all functions
- [ ] Replace `strpos()` with `str_contains()`
- [ ] Replace loose `in_array()` with strict comparison
- [ ] Convert `array()` to `[]` syntax
- [ ] Update hook names to match function names
- [ ] Create deprecated function wrappers

### Phase 4: Testing 🧪
- [ ] Run full test suite
- [ ] Test backward compatibility with deprecated functions
- [ ] Verify no naming conflicts with WordPress core
- [ ] Check for conflicts with popular plugins
- [ ] Performance testing on renamed functions
- [ ] Test on multiple PHP versions (7.4, 8.0, 8.1, 8.2)

### Phase 5: Documentation 📚
- [ ] Update README with new function references
- [ ] Create migration guide for developers
- [ ] Update inline code comments
- [ ] Generate updated API documentation
- [ ] Update CHANGELOG with breaking changes
- [ ] Document deprecated functions

---

## Quick Reference

### Naming Pattern Examples

| Category | Pattern | Example |
|----------|---------|---------|
| **Core** | `pageflash_` | `pageflash_init()` |
| **Admin** | `pageflash_admin_` | `pageflash_admin_menu()` |
| **Frontend** | `pageflash_frontend_` | `pageflash_frontend_render()` |
| **Cache** | `pageflash_cache_` | `pageflash_cache_clear()` |
| **API** | `pageflash_api_` | `pageflash_api_request()` |
| **Settings** | `pageflash_settings_` | `pageflash_settings_get()` |
| **Utility** | `pageflash_` | `pageflash_sanitize()` |
| **Hooks** | `pageflash_` | `pageflash_before_init` |

### Common Action Verbs

| Verb | Usage | Example |
|------|-------|---------|
| `get` | Retrieve data | `pageflash_get_option()` |
| `set` | Store data | `pageflash_set_cache()` |
| `register` | Register with WP | `pageflash_register_post_type()` |
| `enqueue` | Enqueue assets | `pageflash_enqueue_scripts()` |
| `render` | Output HTML | `pageflash_render_widget()` |
| `validate` | Check validity | `pageflash_validate_url()` |
| `sanitize` | Clean data | `pageflash_sanitize_input()` |
| `format` | Format data | `pageflash_format_date()` |
| `init` | Initialize | `pageflash_init_hooks()` |
| `delete` | Remove data | `pageflash_delete_cache()` |

### Backward Compatibility Pattern

If you need to maintain compatibility with old function names:

```php
/**
 * Legacy function wrapper for backward compatibility.
 *
 * @since 1.0.0
 * @deprecated 2.0.0 Use pageflash_async_script_loader() instead.
 * @see pageflash_async_script_loader()
 *
 * @param string $tag    Script tag HTML.
 * @param string $handle Script handle.
 * @return string Modified script tag.
 */
function async_script_loader( $tag, $handle ) {
    _deprecated_function( __FUNCTION__, '2.0.0', 'pageflash_async_script_loader' );
    return pageflash_async_script_loader( $tag, $handle );
}
```

---

## Additional Resources

- 📖 [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- 🐘 [PHP 8 Migration Guide](https://www.php.net/manual/en/migration80.php)
- 📦 [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- 📝 [PHPDoc Documentation](https://docs.phpdoc.org/guide/guides/docblocks.html)
- 🔍 [WordPress Function Reference](https://developer.wordpress.org/reference/)

---

## Contributing

This is marked as a **Good First Issue** and contributions are welcome!

### How to Contribute

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b refactor/function-naming`
3. **Follow** the naming guidelines in this document
4. **Test** your changes thoroughly
5. **Submit** a pull request with clear description

### Need Help?

- Comment on [Issue #106](https://github.com/theaminuli/pageflash/issues/106)
- Review existing pull requests for examples
- Check the WordPress Codex for best practices

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | Dec 2024 | Initial guidelines created |

---

**Maintained by**: [@theaminuli](https://github.com/theaminuli)  
**License**: Same as PageFlash project  
**Last Updated**: December 26, 2024