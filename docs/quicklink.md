# Quicklink - Instant Next-Page Navigation

## Overview

Quicklink is a powerful prefetching solution that makes your website's next-page navigations instant by intelligently prefetching links in the user's viewport during idle time. This feature is automatically enabled in PageFlash to provide a blazing-fast browsing experience for your visitors.

## What is Quicklink?

Quicklink prefetches URLs for links that are visible in the user's viewport during browser idle time. This means when users see a link on their screen, the page is already being loaded in the background. When they click on it, the page appears instantly because it's already prefetched.

**Key Statistics:**
- NewEgg reported a **50% increase in conversions** and **4x faster page transitions** after implementing Quicklink
- Trusted by major brands including Ray-Ban, Oakley, Newegg, and Hashnode
- Library size: **< 1KB minified/gzipped**

## How It Works

1. **Viewport Detection**: Quicklink observes all links visible in the user's viewport using the Intersection Observer API
2. **Idle Time Prefetching**: During browser idle time (when the user is not actively interacting with the page), Quicklink prefetches the HTML of visible links
3. **Smart Resource Management**: Uses `requestIdleCallback` to ensure prefetching doesn't interfere with user interactions or critical rendering tasks
4. **Instant Navigation**: When the user clicks on a prefetched link, the page loads instantly without network delay

## Benefits

### 1. **Improved User Experience**
- Pages load instantly when users click on links
- Smoother navigation throughout your website
- Reduced perceived latency

### 2. **Higher Conversion Rates**
- Research shows that removing 100ms of latency can improve sales by 1%
- Faster page transitions keep users engaged
- Reduced bounce rates due to faster loading

### 3. **Intelligent Prefetching**
- Only prefetches links that are visible in the viewport
- Respects user's data saver mode
- Uses browser idle time to avoid impacting performance
- Automatically excludes admin URLs, login pages, and feeds

### 4. **Lightweight & Fast**
- Minimal impact on page load time
- Asynchronous loading ensures no blocking
- Small footprint (< 1KB)

## How to Use

Quicklink is **automatically enabled** in PageFlash. No additional configuration is required. Once you activate the PageFlash plugin, Quicklink starts working immediately on your frontend pages.

### Default Behavior

By default, PageFlash Quicklink:
- Prefetches links visible in the viewport
- Uses a 2-second timeout with `requestIdleCallback`
- Respects the same origin (your website domain)
- Automatically ignores:
  - WordPress admin URLs
  - Login/logout pages
  - Feed URLs
  - Current page anchor links
  - Content URLs (uploads, plugins, themes)

## Customization

### Filtering Ignored URLs

You can customize which URLs should be ignored by Quicklink using WordPress filters:

```php
// Add custom URL patterns to ignore
add_filter( 'wp_pageflash_quicklink_ignore_urls', function( $ignores ) {
    // Add custom patterns
    $ignores[] = '/checkout/';
    $ignores[] = '/my-account/';
    $ignores[] = preg_quote( '/payment/', '/' );
    
    return $ignores;
} );
```

### Customizing Quicklink Settings

You can modify all Quicklink configuration options using the main filter:

```php
add_filter( 'wp_pageflash_quicklink', function( $settings ) {
    // Customize timeout
    $settings['timeout'] = 3000; // 3 seconds
    
    // Specify a container to observe
    $settings['el'] = '#main-content';
    
    // Add additional origins to allow
    $settings['origins'][] = 'https://cdn.example.com';
    
    // Add more ignore patterns
    $settings['ignores'][] = '/private/';
    
    return $settings;
} );
```

## Advanced Configuration Examples

### Limit Prefetching to Specific Container

If you want to prefetch only links within a specific area of your site:

```php
add_filter( 'wp_pageflash_quicklink', function( $settings ) {
    // Only prefetch links inside the main content area
    $settings['el'] = '#content';
    
    return $settings;
} );
```

### Allow External Domain Prefetching

To prefetch links from specific external domains:

```php
add_filter( 'wp_pageflash_quicklink', function( $settings ) {
    // Add external domains you trust
    $settings['origins'][] = 'https://cdn.yoursite.com';
    $settings['origins'][] = 'https://shop.yoursite.com';
    
    return $settings;
} );
```

### Exclude Specific Pages or Post Types

```php
add_filter( 'wp_pageflash_quicklink_ignore_urls', function( $ignores ) {
    // Ignore WooCommerce cart and checkout
    if ( function_exists( 'is_woocommerce' ) ) {
        $ignores[] = preg_quote( '/cart/', '/' );
        $ignores[] = preg_quote( '/checkout/', '/' );
    }
    
    // Ignore all URLs containing 'download'
    $ignores[] = 'download';
    
    // Ignore specific post type archives
    $ignores[] = preg_quote( get_post_type_archive_link( 'product' ), '/' );
    
    return $ignores;
} );
```

