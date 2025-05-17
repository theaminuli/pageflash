=== PageFlash - Fast and Efficient Headless Browser WordPress Plugin ===
Contributors: theaminuldev
Tags: headless browser, quicklink, performance, speculation rules
Requires at least: 6.0
Tested up to: 6.8.1
Stable tag: 1.2.0
License: GPL-3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright: © 2023 theaminul.com

By using PageFlash, an active plugin, you'll experience a 50% increase in conversions and enjoy 4x faster page loading. ⚡️ Boost your website's speed, increase user engagement 💬, and supercharge your online presence 🚀. - NewEgg

== Description ==
PageFlash is a powerful headless browser WordPress plugin designed to provide you with a fast and efficient web browsing experience within your WordPress site. Say goodbye to page reloads and enjoy seamless navigation through web content with this plugin. Harness the speed and agility of PageFlash for your WordPress website.

### Key Features:
- **Lightning-Fast Browsing:** PageFlash lives up to its name, offering rapid page loading and navigation without the need for tedious page refreshes.
- **Smooth Script Execution:** Execute scripts and interact with web pages in a fluid and continuous manner. With PageFlash, you'll experience uninterrupted script execution, ensuring your web applications run seamlessly.
- **Prefetches:** PageFlash incorporates advanced prefetching technology to speed up your web browsing. It anticipates and loads pages in the background, reducing loading times and providing a smoother browsing experience.
- **No More Reloads:** Say goodbye to unnecessary page reloads with PageFlash, and enjoy uninterrupted web exploration. PageFlash ensures a frustration-free web experience by eliminating the need for page reloads, providing you with a streamlined and seamless browsing experience.

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

= How do I enable PageFlash for a specific post or page? =

After activation, go to the post or page where you want to enable PageFlash's headless browsing features. In the editor, look for the PageFlash settings panel to configure your preferences.

= Where should I check the plugin's features? =

- A. In Chrome's incognito mode.
- B. After logging out of the admin account.
- C. In Firefox's private browsing mode.
- D. In Safari's private browsing mode.
The best places to check the plugin's features are either in Chrome's incognito mode (Option A) or after logging out of the admin account (Option B). These methods ensure that the plugin works correctly without any interference from browser history, cookies, or admin privileges.

= Is PageFlash compatible with the latest version of WordPress? =

Yes, PageFlash is regularly tested and ensured to be compatible with the latest WordPress version.


