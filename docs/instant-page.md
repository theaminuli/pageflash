# Instant.page - Make Your Pages Feel Instant

## Overview

Instant.page is a cutting-edge preloading technique that makes your website's pages feel instant by prefetching pages right before users click on them. This feature uses just-in-time preloading to cheat both latency and human perception, making your site up to 1% more engaging.

## What is Instant.page?

Instant.page uses **just-in-time preloading** - it preloads a page right before a user clicks on it. By detecting user intent through hover and touch events, it starts loading the next page during the time between when the user shows intent and when they actually click. This makes page loads feel instant even on slower connections.

**Key Statistics:**
- Amazon found that removing 100ms of latency improves sales by 1%
- Preloading starts on average 300ms before the click on desktop
- Preloading starts on average 90ms before the click on mobile
- Trusted by Adidas, Spotify, Rakuten, PepsiCo, Yoast, and thousands of others

## How It Works

### On Desktop

**Method 1: Hover Preloading (Default)**
1. User hovers their mouse over a link
2. After 65ms of hovering, there's a 50% chance the user will click
3. Instant.page starts preloading at this moment
4. User clicks and page loads instantly (300ms average head start)

**Method 2: Mousedown Preloading**
- Preloading starts when user presses mouse button
- Zero unused requests, still 80ms improvement on average

**Method 3: Aggressive Preloading**
- Preload on hover + trigger navigation on mousedown
- Makes your pages the fastest in the world

### On Mobile

**Method 1: Touch Start Preloading (Default)**
- User starts touching the screen before releasing
- Leaves 90ms average for preloading

**Method 2: Viewport Preloading**
- Preloads links as soon as they become visible
- Best for smaller screens with limited viewport

## Benefits

### 1. **Perceived Instant Loading**
- The human brain perceives actions under 100ms as instant
- Instant.page works even on 3G connections
- Pages feel instant regardless of server location

### 2. **Improved Conversion Rates**
- Every 100ms of removed latency = 1% improvement in sales
- Faster perceived loading keeps users engaged
- Reduced frustration from waiting

### 3. **Smart & Respectful**
- Only preloads HTML (not CSS, JS, or images)
- Pages are preloaded only when there's a good chance they'll be visited
- Respects user's data saver mode
- Uses passive event listeners for smooth scrolling
- Uses `requestIdleCallback` to stay smooth

### 4. **Minimal Overhead**
- Only 1KB in size
- Loads after everything else (non-blocking)
- Minimal server and bandwidth impact
- Free and open source (MIT license)

## How to Use

Instant.page is automatically enabled in PageFlash when you activate it. The script loads as a JavaScript module with `type="module"` attribute for optimal performance.

### Default Behavior

By default, PageFlash Instant.page:
- Preloads on hover (65ms delay) on desktop
- Preloads on touch start on mobile
- Respects data saver mode
- Uses passive event listeners
- Automatically excludes external links (unless configured)
- Works on all in-viewport links

## Customization

### Changing Preload Intensity

You can customize when links are preloaded using the `data-instant-intensity` attribute on the `<body>` tag:

#### Intensity Options

**Desktop Hover Options:**

```html
<!-- Default: Start preloading after 65ms hover -->
<body>

<!-- Aggressive: Start preloading immediately on hover -->
<body data-instant-intensity="0">

<!-- Conservative: Start preloading after 200ms hover -->
<body data-instant-intensity="200">
```

**Mousedown Only:**

```html
<!-- Only preload on mousedown, no hover preloading -->
<body data-instant-intensity="mousedown-only">
```

**Viewport Options:**

```html
<!-- Preload visible links on small screens (viewport < 450,000 pixels²) -->
<body data-instant-intensity="viewport">

<!-- Preload all visible links regardless of viewport size -->
<body data-instant-intensity="viewport-all">
```

### Programmatic Configuration

You can set intensity via PHP in your theme:

```php
add_action( 'wp_body_open', function() {
    echo '<script>document.body.dataset.instantIntensity = "mousedown-only";</script>';
}, 1 );
```

Or modify the body class:

