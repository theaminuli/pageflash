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

= 2.1.0 =
* Add PR #129 reference to changelog
* Fixed: Add Icomoon icon font stylesheet for admin settings page. (#129)
* Update version to 2.0.0 in CHANGELOG
* Merge branch 'trunk' into development
* Add PR #126 reference to changelog
*  Update README and readme.txt with new features and links (#126)
* Add PR #125 reference to changelog
* Update ignore and export rules for project files (#125)
* Add PR #123 reference to changelog
* Feature/license management (#123)
* Add PR #120 reference to changelog
*  Add i18n support to admin UI and PHP config labels (#120)
* Add PR #122 reference to changelog
* Update GitHub Actions to latest major versions (#122)
* Add PR #119 reference to changelog
* Add: Namespaces to include 'TheAminul' prefix and add function naming guidelines (#119)
* Merge branch 'trunk' into development
* Add PR #118 reference to changelog
* Add or Update Instructions for Agents and Tools (#118)
* Fixed Function in ActionLinks.php (#117)
* Add PR #116 reference to changelog
* Refactor code style and add Stylelint config (#116)
* Refactor autoloader for improved modularity and clarity
* Add PR #115 reference to changelog
* Fix autoloader function prefix: rename exoole_autoloader to pageflash_autoloader and format issue ( #115 )
* Refactor codebase for consistency and WP standards
* Refactor admin codebase for consistent style
* Rename autoloader function for consistency
* Rename exoole_autoloader to pageflash_autoloader for naming consistency
* Initial plan
* Add PR #114 reference to changelog
* Remove emoji from user engagement text in README (#114)
* Add PR #113 reference to changelog
* Update PR workflow trigger and permissions (#113)
* Add PR #111 reference to changelog
* Revise API and architecture documentation for clarity (#111)
* Fix indentation and comment in Landmark.php
* Add PR #104 reference to changelog
* Add global helper class for content sanitization (#104)
* Comment out instantpage feature registration
* Remove unused General import and instantiation
* Add PR #103 reference to changelog
* Merge pull request #103 from theaminuli/aminul/dev
* Refactor Landmark API to use slugs and add new features #72
* Clean up changelog by removing old entries
* Add PR #102 reference to changelog
* Merge pull request #102 from theaminuli/aminul/dev
* Remove auto-format PR title workflow
* Add PR #101 reference to changelog
* Merge pull request #101 from theaminuli/aminul/dev
* Clean up FAQ section formatting in readme.txt
* Add PR #100 reference to changelog
* Merge pull request #100 from theaminuli/aminul/dev
* Improve PR title formatting after merge
* Add PR #99 reference to changelog
* Merge pull request #99 from theaminuli/aminul/dev
* Update changelog formatting in readme.txt
* Add PR #98 reference to changelog
* Merge pull request #98 from theaminuli/aminul/dev
* Update workflow to format merged PR titles
* Add PR #97 reference to changelog
* Merge pull request #97 from theaminuli/aminul/dev
* Remove Helper.php global helper class
* Add PR #96 reference to changelog
* Merge pull request #96 from theaminuli/aminul/dev
* Remove extra space in README marketing text
* Update PR title formatting in workflow
* Fix spacing in README section list
* Add checkout step to PR title formatting workflow
* Merge branch 'development' into aminul/dev
* Update CSS filter and clean up Compatibility docblocks
* Add PR #95 reference to changelog
* Merge pull request #95 from theaminuli/auto-format-pr-title
* Add workflow to auto-format PR titles
* Add PR #93 reference to changelog
* Merge pull request #93 from theaminuli/copilot/remove-sub-entry-pr-reference
* Remove sub-entry file listings from changelog workflow and CHANGELOG.md
* Merge branch 'development' into copilot/remove-sub-entry-pr-reference
* Add PR #94 reference to changelog
* Merge pull request #94 from theaminuli/aminul/dev
* Remove PR references from sub-entries in changelog workflow and clean up existing CHANGELOG.md
* Update changelog entry formatting in workflow
* Initial plan
* Add PR #91 reference to changelog
* Merge pull request #91 from theaminuli/copilot/remove-automatic-readme-updates
* Update changelog workflow to put PR reference number at front
* Initial plan
* Add PR #87 reference to changelog
* Merge pull request #87 from theaminuli/copilot/fix-issue-in-previous-pr
* Enhance PR description template with clearer guidance for What, Why, How, and Testing sections
* Initial plan
* Add PR #84 reference to changelog
* Merge pull request #84 from theaminuli/aminul/dev
* Merge pull request #85 from theaminuli/copilot/sub-pr-84
* Initial plan
* Update changelog formatting and readme entries
* Add PR #83 reference to changelog
* Merge pull request #83 from theaminuli/copilot/review-pull-request-feedback
* Add Code Review Checklist to copilot-instructions.md
* Improve HTML comment regex and error messaging
* Fix code review feedback: regex pattern, file count, and Copilot review mechanism
* Add GitHub Actions workflow for Copilot PR description and review
* Initial plan
* Add PR #80 reference to changelog
* Merge pull request #80 from theaminuli/copilot/update-readme-version-number-again
* Add file tracking with PR references to changelog entries
* Improve x.y.z version insertion logic in changelog workflow
* Add PR #81 reference to changelog
* Add automatic x.y.z version with date for changelog entries
* Merge pull request #81 from theaminuli/aminul/dev
* Add pull request template
* Remove readme.txt automatic updates from changelog workflow
* Initial plan
* Add PR #76 reference to changelog
* Merge pull request #76 from theaminuli/aminul/dev
* Update license, add react-router, and init compatibility
* Add PR #75 reference to changelog
* Merge pull request #75 from theaminuli/copilot/add-pr-reference-tracking
* Merge branch 'development' into copilot/add-pr-reference-tracking
* Resolve conflicts with development branch
* Add PR reference tracking to changelog workflow
* Initial plan
* Merge pull request #73 from theaminuli/copilot/update-changelog-for-pr
* Fix GitHub Actions changelog workflow for updating readme.txt after PR merge
* Initial plan
* Merge pull request #55 from theaminuli/aminul/dev
* Refactor admin dashboard to use router and landmarks
* Refactor admin SCSS structure and styles
* Refactor API Documentation CLAUDE
* Refactor API Documentation AI
* Enhanced API documentation for clarity and added examples for GET and PUT requests.
* - Introduced Compatibility class for handling third-party plugin compatibility
* - Added architecture documentation for version 1.2.0
* Merge branch 'development' into aminul/dev
* Merge pull request #69 from theaminuli/copilot/develop-wordpress-rest-api
* Fix namespace usage for NoReload classes in Landmark class
* Update changelog after merging PR #68
* Merge pull request #68 from theaminuli/copilot/develop-wordpress-rest-api
* Fix comment formatting and clarify InstantPage implementation in Landmark class
* Refactor constructor and method formatting in Landmark class for consistency
* Remove legacy POST endpoint for landmarks and enhance landmark retrieval response
* Refactor landmark ID generation to use wp_unique_id for consistency
* Implement wp_unique_id and dynamic ID generation for landmarks
* Move API docs to dev-docs and fix landmark feature initialization
* Fix API documentation - correct date and clarify nonce usage
* Add comprehensive REST API documentation
* Add REST API endpoints for landmarks with nonce verification
* Initial plan
* Feat: Implement Admin Dashboard with routing and context management
* Add admin menu and enhance asset management in PageFlash plugin

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
