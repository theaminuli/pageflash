=== PageFlash - Fast Page Preloading, Performance Optimization & Secure Your WordPress Site ===
Contributors: theaminuldev
Tags: preload, page-speed, optimization, performance, pageflash, speed, fast, instant-navigation, prefetch, quicklink
Requires at least: 6.1
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 2.1.0
License: GPL-3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright: © 2026 theaminul.com

Preload pages intelligently to boost site speed and enhance user experience by loading pages before users click, ensuring instant page transitions.

== Description ==
PageFlash is a powerful WordPress plugin that preloads pages intelligently to boost site speed and enhance user experience. By loading pages before users click, PageFlash ensures instant page transitions, creating a seamless and lightning-fast browsing experience for your visitors.

### Key Features:

#### Preloading:
- **Quicklink:** Experience a 50% increase in conversions and enjoy 4x faster page loading. Boost your website speed and increase user engagement.
- **InstantPage:** Uses just-in-time preloading — preloads a page right before a user clicks on it.

#### Performance Optimization:
- **Disable Dashicons:** Remove Dashicons on the frontend for non-logged-in users to improve performance.
- **Disable Embeds:** Disable the WordPress oEmbed feature to reduce unnecessary HTTP requests and improve page load times.
- **Disable Emojis:** Remove built-in WordPress emoji scripts and styles to reduce page size and improve load times.
- **Disable Heartbeat:** Control WordPress Heartbeat API everywhere or in certain areas (used for auto-saving and revision tracking) with customizable behavior and frequency settings.
- **Remove jQuery Migrate:** Remove jQuery Migrate script to improve performance on the frontend.

#### Security Enhancements:
- **Disable REST API:** Disable the WordPress REST API for non-authenticated users to enhance security and reduce unnecessary requests.
- **Remove REST API Link:** Remove REST API link tag from the front end and the REST API header link from page requests.
- **Hide WordPress Version:** Hide the WordPress version number from the site header, meta tags, and RSS feeds to improve security.
- **Disable XML-RPC:** Disable the XML-RPC feature to enhance security and reduce unnecessary requests.

For more information and documentation, visit our [plugin documentation](https://theaminul.com/pageflash/docs).

### How it works:

- **Detects links within the viewport** (using [Intersection Observer](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API))
- **Waits until the browser is idle** (using [requestIdleCallback](https://developer.mozilla.org/en-US/docs/Web/API/Window/requestIdleCallback))
- **Checks if the user isn't on a slow connection** (using `navigator.connection.effectiveType`) or has data-saver enabled (using `navigator.connection.saveData`)
- **Prefetches URLs to the links** (using [`<link rel=prefetch>`](https://www.w3.org/TR/resource-hints/#prefetch) or XHR). Provides some control over the request priority (can switch to `fetch()` if supported).

If you are a developer, we encourage you to follow along or [contribute](https://github.com/theaminuldev/pageflash) to the development of this plugin [on GitHub](https://github.com/theaminuldev/pageflash).

### Browser support:

This plugin also works perfectly on popular browsers.
- 🖥 Microsoft EDGE
- 🖥 Firefox 4+
- 🖥 Chrome
- 🖥 Opera
- 📱 Android 4+

== Installation ==

= To install the plugin via WordPress Dashboard: =

1. In your WordPress admin dashboard, go to "Plugins" and click "Add New."
2. Click "Activate."

= To install the plugin manually: =

1. Download the plugin ZIP file from the [PageFlash WordPress Plugin Page](https://wordpress.org/plugins/pageflash/).
2. Click the "Upload Plugin" button and select the ZIP file you downloaded.
3. Click "Install Now" and then "Activate."

== Frequently Asked Questions ==
= How do I configure PageFlash? =

After activation, navigate to the PageFlash settings page in your WordPress admin dashboard to enable and configure the features you want to use.

= Where should I check the plugin's features? =
- A. In Chrome's incognito mode.
- B. After logging out of the admin account.
- C. In Firefox's private browsing mode.
- D. In Safari's private browsing mode.
The best places to check the plugin's features are either in Chrome's incognito mode (Option A) or after logging out of the admin account (Option B). These methods ensure that the plugin works correctly without any interference from browser history, cookies, or admin privileges.

= Is PageFlash compatible with the latest version of WordPress? =
Yes, PageFlash is regularly tested and ensured to be compatible with the latest WordPress version.


== Changelog ==

= 2.1.0 - 2026-01-25 =
- Fixed: Add Icomoon icon font stylesheet for admin settings page.
- Fixed: Update plugin metadata for version 2.0.0.
- Fixed: Update readme with PHP requirement and formatting.

= 2.0.0 - 2026-01-24 =
* Added: InstantPage integration to enhance the Preloading module performance.
* Added: Introduced admin dashboard for better user experience.
* Added: Disabled frontend Dashicons for non-logged-in users.
* Added: Disabled WordPress oEmbed for improved page load performance.
* Added: Disabled WordPress emojis to improve load time.
* Added: Removed WordPress version info for better security.
* Added: Disabled jquery-migrate.min.js to improve performance.
* Added: Disabled XML-RPC to reduce unnecessary requests.
* Added: Disabled WordPress REST API for non-authenticated users.
* Added: Removed REST API link tag and header from the frontend.
* Added: Disabled WordPress Heartbeat globally or in selected areas.

= 1.2.0 - 2025-05-14 =
* chore: Add initial configuration files 
* Improved: Update asset management
* Improved: Update security policy and PHPCS configuration
* Improved: Update README and CONTRIBUTING guidelines
* Improved: WP coding standards and code quality ensure
* Updated: Update GPL license to version 3.0

= 1.1.0 - 2024-12-20 =
* Fixed: Security vulnerability reporting guidelines.
* Added: Supply chain attack prevention.

= 1.0.1 - 2024-12-19 =
* Fixed: Issue with `validateElement` function in `pageflash-frontend`.
* Improved: Added validation for `timeoutFn` in `buildListenerOptions` function.
* Added: Confusion Clear FAQ plugin for better understanding.

= 1.0.0 - 2024-12-18 =
* Initial release.
* Added: `pageflash-frontend.js` for frontend functionality.
* Added: MetaBox class for admin settings.
* Added: `PAGEFLASH_ASSETS_PATH` constant for asset management.
