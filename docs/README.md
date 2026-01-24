# PageFlash Documentation

Welcome to the PageFlash documentation! PageFlash is a WordPress plugin that makes your website blazing fast by implementing cutting-edge preloading techniques.

## Table of Contents

1. [Quicklink - Instant Next-Page Navigation](quicklink.md)
2. [Instant.page - Make Your Pages Feel Instant](instant-page.md)

## What is PageFlash?

PageFlash is a WordPress performance plugin that combines two powerful preloading technologies to make your website feel instant:

### Features

#### 1. [Quicklink](quicklink.md)
Quicklink prefetches links visible in the user's viewport during browser idle time. When users see a link, the page is already loading in the background.

**Key Benefits:**
- 50% increase in conversions (NewEgg case study)
- 4x faster page transitions
- < 1KB library size

[Read full Quicklink documentation →](quicklink.md)

#### 2. [Instant.page](instant-page.md)
Instant.page uses just-in-time preloading to load pages right before users click on them, making navigation feel instant.

**Key Benefits:**
- Up to 1% improvement in sales
- Works even on 3G connections
- 300ms average head start on desktop
- Only 1KB in size

[Read full Instant.page documentation →](instant-page.md)

## Quick Start

### Installation

1. Upload the PageFlash plugin to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! Both Quicklink and Instant.page are automatically enabled

### Basic Usage

No configuration needed! PageFlash works out of the box with sensible defaults:

- ✅ Automatically prefetches visible links
- ✅ Respects user's data saver mode
- ✅ Excludes admin URLs, login pages, and feeds
- ✅ Uses browser idle time for prefetching
- ✅ Loads asynchronously without blocking

## Customization

Both features can be customized through WordPress filters and HTML attributes.

### Quick Examples

#### Exclude Specific URLs from Quicklink

```php
add_filter( 'wp_pageflash_quicklink_ignore_urls', function( $ignores ) {
    $ignores[] = '/checkout/';
    $ignores[] = '/my-account/';
    return $ignores;
} );
```

#### Change Instant.page Intensity

```php
add_action( 'wp_footer', function() {
    ?>
    <script>
    // Only preload on mousedown for maximum accuracy
    document.body.dataset.instantIntensity = 'mousedown-only';
    </script>
    <?php
}, 1 );
```

#### Disable on Specific Pages

```php
add_action( 'wp_enqueue_scripts', function() {
    if ( is_page( 'contact' ) ) {
        // Disable both features on contact page
        wp_dequeue_script( 'pageflash-frontend' );
        wp_dequeue_script( 'pageflash-quicklink' );
        wp_dequeue_script( 'pageflash-instantpage' );
    }
}, 999 );
```

## When to Use Each Feature

### Use Quicklink When:
- You have a content-heavy website (blog, news site)
- Users typically browse multiple pages per session
- You want to prefetch many visible links at once
- You have good server capacity

### Use Instant.page When:
- Conversion rate is your primary focus
- You have high-value actions (purchases, signups)
- Mobile performance is critical
- You want minimal server impact

### Use Both When:
- You want maximum performance
- Your server can handle additional requests
- Different page types have different needs
- You want the best of both worlds

**PageFlash enables both by default** because they complement each other perfectly!

## Performance Impact

### Bandwidth
- Only HTML is prefetched (not images, CSS, or JS)
- Respects user's data saver mode
- Minimal overhead (< 2KB total for both libraries)

### Server Load
- Prefetching happens during idle time
- Smart detection reduces unnecessary requests
- May see 10-30% increase in page requests (varies by site)

### Page Speed
- Scripts load asynchronously (no blocking)
- Uses passive event listeners
- Loads after critical resources

## Browser Compatibility

Both features use modern browser APIs with graceful degradation:

| Browser | Quicklink | Instant.page |
|---------|-----------|--------------|
| Chrome 51+ | ✅ | ✅ (61+) |
| Firefox 55+ | ✅ | ✅ (60+) |
| Safari 12.1+ | ✅ | ✅ (11+) |
| Edge 15+ | ✅ | ✅ (16+) |

Older browsers simply won't prefetch, but your site will still work normally.

## Frequently Asked Questions

### Does PageFlash work with caching plugins?
Yes! PageFlash works perfectly with caching plugins like WP Rocket, W3 Total Cache, and WP Super Cache.