## Performance Considerations

### Best Practices

1. **Don't Prefetch Dynamic Content**: Avoid prefetching pages with user-specific content or forms
2. **Exclude Authentication Pages**: Login, logout, and account pages are automatically excluded
3. **Be Mindful of Server Load**: Quicklink respects idle time, but high-traffic sites may see increased server requests
4. **Monitor Analytics**: Track how prefetching affects your metrics

### When to Disable Quicklink

You might want to disable Quicklink on specific pages:

```php
add_action( 'wp_enqueue_scripts', function() {
    // Disable on specific pages
    if ( is_page( 'contact' ) || is_singular( 'product' ) ) {
        wp_dequeue_script( 'pageflash-frontend' );
        wp_dequeue_script( 'pageflash-quicklink' );
    }
}, 999 );
```

## Technical Details

### Scripts Loaded

PageFlash loads two scripts for Quicklink functionality:
1. **pageflash-quicklink**: The Quicklink library (`quicklink.umd.js`)
2. **pageflash-frontend**: Custom initialization script with WordPress-specific configuration

Both scripts are loaded asynchronously to prevent blocking page rendering.

### Browser Compatibility

Quicklink uses modern browser APIs:
- **Intersection Observer API**: For viewport detection
- **requestIdleCallback**: For idle time detection
- Gracefully degrades in older browsers

Supported browsers:
- Chrome 51+
- Firefox 55+
- Safari 12.1+
- Edge 15+

## Hook Reference

### Filters

#### `wp_pageflash_quicklink`

Filters the complete Quicklink configuration settings.

**Parameters:**
- `$settings` (array) - Array of Quicklink configuration options

**Available Settings:**
- `el` (string) - CSS selector for the container to observe. Default: `''` (observes entire body)
- `urls` (array) - Static array of URLs to prefetch
- `timeout` (int) - Timeout in milliseconds before prefetching. Default: `2000`
- `timeoutFn` (string) - Timeout function name. Default: `'requestIdleCallback'`
- `priority` (bool) - Use high-priority fetch. Default: `false`
- `origins` (array) - Allowed origins for prefetching
- `ignores` (array) - Regular expression patterns to ignore

**Example:**
```php
add_filter( 'wp_pageflash_quicklink', function( $settings ) {
    $settings['timeout'] = 1500;
    $settings['el'] = '#primary';
    return $settings;
} );
```

#### `wp_pageflash_quicklink_ignore_urls`

Filters the array of URL patterns to ignore during prefetching.

**Parameters:**
- `$ignores` (array) - Array of URL patterns (strings or regex patterns)

**Example:**
```php
add_filter( 'wp_pageflash_quicklink_ignore_urls', function( $ignores ) {
    $ignores[] = '/private-page/';
    $ignores[] = preg_quote( '/members/', '/' );
    return $ignores;
} );
```

### Actions

PageFlash Quicklink uses standard WordPress actions for script registration and enqueuing:

- `wp_default_scripts` - Registers Quicklink scripts early in WordPress runtime
- `wp_enqueue_scripts` - Enqueues Quicklink scripts and localizes settings

### Constants Used

- `PAGEFLASH_VERSION` - Plugin version for cache busting
- `PAGEFLASH_PATH` - Plugin directory path
- `PAGEFLASH_URL` - Plugin directory URL
- `PAGEFLASH_ASSETS_URL` - Plugin assets directory URL

## Troubleshooting

### Quicklink Not Working?

1. **Check if scripts are loaded**: View page source and search for `pageflash-quicklink`
2. **Check browser console**: Look for any JavaScript errors
3. **Verify WordPress version**: Ensure you're running WordPress 5.0 or higher
4. **Check for conflicts**: Temporarily disable other performance plugins

### Excessive Prefetching?

If you notice too many prefetch requests:

```php
add_filter( 'wp_pageflash_quicklink', function( $settings ) {
    // Increase timeout to reduce prefetch frequency
    $settings['timeout'] = 5000; // 5 seconds
    
    // Or limit to a specific container with fewer links
    $settings['el'] = '.post-navigation';
    
    return $settings;
} );
```

## Further Reading

- [Quicklink Official Documentation](https://getquick.link/)
- [Quicklink GitHub Repository](https://github.com/GoogleChromeLabs/quicklink)
- [Google Chrome Labs Blog Post](https://web.dev/quicklink/)

## Support

For PageFlash-specific Quicklink issues, please visit the [PageFlash support forum](#) or [GitHub repository](#).