```php
add_filter( 'body_class', function( $classes ) {
    // This doesn't work directly, but you can use this approach:
    add_action( 'wp_footer', function() {
        echo '<script>document.body.setAttribute("data-instant-intensity", "viewport");</script>';
    }, 1 );
    return $classes;
} );
```

### Excluding Specific Links

#### Exclude Single Link

Add `data-no-instant` attribute to any link you want to exclude:

```html
<a href="/login" data-no-instant>Login</a>
<a href="/logout" data-no-instant>Logout</a>
```

#### Exclude via PHP

```php
add_filter( 'the_content', function( $content ) {
    // Add data-no-instant to specific links
    $content = str_replace( 
        'href="/checkout"', 
        'href="/checkout" data-no-instant', 
        $content 
    );
    return $content;
} );
```

### Allow External Links

By default, external links are not preloaded. To enable:

```html
<body data-instant-allow-external-links>
```

Or via PHP:

```php
add_action( 'wp_body_open', function() {
    echo '<script>document.body.dataset.instantAllowExternalLinks = "";</script>';
}, 1 );
```

### Allow Query Strings

By default, URLs with query strings are not preloaded (except when specifically whitelisted). To enable:

```html
<body data-instant-allow-query-string>
```

### Whitelist Mode

Only preload links with `data-instant` attribute:

```html
<body data-instant-whitelist>
```

Then mark specific links for preloading:

```html
<a href="/product" data-instant>View Product</a>
```

## Advanced Configuration

### Custom Mousedown Shortcut

Enable both hover preloading and mousedown navigation:

```html
<body data-instant-mousedown-shortcut>
```

This creates the fastest possible navigation by:
1. Preloading on hover
2. Triggering navigation on mousedown (before click)

### Vary Accept Header

For sites that need it (like Shopify):

```html
<body data-instant-vary-accept>
```

## Integration Examples

### WooCommerce Integration

```php
add_action( 'wp_footer', function() {
    if ( function_exists( 'is_woocommerce' ) ) {
        ?>
        <script>
        // Set intensity for shop pages
        if ( document.body.classList.contains('woocommerce') ) {
            document.body.dataset.instantIntensity = 'mousedown-only';
        }
        </script>
        <?php
    }
} );

// Exclude cart and checkout
add_filter( 'woocommerce_get_cart_url', function( $url ) {
    // This would need to be applied to the anchor tag itself
    return $url;
} );
```

### Contact Form 7 Integration

```php
// Exclude Contact Form 7 forms from preloading
add_filter( 'the_content', function( $content ) {
    if ( has_shortcode( $content, 'contact-form-7' ) ) {
        // Disable instant.page on pages with forms
        add_action( 'wp_footer', function() {
            echo '<script>document.body.dataset.noInstant = "";</script>';
        }, 1 );
    }
    return $content;
} );
```

### Blog Post Navigation

```php
// Aggressive preloading for blog navigation
add_action( 'wp_footer', function() {
    if ( is_single() ) {
        ?>
        <script>
        // Preload immediately on hover for blog posts
        document.body.dataset.instantIntensity = '0';
        </script>
        <?php
    }
} );
```

## Performance Considerations

### Best Practices

1. **Exclude Form Pages**: Don't preload pages with forms to avoid losing user input
2. **Exclude Authentication**: Login/logout should not be preloaded
3. **Monitor Server Load**: Instant.page may increase server requests slightly
4. **Test on Real Devices**: Mobile behavior differs from desktop

### When to Use Viewport Preloading

Viewport preloading is ideal for:
- Mobile-first websites
- Sites with slower servers
- High-traffic sites wanting maximum conversions

```php
add_action( 'wp_footer', function() {
    if ( wp_is_mobile() ) {
        ?>
        <script>
        document.body.dataset.instantIntensity = 'viewport';
        </script>
        <?php
    }
}, 1 );
```

### Disable on Specific Pages

```php
add_action( 'wp_enqueue_scripts', function() {
    // Disable on checkout or cart
    if ( is_page( 'checkout' ) || is_cart() ) {
        wp_dequeue_script( 'pageflash-instantpage' );
    }
    
    // Disable on user account pages
    if ( is_account_page() ) {
        wp_dequeue_script( 'pageflash-instantpage' );
    }
}, 999 );
```

## Technical Details

### Browser Compatibility

