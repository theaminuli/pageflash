# GitHub Copilot Instructions for PageFlash

This document provides coding guidelines and standards for working on the PageFlash WordPress plugin. These instructions ensure consistency, security, and code quality across the project.

## Table of Contents

1. [Security Standards](#security-standards)
2. [PHP Standards](#php-standards)
3. [WordPress Coding Standards](#wordpress-coding-standards)
4. [JavaScript Standards](#javascript-standards)
5. [Accessibility Standards](#accessibility-standards)
6. [Block Editor Standards](#block-editor-standards)
7. [Summary Checklist](#summary-checklist)
8. [References](#references)

---

## Security Standards

Security is paramount in WordPress plugin development. Always follow these security practices:

### Nonces (Number Used Once)

- **Always verify nonces** for form submissions and AJAX requests to prevent CSRF attacks with wordpress/api-fetch
**Example:**
```javascript
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';

const queryParams = { include: [1,2,3] }; // Return posts with ID = 1,2,3.

apiFetch( { path: addQueryArgs( '/wp/v2/posts', queryParams ) } ).then( ( posts ) => {
    console.log( posts );
} );
```

### Capability Checks

- **Always check user capabilities** before performing sensitive operations
- Use `current_user_can()` to verify permissions
- Common capabilities: `manage_options`, `edit_posts`, `edit_pages`, `publish_posts`

**Example:**
```php
// Check if user has permission to manage options
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'pageflash' ) );
}
```

### Data Sanitization and Validation

Always sanitize input and escape output. Use WordPress core sanitization functions:

#### Input Sanitization
```php
// Text fields
$text = sanitize_text_field( $_POST['text_field'] );

// Textarea
$textarea = sanitize_textarea_field( $_POST['textarea_field'] );

// Email
$email = sanitize_email( $_POST['email_field'] );

// URL
$url = esc_url_raw( $_POST['url_field'] );

// Integer
$number = absint( $_POST['number_field'] );

// Arrays
$array = array_map( 'sanitize_text_field', $_POST['array_field'] );

// HTML content (with allowed tags)
$content = wp_kses_post( $_POST['content_field'] );
```

#### Output Escaping
```php
// HTML content
echo esc_html( $text );

// Attributes
echo '<div class="' . esc_attr( $class ) . '">';

// URLs
echo '<a href="' . esc_url( $url ) . '">';

// JavaScript
echo '<script>var text = "' . esc_js( $text ) . '";</script>';

// Post content (allows safe HTML)
echo wp_kses_post( $content );

// Textarea
echo esc_textarea( $textarea );
```

### Database Queries

- **Always use prepared statements** for database queries
- Use `$wpdb->prepare()` to prevent SQL injection

**Example:**
```php
global $wpdb;

// Prepared statement
$results = $wpdb->get_results( 
    $wpdb->prepare( 
        "SELECT * FROM {$wpdb->prefix}pageflash WHERE id = %d AND status = %s",
        $id,
        $status
    )
);
```

---

## PHP Standards

### Version Requirements

- **Minimum PHP Version:** 8.1
- **Tested up to:** Latest stable PHP version
- Write code compatible with PHP 8.1+ features
- When using PHP 8.1+ features (e.g., named arguments, enums, readonly properties), document their usage and ensure graceful degradation or feature detection if the plugin needs to support older PHP versions in the future

### PSR-4 Autoloading

Follow PSR-4 autoloading standards for class organization:

- **Namespace:** All classes must be in the `PageFlash` namespace
- **Directory structure:** Mirrors namespace structure
- **File naming:** Class name matches file name exactly

**Example:**
```php
<?php
/**
 * AssetsManager class.
 * File located at: includes/AssetsManager/AssetsManager.php
 * Namespace PageFlash\AssetsManager maps to directory includes/AssetsManager/
 */

namespace PageFlash\AssetsManager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class AssetsManager {
    // Class implementation
}
```

### PHP Coding Style

- Use **tabs for indentation**
- **Opening braces** on the same line for functions and classes
- **Single quotes** for strings (unless interpolation is needed)
- **Type declarations** for function parameters and return types where appropriate
- **Strict types** where beneficial for type safety

**Example:**
```php
<?php

namespace PageFlash\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Settings {
    
    /**
     * Get setting value.
     *
     * @param string $key Setting key.
     * @param mixed  $default Default value.
     * @return mixed Setting value.
     */
    public function get_setting( string $key, $default = '' ) {
        $value = get_option( "pageflash_{$key}", $default );
        return $value;
    }
}
```

---

## WordPress Coding Standards

Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/) strictly:

### PHP Coding Standards

- Use WordPress core functions instead of native PHP when available
- Follow WordPress naming conventions (snake_case for functions and variables)
- Prefix all global functions, classes, and constants with `pageflash_` or `PAGEFLASH_`
- Include proper DocBlocks for all functions and classes

**Example:**
```php
/**
 * Load PageFlash textdomain.
 *
 * Load gettext translate for PageFlash text domain.
 *
 * @since PageFlash 1.0.0
 *
 * @return void
 */
function pageflash_load_plugin_textdomain() {
    load_plugin_textdomain( 'pageflash' );
}
```

### Internationalization (i18n)

- **Text domain:** `pageflash`
- Wrap all user-facing strings with translation functions
- Use proper translator comments for context

**Example:**
```php
// Simple text
esc_html__( 'Settings saved successfully.', 'pageflash' );

// Text with output
echo esc_html__( 'Welcome to PageFlash', 'pageflash' );

// Text with variables (use sprintf)
echo sprintf(
    /* translators: %s: plugin version */
    esc_html__( 'PageFlash Version %s', 'pageflash' ),
    PAGEFLASH_VERSION
);

// Plural forms
echo sprintf(
    _n(
        'One link prefetched',
        '%s links prefetched',
        $count,
        'pageflash'
    ),
    number_format_i18n( $count )
);
```

### Hooks and Filters

- Use descriptive hook names with `pageflash_` prefix
- Document hooks with proper DocBlocks
- Provide hook examples in documentation

**Example:**
```php
/**
 * Filters PageFlash settings before saving.
 *
 * @since PageFlash 1.0.0
 *
 * @param array $settings Array of PageFlash settings.
 */
$settings = apply_filters( 'pageflash_settings', $settings );

/**
 * Fires after PageFlash settings are saved.
 *
 * @since PageFlash 1.0.0
 *
 * @param array $settings Array of saved settings.
 */
do_action( 'pageflash_settings_saved', $settings );
```

### WordPress Version Requirements

- **Minimum WordPress Version:** 6.0
- **Tested up to:** Latest WordPress version
- Use WordPress 6.x+ features and APIs

### File Organization

- **Main plugin file:** `pageflash.php`
- **Plugin class:** `plugin.php`
- **Includes directory:** All classes in `includes/` following PSR-4
- **Assets directory:** JavaScript, CSS, images in `assets/`
- **Source directory:** Development files in `src/`

---

## JavaScript Standards

### Pure JavaScript - No jQuery

- **Use vanilla JavaScript only** - no jQuery dependencies
- Use modern JavaScript (ES6+) features
- Follow WordPress JavaScript coding standards

### Modern JavaScript Features

**Use:**
- `const` and `let` (no `var`)
- Arrow functions
- Template literals
- Destructuring
- Spread operator
- Async/await for promises
- Fetch API for AJAX

**Example:**
```javascript
/**
 * Fetch PageFlash settings from server.
 *
 * @since PageFlash 1.0.0
 * @return {Promise<Object>} Promise resolving to settings object.
 */
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';

const queryParams = { include: [1,2,3] }; // Return posts with ID = 1,2,3.

apiFetch( { path: addQueryArgs( '/wp/v2/posts', queryParams ) } ).then( ( posts ) => {
    console.log( posts );
} );
```

### DOM Manipulation

Use modern DOM APIs:

```javascript
// Query selectors
const element = document.querySelector( '.pageflash-container' );
const elements = document.querySelectorAll( '.pageflash-item' );

// Event listeners
element.addEventListener( 'click', ( event ) => {
    event.preventDefault();
    // Handle click
} );

// Class manipulation
element.classList.add( 'active' );
element.classList.remove( 'inactive' );
element.classList.toggle( 'expanded' );

// Creating elements
const div = document.createElement( 'div' );
div.textContent = 'PageFlash';
div.setAttribute( 'data-id', '123' );
```

### JSDoc Comments

Document all functions with JSDoc:

```javascript
/**
 * Validate and get a positive number.
 *
 * @param {number|string} value - Number or numeric string to validate.
 * @return {number|null} - Validated positive number or null if invalid.
 * @since PageFlash 1.0.0
 */
function validatePositiveNumber( value ) {
    const num = Number( value );
    return isNaN( num ) || num <= 0 ? null : num;
}
```

### Type Definitions

Use JSDoc type definitions for complex objects:

```javascript
/**
 * PageFlash settings object.
 *
 * @typedef {Object} PageFlashSettings
 * @property {string}   el        - CSS selector for the DOM element to observe.
 * @property {number}   limit     - The total requests that can be prefetched.
 * @property {number}   throttle  - The concurrency limit for simultaneous requests.
 * @property {number}   timeout   - Timeout after which prefetching will occur.
 * @property {boolean}  priority  - Attempt higher priority fetch.
 * @property {string[]} origins   - Allowed origins to prefetch.
 * @property {RegExp[]} ignores   - Patterns to determine whether a URL is ignored.
 */
```

---

## Accessibility Standards

Follow [WordPress Accessibility Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/accessibility/):

### Semantic HTML

- Use proper HTML5 semantic elements (`<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`)
- Use heading hierarchy correctly (`<h1>` to `<h6>`)
- Use lists for list content (`<ul>`, `<ol>`, `<li>`)

### ARIA Attributes

Add ARIA attributes for enhanced accessibility:

```html
<!-- Buttons -->
<button aria-label="<?php esc_attr_e( 'Close settings panel', 'pageflash' ); ?>">
    <span aria-hidden="true">&times;</span>
</button>

<!-- Links -->
<a href="#" aria-label="<?php esc_attr_e( 'View PageFlash Documentation', 'pageflash' ); ?>">
    <?php esc_html_e( 'Docs', 'pageflash' ); ?>
</a>

<!-- Live regions -->
<div role="status" aria-live="polite" aria-atomic="true">
    <?php esc_html_e( 'Settings saved successfully', 'pageflash' ); ?>
</div>

<!-- Hidden content -->
<span class="screen-reader-text">
    <?php esc_html_e( 'Additional information for screen readers', 'pageflash' ); ?>
</span>
```

### Color and Contrast

- Ensure sufficient color contrast (WCAG AA minimum: 4.5:1 for normal text)
- Don't rely on color alone to convey information
- Test with color blindness simulators

### Form Accessibility

```html
<!-- Labels -->
<label for="pageflash-timeout">
    <?php esc_html_e( 'Timeout (ms)', 'pageflash' ); ?>
</label>
<input 
    type="number" 
    id="pageflash-timeout" 
    name="timeout" 
    aria-describedby="timeout-description"
    value="<?php echo esc_attr( $timeout ); ?>"
>
<p id="timeout-description" class="description">
    <?php esc_html_e( 'Time in milliseconds before prefetching starts', 'pageflash' ); ?>
</p>

<!-- Required fields -->
<input 
    type="text" 
    required 
    aria-required="true"
    aria-invalid="<?php echo esc_attr( $has_error ? 'true' : 'false' ); ?>"
>

<!-- Error messages -->
<div role="alert" aria-live="assertive">
    <?php esc_html_e( 'Please enter a valid timeout value', 'pageflash' ); ?>
</div>
```

---

## Block Editor Standards

Follow [Block Editor Handbook](https://developer.wordpress.org/block-editor/) standards when working with Gutenberg blocks:

### Block Components

Use WordPress components:

```javascript
import { 
    PanelBody, 
    ToggleControl,
    TextControl,
    RangeControl 
} from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';

function Edit( { attributes, setAttributes } ) {
    const { enabled, timeout } = attributes;
    
    return (
        <>
            <InspectorControls>
                <PanelBody 
                    title={ __( 'PageFlash Settings', 'pageflash' ) }
                    initialOpen={ true }
                >
                    <ToggleControl
                        label={ __( 'Enable Prefetch', 'pageflash' ) }
                        checked={ enabled }
                        onChange={ ( value ) => setAttributes( { enabled: value } ) }
                    />
                    <RangeControl
                        label={ __( 'Timeout (ms)', 'pageflash' ) }
                        value={ timeout }
                        onChange={ ( value ) => setAttributes( { timeout: value } ) }
                        min={ 0 }
                        max={ 5000 }
                    />
                </PanelBody>
            </InspectorControls>
            <div>
                { __( 'PageFlash Prefetch Control', 'pageflash' ) }
            </div>
        </>
    );
}
```

### Build Process

- Use `@wordpress/scripts` for building blocks
- Follow WordPress build conventions
- Entry point: `src/index.js`
- Output: `build/index.js`

---

## Additional Best Practices

### Error Handling

Always implement proper error handling:

```php
// PHP
try {
    // Code that might throw exception
    $result = risky_operation();
} catch ( Exception $e ) {
    error_log( 'PageFlash Error: ' . $e->getMessage() );
    wp_die( esc_html__( 'An error occurred', 'pageflash' ) );
}
```

```javascript
// JavaScript
try {
    const data = await fetchData();
    processData( data );
} catch ( error ) {
    console.error( 'PageFlash Error:', error );
    showErrorMessage( error.message );
}
```

### Performance

- Minimize database queries
- Use transients for caching when appropriate
- Lazy load assets when possible
- Optimize images and assets
- Use `requestIdleCallback` for non-critical operations

### Code Documentation

- Document all functions, classes, and methods
- Include `@since` tags with version numbers
- Provide usage examples where helpful
- Keep comments up to date

### Testing

- Test on minimum required PHP and WordPress versions
- Test with WordPress debug mode enabled
- Test accessibility with screen readers
- Test keyboard navigation
- Validate with PHPCS (WordPress Coding Standards)
- Validate JavaScript with ESLint

---

## Code Review Checklist

When reviewing pull requests, Copilot should verify the following criteria:

1. **WordPress Coding Standards**: Code follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
2. **No New Warnings/Errors**: Changes generate no new warnings or errors
3. **WordPress Compatibility**: Tested on WordPress 6.4+
4. **Pattern Compliance**: Code follows patterns documented in [AGENTS.md](../AGENTS.md)
5. **File Size Limit**: All files are under 300 lines (if applicable)
6. **Documentation**: JSDoc comments added to new functions
7. **Accessibility**: WCAG 2.1 AA compliant
8. **Security**: All user input is validated and sanitized
9. **Internationalization**: All user-facing strings use `__()`, `_e()`, `esc_html__()`, `esc_html_e()`, or other WordPress translation functions
10. **Issue Linking**: If the pull request is merged, the related issue will be closed

---

## Summary Checklist

Before submitting code, ensure:

- [ ] All user input is sanitized
- [ ] All output is escaped
- [ ] Nonces are implemented for forms and AJAX
- [ ] Capability checks are in place
- [ ] Code follows WordPress coding standards
- [ ] No jQuery dependencies (pure JavaScript only)
- [ ] Accessibility attributes are present
- [ ] Internationalization is implemented
- [ ] PSR-4 autoloading is followed
- [ ] Code is compatible with PHP 8.1+
- [ ] Code is compatible with WordPress 6.x+
- [ ] Proper error handling is implemented
- [ ] Code is documented with DocBlocks/JSDoc
- [ ] PHPCS validation passes
- [ ] ESLint validation passes

---

## References

- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [WordPress Accessibility Handbook](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/accessibility/)
- [PSR-4 Autoloading Standard](https://www.php-fig.org/psr/psr-4/)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

---

*Last Updated: 2025-10-11*