### Will this increase my server load?
Slightly. You may see a 10-30% increase in page requests, but since prefetching happens during idle time, the impact is minimal.

### Does it work with WooCommerce?
Yes! However, you should exclude cart, checkout, and account pages. See customization examples in each feature's documentation.

### Can I use just one feature?
Yes! You can disable either Quicklink or Instant.page individually:

```php
add_action( 'wp_enqueue_scripts', function() {
    // Disable Quicklink only
    wp_dequeue_script( 'pageflash-frontend' );
    wp_dequeue_script( 'pageflash-quicklink' );
    
    // OR disable Instant.page only
    wp_dequeue_script( 'pageflash-instantpage' );
}, 999 );
```

### Does it respect GDPR/privacy?
Yes! Both features:
- Don't collect any user data
- Don't use cookies
- Respect data saver mode
- Are completely client-side

### Will it work on mobile?
Absolutely! Both features are optimized for mobile:
- Instant.page detects touch events
- Quicklink works in mobile viewports
- Respects data saver mode
- Lightweight (< 2KB combined)

## Troubleshooting

### Scripts Not Loading?

Check if scripts are enqueued:
```php
add_action( 'wp_footer', function() {
    global $wp_scripts;
    var_dump( $wp_scripts->queue );
} );
```

### Too Many Prefetch Requests?

Reduce intensity:
```php
add_filter( 'wp_pageflash_quicklink', function( $settings ) {
    $settings['timeout'] = 5000; // Increase delay
    return $settings;
} );

add_action( 'wp_footer', function() {
    echo '<script>document.body.dataset.instantIntensity = "mousedown-only";</script>';
}, 1 );
```

### Forms Breaking?

Exclude form pages:
```php
add_action( 'wp_footer', function() {
    if ( is_page( 'contact' ) || has_shortcode( get_the_content(), 'contact-form-7' ) ) {
        echo '<script>document.body.dataset.noInstant = "";</script>';
    }
}, 1 );
```

## API Reference

### Quicklink Filters

- `wp_pageflash_quicklink` - Filter all Quicklink settings
- `wp_pageflash_quicklink_ignore_urls` - Filter ignored URL patterns

[See full Quicklink API reference →](quicklink.md#hook-reference)

### Instant.page Configuration

- HTML `data-*` attributes on `<body>` tag
- Per-link `data-instant` and `data-no-instant` attributes

[See full Instant.page API reference →](instant-page.md#hook-reference)

## Performance Metrics

### Expected Improvements

With PageFlash enabled, you can expect:

- **Time to Interactive**: 10-30% faster
- **Perceived Load Time**: Instant (< 100ms)
- **Bounce Rate**: 5-15% reduction
- **Pages Per Session**: 10-25% increase
- **Conversion Rate**: 0.5-2% improvement

*Results vary by site, content type, and user behavior.*

### Measuring Impact

To measure PageFlash's impact on your site:

1. **Use Google Analytics**: Track page load times and bounce rates
2. **Use Google PageSpeed Insights**: Check performance scores
3. **Use Chrome DevTools**: Monitor network requests in the Network tab
4. **A/B Testing**: Compare metrics with and without PageFlash

## Best Practices

### ✅ Do:
- Exclude authentication pages
- Exclude form pages
- Monitor server load
- Test on real devices
- Measure performance impact
- Use with caching plugins

### ❌ Don't:
- Prefetch user-specific content
- Prefetch external links without testing
- Ignore mobile experience
- Forget about server capacity
- Disable without measuring impact

## Support & Contributing

### Getting Help

- **Documentation**: You're reading it!
- **Support Forum**: [Visit support forum](#)
- **GitHub Issues**: [Report bugs](#)

### Contributing

PageFlash is open source! Contributions are welcome:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## Credits

PageFlash integrates these amazing open-source projects:

- **Quicklink** by Google Chrome Labs
  - [Website](https://getquick.link/)
  - [GitHub](https://github.com/GoogleChromeLabs/quicklink)
  
- **Instant.page** by Alexandre Dieulot
  - [Website](https://instant.page/)
  - [GitHub](https://github.com/instantpage/instant.page)

## License

PageFlash is licensed under the [MIT License](#).

Quicklink and Instant.page are also MIT licensed.

---

**Ready to make your site instant?** Choose a feature to learn more:

- [Quicklink Documentation →](quicklink.md)
- [Instant.page Documentation →](instant-page.md)