== Screenshots ==
1. [Screenshot 1](https://github.com/theaminuldev/pageflash/src/images/screenshot.png): Describe the screenshot here.

== Changelog ==

= 1.2.0 =
* Merge pull request #50 from theaminuli/development
* Refactor GitHub Actions workflow for release process
* Merge pull request #49 from theaminuli/release/2025-05-14/minor-release
* Merge branch 'trunk' into release/2025-05-14/minor-release
* Remove 'speed' tag from readme.txt
* Merge pull request #48 from theaminuli/development
* Update version to 1.1.0 and add @actions/core as a dependency
* Merge branch 'trunk' into release/2025-05-14/minor-release
* Merge pull request #47 from theaminuli/development
* Revert version number to 1.1.0 in package.json, pageflash.php, and readme.txt
* Revert version number to 1.1.0 in plugin header
* Update license from GPL-3.0-or-later to GPL-2.0-or-later in package.json
* Merge branch 'release/2025-05-14/minor-release' of https://github.com/theaminuli/pageflash into release/2025-05-14/minor-release
* Fix
* Merge pull request #45 from theaminuli/release/2025-05-14/minor-release
* Fix copyright year in plugin header
* Merge branch 'development' into release/2025-05-14/minor-release
* Create package-lock.json
* Bump version to 1.2.0 and update tested up to version in plugin header
* Delete package-lock.json
* Update tested version and stable tag in readme.txt
* Merge pull request #44 from theaminuli/release/2025-05-14/minor-release
* Remove redundant comment from the Plugin class constructor
* Create deploy-to-dotorg.yml
* Merge pull request #43 from theaminuli/development
* Merge pull request #41 from theaminuli/aminul/dev
* Update changelog date for version 1.2.0 to reflect the correct release date
* Merge pull request #40 from theaminuli/aminul/dev
* Add simple-git dependency and implement version update script for automated releases
* Merge pull request #39 from theaminuli/aminul/dev
* Refactor pageflash_plugin_action_links method to remove commented-out code and improve clarity
* Update example comments in Admin and ActionLinks classes for clarity
* Update PHPCS ruleset to exclude additional comment and whitespace checks; clean up constructor formatting in Plugin class
* Refactor comments in Admin and Plugin classes for clarity and consistency
* Remove commented-out code from pageflash_plugin_action_links method
* Merge branch 'development' into aminul/dev
* Refactor Admin class: remove AdminMenu dependency and clean up Landmark classes
* Add JSON server configuration and sample data; update package scripts for development
* Merge pull request #38 from theaminuli/aminul/dev
* Remove WP Standard Lint step from GitHub Actions workflow
* Enhance GitHub Actions workflow to include PHP dependency installation and run WP Standard Lint
* Add Landmark class with constructor for PageFlash plugin initialization
* Add silence comment to Landmark.php for improved file clarity
* Merge pull request #37 from theaminuli/aminul/dev
* Refactor GitHub Actions workflow to remove PHP dependency installation and streamline linting process
* Remove silence comment from Landmark.php
* Add silence comment to Landmark.php for improved file clarity
* Merge pull request #36 from theaminuli/aminul/dev
* Update linting scripts in package.json for improved path consistency and add WPCS support
* Add silence comment to Landmark.php for improved file clarity
* Add silence comment to LandmarkList.php for improved file clarity
* Merge pull request #35 from theaminuli/aminul/dev
* Refactor code for improved readability and consistency across multiple files
* Refactor quicklink integration and remove unused files for improved codebase clarity
* Refactor package.json scripts section for improved organization and clarity
* Update package.json and pageflash.php for author information and formatting improvements
* Update package.json and pageflash.php to clarify license information and improve formatting
* Update package.json and pageflash.php for improved metadata and license clarity
* Remove quicklink package.json as it is no longer needed
* Update package.json to enhance metadata and add dependencies
* Refactor variable naming for clarity in pageflash.php
* Refactor code formatting and improve readability in multiple files
* Add echo statement to index.php for clarity
* Merge branch 'development' into aminul/dev
* Update pr-checks.yml
* Create package-lock.json
* Merge branch 'development' into aminul/dev
* Update pr-checks.yml
* Remove release workflow configuration
* Merge branch 'development' into aminul/dev
* Merge branch 'development' into aminul/dev
* Update pr-checks.yml
* Update pr-checks.yml
* Update pr-checks.yml
* Add GitHub Actions workflow for creating new release PRs
* Merge pull request #33 from theaminuli/aminul/dev
* Revert "Add GitHub Actions workflow for automated release management"
* Update PR checks workflow to conditionally install npm dependencies
* Add GitHub Actions workflow for automated release management
* Merge pull request #31 from theaminuli/aminul/dev
* Update package.json with repository details, add keywords, and specify Node.js and npm engines
* Add GitHub Actions workflow for release management and update linting checks
* Update LICENSE
* Merge pull request #30 from theaminuli/aminul/dev
* Add ESLint configuration, update composer.json exclusions, and implement GitHub Actions for linting checks
* Add PHP Compatibility Checker to composer.json for improved code standards
* Merge pull request #29 from theaminuli/aminul/dev
* Add initial implementation of PageFlash plugin with autoloading and admin features
* Update changelog after merging PR #28
* Merge pull request #28 from theaminuli/aminul/dev
* Add security vulnerability reporting guidelines to changelog
* Merge pull request #27 from theaminuli/aminul/dev
* Remove redundant header from readme.txt in changelog workflow, streamlining output formatting
* Remove obsolete changelog workflow, streamlining the repository's automation processes
* Update readme.txt
* Update changelog after merging PR #26
* Merge pull request #26 from theaminuli/aminul/dev
* Refactor changelog workflow to improve extraction and formatting of changelog entries, enhancing output structure and readability
* Update changelog after merging PR #25
* Merge pull request #25 from theaminuli/aminul/dev
* Refactor changelog workflow to improve formatting and extraction logic, enhancing readability and removing obsolete steps
* Update changelog after merging PR #24
* Merge pull request #24 from theaminuli/aminul/dev
* Refactor changelog workflow to enhance changelog generation and formatting, improving readability and updating readme.txt accordingly
* Merge pull request #23 from theaminuli/aminul/dev
* Refactor changelog workflow to improve formatting and error handling, ensuring proper updates to readme.txt
* Update changelog after merging PR #22
* Merge pull request #22 from theaminuli/aminul/dev
* Refactor changelog workflow to simplify formatting and update push target to development branch
* Merge pull request #21 from theaminuli/aminul/dev
* Enhance changelog workflow to extract and format changelog entries, updating readme.txt accordingly
* Merge pull request #20 from theaminuli/aminul/dev
* Improve changelog generation workflow with error handling for missing files
* Merge pull request #19 from theaminuli/aminul/dev
* Update changelog format and add version details for 1.1.0 and 1.0.1
* Merge branch 'development' into aminul/dev
* Improved: Update changelog workflow to generate formatted changelog and push updates after PR merges
* Delete formatted_changelog.txt
* Update changelog for version aminul/dev
* Merge pull request #18 from theaminuli/aminul/dev
* Improved: Enhance changelog workflow to handle no changes gracefully and include debugging output
* Merge pull request #17 from theaminuli/aminul/dev
* - Added: Quicklink library files for better performance
* Merge pull request #16 from theaminuli/aminul/dev
* Added quicklink library files and updated .gitignore to exclude assets
* Update changelog for version aminul/dev
* Merge pull request #15 from theaminuli/aminul/dev
* Improved: Change gem installation to use sudo for changelog generator
* Merge pull request #14 from theaminuli/aminul/dev
* Improved: Update changelog workflow to install Ruby and dependencies more efficiently
* Merge pull request #13 from theaminuli/aminul/dev
* Improved: Update changelog generation workflow to install gem for user and set PATH, and utilize GITHUB_TOKEN from secrets
* Merge pull request #12 from theaminuli/aminul/dev
* Improved: Update GitHub Actions workflow to use Ubuntu 22.04 and include token for changelog generation
* Merge pull request #11 from theaminuli/aminul/dev
* Updated: Update GPL license to version 3.0
* Updated: Update GPL license to version 3.0
* - Improved: Update security policy and PHPCS configuration
* Improved: Update asset management
*  initial configuration files
* Chore: Add initial configuration files
* Refactor: Move NoMoreReload class to a new namespace and enhance its functionality
* Merge pull request #10 from theaminuldev/development
* Merge pull request #9 from theaminuldev/aminul/dev
* Chore: Update plugin tags for better categorization and discoverability
= 1.2.0 - 2025-05-14 =
chore: Add initial configuration files 
Improved: Update asset management
Improved: Update security policy and PHPCS configuration
Improved: Update README and CONTRIBUTING guidelines
Improved: WP coding standards and code quality ensure
Updated: Update GPL license to version 3.0
= 1.1.0 - 2024-12-20 =
Fixed: Security vulnerability reporting guidelines.
Added: Supply chain attack prevention.
= 1.0.1 - 2024-12-19 =
Fixed: Issue with `validateElement` function in `pageflash-frontend`.
Improved: Added validation for `timeoutFn` in `buildListenerOptions` function.
Added: Confusion Clear FAQ plugin for better understanding.
= 1.0.0 - 2024-12-18 =
Initial release.
Added: `pageflash-frontend.js` for frontend functionality.
Added: MetaBox class for admin settings.
Added: `PAGEFLASH_ASSETS_PATH` constant for asset management.