Instant.page uses ES6 modules and modern browser features:

Supported browsers:
- Chrome 61+
- Firefox 60+
- Safari 11+
- Edge 16+

The script uses `type="module"` which means older browsers will simply ignore it (graceful degradation).

### What Gets Preloaded

**Only the HTML** of the target page is preloaded. CSS, JavaScript, images, and other resources are NOT preloaded until the user actually navigates to the page.

This keeps bandwidth usage minimal while still providing the instant feel.

### Script Loading

The instant.page script is loaded as a module:
```html
<script src="instantpage.js" type="module"></script>
```

It automatically initializes and starts working immediately.

## Hook Reference

### Actions

#### Enqueuing Script

The script is enqueued through standard WordPress hooks:

```php
add_action( 'wp_enqueue_scripts', 'pageflash_instantpage_assets' );
```

### Filters

#### `script_loader_tag`

PageFlash uses this filter to add `type="module"` to the instant.page script:

```php
add_filter( 'script_loader_tag', 'pageflash_instantpage_script_loader_tag', 10, 2 );
```

### Constants Used

- `PAGEFLASH_VERSION` - Plugin version for cache busting
- `PAGEFLASH_ASSETS_URL` - URL to assets directory where instant.page script is located

### HTML Data Attributes

All configuration is done through `data-*` attributes on the `<body>` tag or individual links:

**Body Attributes:**
- `data-instant-intensity` - Controls when preloading occurs
- `data-instant-allow-external-links` - Allow external link preloading
- `data-instant-allow-query-string` - Allow URLs with query strings
- `data-instant-whitelist` - Only preload explicitly marked links
- `data-instant-mousedown-shortcut` - Enable mousedown navigation
- `data-instant-vary-accept` - Add Vary: Accept header support

**Link Attributes:**
- `data-instant` - Mark link for preloading (in whitelist mode)
- `data-no-instant` - Exclude specific link from preloading

## Troubleshooting

### Instant.page Not Working?

1. **Check if script is loaded**: View page source and search for `pageflash-instantpage` or `instant.page`
2. **Verify browser support**: Make sure you're using a modern browser that supports ES6 modules
3. **Check console**: Look for any JavaScript errors
4. **Test hover behavior**: Hover over a link and check Network tab for prefetch requests

### Forms Not Working?

If form submissions are breaking:

```php
add_action( 'wp_footer', function() {
    ?>
    <script>
    // Disable instant.page on pages with forms
    if (document.querySelector('form')) {
        document.body.dataset.noInstant = '';
    }
    </script>
    <?php
} );
```

### Too Many Requests?

If you're seeing too many prefetch requests:

```php
add_action( 'wp_footer', function() {
    ?>
    <script>
    // Use more conservative intensity
    document.body.dataset.instantIntensity = 'mousedown-only';
    </script>
    <?php
}, 1 );
```

## Comparison: Instant.page vs Quicklink

| Feature | Instant.page | Quicklink |
|---------|--------------|-----------|
| **Trigger** | Hover/Touch (just-in-time) | Viewport (idle time) |
| **Timing** | Right before click | When link becomes visible |
| **Requests** | Fewer, more targeted | More links, less urgent |
| **Best For** | Conversion-focused sites | Content-heavy sites |
| **Configuration** | HTML attributes | JavaScript config |
| **Size** | 1KB | <1KB |

### Which One Should I Use?

**Use Instant.page when:**
- You want maximum conversion rate improvement
- You have a high-value action (purchase, signup)
- Mobile performance is critical
- You want minimal server impact

**Use Quicklink when:**
- You have a content-heavy site (blog, news)
- Users browse multiple pages per session
- You want to prefetch many links at once
- You need more granular control via JS

**Use Both when:**
- You want the best of both worlds
- Your server can handle the additional requests
- You have different needs for different page types

PageFlash includes both by default, but you can disable either one individually if needed.

## Further Reading

- [Instant.page Official Website](https://instant.page/)
- [Instant.page Technical Details](https://instant.page/tech)
- [Instant.page GitHub Repository](https://github.com/instantpage/instant.page)

## Support

For PageFlash-specific Instant.page issues, please visit the [PageFlash support forum](#) or [GitHub repository](#).
