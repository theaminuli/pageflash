# PageFlash - Architecture Documentation

## Overview

PageFlash is a WordPress plugin that preloads pages intelligently to boost site speed and enhance user experience by loading pages before users click, ensuring instant page transitions. Built following WordPress Core Contributor guidelines and modern plugin development standards, the plugin offers seamless integration with WordPress sites.

## Table of Contents

1. [Plugin Structure](#plugin-structure)
2. [Contributing](#contributing)
3. [Resources](#resources)

---

## Plugin Structure

### Directory Layout

```
pageflash/
├── pageflash.php                # Main plugin file (entry point)
├── plugin.php                   # Plugin class initialization
├── autoload.php                 # PSR-4 autoloader
├── package.json                 # NPM dependencies and scripts
├── webpack.config.js            # Custom webpack configuration
├── composer.json                # Composer dependencies
├── phpcs.xml                    # PHP CodeSniffer configuration
├── LICENSE                      # GPL-3.0-or-later license
├── README.md                    # User-facing documentation
├── ARCHITECTURE.md              # This file
├── AGENTS.md                    # AI coding agent guidelines
├── CONTRIBUTING.md              # Contribution guidelines
├── CHANGELOG.md                 # Version history
├── SECURITY.md                  # Security policy
│
├── src/                         # Source files (uncompiled)
│   ├── index.js                 # Main entry point (empty placeholder)
│   ├── admin/                   # Admin panel React application
│   │   ├── index.jsx            # Admin app entry point
│   │   └── app.jsx              # Main admin component
│   ├── quicklink/               # Quicklink integration
│   │   └── index.js             # Quicklink initialization
│   └── scss/                    # Stylesheets
│       └── admin.scss           # Admin panel styles
│
├── build/                       # Compiled assets (generated)
│   ├── admin/                   # Built admin assets
│   │   ├── admin.js             # Compiled admin JS
│   │   ├── admin.css            # Compiled admin CSS
│   │   └── admin.asset.php      # Asset dependencies
│   └── quicklink/               # Built quicklink assets
│       ├── quicklink.js         # Compiled quicklink JS
│       └── quicklink.asset.php  # Asset dependencies
│
├── includes/                    # PHP classes (PSR-4)
│   ├── Admin/                   # Admin-related classes
│   │   ├── Admin.php            # Main admin class
│   │   ├── AdminMenu.php        # Menu registration
│   │   └── ActionLinks.php      # Plugin action links
│   ├── AssetsManager/           # Asset management
│   │   └── AssetsManager.php    # Asset enqueue handler
│   ├── Compatibility/           # Compatibility checks
│   │   └── Compatibility.php    # Version compatibility
│   ├── Landmark/                # Core functionality
│   │   └── Landmark.php         # Plugin landmarks
│   └── Helpers/                 # Helper functions
│       └── Helpers.php          # Utility functions
│
├── assets/                      # Static assets
│   ├── logo/                    # Plugin branding
│   │   ├── icon.svg             # Plugin icon
│   │   └── banner.png           # Plugin banner
│   └── js/                      # Static JavaScript
│
├── languages/                   # Internationalization
│   └── pageflash.pot            # Translation template
│
├── .github/                     # GitHub configurations
│   └── workflows/               # CI/CD workflows
│
├── .wordpress-org/              # WordPress.org assets
│   ├── banner-772x250.png       # Plugin directory banner
│   ├── banner-1544x500.png      # Retina banner
│   └── icon-256x256.png         # Plugin directory icon
│
└── dev-docs/                    # Development documentation
    └── [Various documentation files]
```

### File Responsibilities

| File | Purpose |
|------|---------|
| `pageflash.php` | Plugin initialization, constants definition, compatibility checks |
| `plugin.php` | Main plugin class, PSR-4 autoloading, component initialization |
| `autoload.php` | PSR-4 autoloader implementation |
| `AssetsManager.php` | Asset enqueueing and dependency management |
| `Admin.php` | Admin panel initialization and settings rendering |
| `AdminMenu.php` | WordPress admin menu registration |
| `ActionLinks.php` | Plugin action links in plugins list |
| `index.jsx` (admin) | React admin app entry point and initialization |
| `app.jsx` (admin) | Main React component for admin interface |
| `index.js` (quicklink) | Quicklink.js integration and configuration |

---

---

## Contributing

### Development Workflow

1. Fork repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Make changes and test thoroughly
4. Run linters: `npm run lint:js && npm run lint:css`
5. Format code: `npm run format`
6. Commit changes: `git commit -m 'Add amazing feature'`
7. Push to branch: `git push origin feature/amazing-feature`
8. Open Pull Request

### Coding Standards

- **JavaScript**: [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- **CSS**: [WordPress CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/)
- **PHP**: [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)

### Code Review Checklist

- [ ] Follows WordPress coding standards
- [ ] No console errors or warnings
- [ ] Accessibility compliant (WCAG 2.1 AA)
- [ ] Backward compatible (no breaking changes)
- [ ] Documentation updated
- [ ] Tests added/updated (when applicable)

---

## Resources

### Official Documentation

- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [@wordpress/scripts Documentation](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/)
- [React Documentation](https://react.dev/)
- [Quicklink.js Documentation](https://github.com/GoogleChromeLabs/quicklink)

### Learning Resources

- [WordPress Plugin Development](https://developer.wordpress.org/plugins/)
- [Modern WordPress Development](https://developer.wordpress.org/block-editor/)
- [PSR-4 Autoloading Standard](https://www.php-fig.org/psr/psr-4/)
- [Webpack Configuration](https://webpack.js.org/configuration/)

### Project Resources

- [PageFlash GitHub Repository](https://github.com/theaminulai/pageflash)
- [WordPress.org Plugin Page](https://wordpress.org/plugins/pageflash/)
- [Issue Tracker](https://github.com/theaminulai/pageflash/issues)
- [Support Forum](https://wordpress.org/support/plugin/pageflash/)

### Related Documentation

- [AGENTS.md](AGENTS.md) - AI coding agent guidelines
- [CONTRIBUTING.md](CONTRIBUTING.md) - Contribution guidelines
- [SECURITY.md](SECURITY.md) - Security policy
- [CHANGELOG.md](CHANGELOG.md) - Version history

---

## License

This plugin is licensed under GPL-3.0-or-later. See [LICENSE](LICENSE) file for details.

---

**Last Updated**: December 25, 2025  
**Plugin Version**: 1.2.0  
**WordPress Compatibility**: 6.0+  
**PHP Compatibility**: 7.4+
