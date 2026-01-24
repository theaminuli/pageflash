# Dynamic Feature Instantiation

**Component:** `BootManager::instantiate_feature()`  
**Version:** 1.3.0  
**Last Updated:** January 2026

## Overview

The `instantiate_feature()` method is a core component of PageFlash's Feature Management System that **automatically instantiates feature classes with the correct constructor parameters** using PHP Reflection. This eliminates the need for manual `new Feature()` calls with hardcoded values and enables dynamic, user-driven feature configuration.

## Table of Contents

- [How It Works](#how-it-works)
- [Step-by-Step Process](#step-by-step-process)
- [Real-World Use Cases](#real-world-use-cases)
- [Data Flow Diagram](#data-flow-diagram)
- [Best Practices](#best-practices)
- [Debugging Tips](#debugging-tips)
- [Advanced Examples](#advanced-examples)

---

## How It Works

### Method Signature

```php
protected function instantiate_feature( $class, $settings )
```

**Parameters:**
- `$class` (string) - Fully qualified class name (e.g., `DisableHeartbeat::class`)
- `$settings` (array) - Feature-specific settings from database

**Returns:** Object instance of the feature class

**Location:** `includes/Landmark/BootManager.php`

### Core Logic

```php
protected function instantiate_feature( $class, $settings ) {
    try {
        $reflection  = new \ReflectionClass( $class );
        $constructor = $reflection->getConstructor();

        // No constructor or no parameters
        if ( ! $constructor || 0 === $constructor->getNumberOfParameters() ) {
            return new $class();
        }

        // Build constructor arguments from settings
        $args = array();
        foreach ( $constructor->getParameters() as $param ) {
            $name = $param->getName();

            // Check nested input structure (e.g., input.behavior.value)
            if ( isset( $settings['input'][ $name ]['value'] ) ) {
                $args[] = $settings['input'][ $name ]['value'];
            } elseif ( isset( $settings[ $name ] ) ) {
                // Fallback to direct property
                $args[] = $settings[ $name ];
            } elseif ( $param->isDefaultValueAvailable() ) {
                $args[] = $param->getDefaultValue();
            } else {
                $args[] = null;
            }
        }

        return $reflection->newInstanceArgs( $args );
    } catch ( \Exception $e ) {
        // Fallback to no-args constructor
        return new $class();
    }
}
```

---

## Step-by-Step Process

### 1. Reflection Analysis

```php
$reflection  = new \ReflectionClass( $class );
$constructor = $reflection->getConstructor();
```

**What it does:**
- Uses PHP Reflection API to inspect the class structure
- Detects if a constructor exists
- Retrieves constructor parameter names and types
- Checks if parameters have default values

**Example:**
```php
// For DisableHeartbeat::class, Reflection discovers:
// - Constructor exists: Yes
// - Parameters: ['behavior', 'frequency']
// - Default values: ['disable_everywhere', 60]
```

### 2. No-Parameter Detection

```php
if ( ! $constructor || 0 === $constructor->getNumberOfParameters() ) {
    return new $class();
}
```

**When it triggers:**
- Class has no `__construct()` method
- Constructor exists but takes no parameters

**Features using this pattern:**
- `DisableEmojis`
- `DisableXMLRPC`
- `HideWPVersion`
- `DisableDashicons`
- `DisableEmbeds`

**Example:**
```php
class DisableEmojis {
    public function __construct() {
        // Simple initialization, no parameters needed
        add_action( 'init', array( $this, 'disable_emojis' ) );
    }
}

// Result: new DisableEmojis();
```

### 3. Parameter Matching (Priority Order)

For each constructor parameter, the method searches for values in this priority:

#### **Priority 1: Nested Input Structure** (Highest)

```php
if ( isset( $settings['input'][ $name ]['value'] ) ) {
    $args[] = $settings['input'][ $name ]['value'];
}
```

**Used for:** Features with multiple input fields (behavior, frequency, mode, etc.)

**Database structure:**
```php
'heartbeat' => [
    'active' => true,
    'input' => [
        'behavior' => ['value' => 'allow_posts'],
        'frequency' => ['value' => 120]
    ]
]
```

**Constructor parameter:** `$behavior`  
**Matches:** `$settings['input']['behavior']['value']` → `'allow_posts'`

#### **Priority 2: Direct Property**

```php
elseif ( isset( $settings[ $name ] ) ) {
    $args[] = $settings[ $name ];
}
```

**Used for:** Simple single-value features or alternative data structures

**Database structure:**
```php
'feature' => [
    'active' => true,
    'mode' => 'advanced'  // Direct property
]
```

**Constructor parameter:** `$mode`  
**Matches:** `$settings['mode']` → `'advanced'`

#### **Priority 3: Default Value from Constructor**

```php
elseif ( $param->isDefaultValueAvailable() ) {
    $args[] = $param->getDefaultValue();
}
```

**Used for:** When user hasn't configured a value, use the default from the class

**Constructor:**
```php
public function __construct( $threshold = 300, $placeholder = 'blur' )
```

**Result:** If not configured, uses `300` and `'blur'`

#### **Priority 4: Null Fallback** (Lowest)

```php
else {
    $args[] = null;
}
```

**Used for:** Last resort when no value is found and no default exists

### 4. Instantiation with Arguments

```php
return $reflection->newInstanceArgs( $args );
```

**What it does:**
- Creates a new instance of the class
- Passes the extracted arguments to the constructor
- Maintains proper argument order

**Example:**
```php
// $args = ['allow_posts', 120]
// Results in:
new DisableHeartbeat( 'allow_posts', 120 );
```

### 5. Error Handling

```php
catch ( \Exception $e ) {
    return new $class();
}
```

**Catches:**
- Reflection errors
- Class not found errors
- Constructor invocation errors

**Fallback:** Creates instance without parameters (safe mode)

---

## Real-World Use Cases

### Use Case 1: Multi-Parameter Feature (DisableHeartbeat)

#### Feature Class

```php
// includes/Landmark/General/DisableHeartbeat.php
namespace TheAminul\PageFlash\Landmark\General;

class DisableHeartbeat {
    private $behavior;
    private $frequency;
    
    /**
     * Constructor
     *
     * @param string $behavior  Heartbeat behavior (disable_everywhere, allow_posts, disable_dashboard)
     * @param int    $frequency Heartbeat frequency in seconds
     */
    public function __construct( $behavior = 'disable_everywhere', $frequency = 60 ) {
        $this->behavior  = $behavior;
        $this->frequency = $frequency;
        
        add_action( 'init', array( $this, 'disable_heartbeat' ), 1 );
        add_filter( 'heartbeat_settings', array( $this, 'heartbeat_frequency' ) );
    }
    
    /**
     * Disable heartbeat based on behavior setting
     */
    public function disable_heartbeat() {
        if ( 'disable_everywhere' === $this->behavior ) {
            wp_deregister_script( 'heartbeat' );
        } elseif ( 'allow_posts' === $this->behavior ) {
            global $pagenow;
            if ( 'post.php' !== $pagenow && 'post-new.php' !== $pagenow ) {
                wp_deregister_script( 'heartbeat' );
            }
        } elseif ( 'disable_dashboard' === $this->behavior ) {
            global $pagenow;
            if ( 'index.php' === $pagenow ) {
                wp_deregister_script( 'heartbeat' );
            }
        }
    }
    
    /**
     * Modify heartbeat frequency
     */
    public function heartbeat_frequency( $settings ) {
        if ( ! empty( $this->frequency ) ) {
            $settings['interval'] = $this->frequency;
        }
        return $settings;
    }
}
```

#### Database Settings (pageflash_landmarks)

```php
'heartbeat' => [
    'id'          => 'pf-123',
    'type'        => 'switch',
    'label'       => 'Disable Heartbeat',
    'description' => 'Control WordPress Heartbeat API behavior',
    'active'      => true,
    'slug'        => 'heartbeat',
    'menu'        => 'general',
    'package'     => 'free',
    'input'       => [
        'behavior' => [
            'child_id'    => 'pf-124',
            'type'        => 'select',
            'label'       => 'Behavior',
            'value'       => 'allow_posts',  // ← User selected this
            'default'     => 'disable_everywhere',
            'options'     => [
                'default'            => 'Default Behavior',
                'disable_everywhere' => 'Disable Everywhere',
                'allow_posts'        => 'Only Allow When Editing Posts/Pages',
                'disable_dashboard'  => 'Disable on Dashboard'
            ]
        ],
        'frequency' => [
            'child_id'    => 'pf-125',
            'type'        => 'select',
            'label'       => 'Frequency',
            'value'       => 120,  // ← User selected 120 seconds
            'default'     => 60,
            'options'     => [
                15  => '15 Seconds',
                30  => '30 Seconds',
                60  => '60 Seconds',
                120 => '120 Seconds',
                300 => '300 Seconds'
            ]
        ]
    ]
]
```

#### Registration

```php
// includes/Landmark/Landmark.php
Boot::register('heartbeat', [
    'class'     => DisableHeartbeat::class,
    'namespace' => 'general',
    'package'   => 'free',
    'priority'  => 10,
    'enabled'   => true,
]);
```

#### Instantiation Flow

```php
// BootManager::load_feature() is called with:
$key = 'heartbeat';
$config = [
    'class'     => 'TheAminul\\PageFlash\\Landmark\\General\\DisableHeartbeat',
    'namespace' => 'general',
    'package'   => 'free',
    'priority'  => 10,
    'enabled'   => true,
];
$settings = [
    'active' => true,
    'input' => [
        'behavior' => ['value' => 'allow_posts'],
        'frequency' => ['value' => 120]
    ]
];

// instantiate_feature() is invoked:
$instance = $this->instantiate_feature( 
    'TheAminul\\PageFlash\\Landmark\\General\\DisableHeartbeat',
    $settings
);

// Reflection analysis:
// Constructor found: __construct($behavior = 'disable_everywhere', $frequency = 60)
// Parameters: ['behavior', 'frequency']

// Building arguments:
$args = [];

// Parameter 1: 'behavior'
//   Priority 1: Check $settings['input']['behavior']['value']
//   → Found: 'allow_posts' ✅
$args[0] = 'allow_posts';

// Parameter 2: 'frequency'
//   Priority 1: Check $settings['input']['frequency']['value']
//   → Found: 120 ✅
$args[1] = 120;

// Final instantiation:
return new DisableHeartbeat( 'allow_posts', 120 );
```

#### Result

✅ **Feature loads with user's custom settings!**

- Heartbeat API is disabled everywhere EXCEPT when editing posts/pages
- When heartbeat runs, it uses 120-second interval instead of default 60

---

### Use Case 2: Simple Toggle Feature (DisableEmojis)

#### Feature Class

```php
// includes/Landmark/General/DisableEmojis.php
namespace TheAminul\PageFlash\Landmark\General;

class DisableEmojis {
    /**
     * Constructor - No parameters needed
     */
    public function __construct() {
        add_action( 'init', array( $this, 'disable_emojis' ) );
    }
    
    /**
     * Disable WordPress emoji detection scripts
     */
    public function disable_emojis() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        
        add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
        add_filter( 'wp_resource_hints', array( $this, 'disable_emojis_dns_prefetch' ), 10, 2 );
    }
    
    public function disable_emojis_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        }
        return array();
    }
    
    public function disable_emojis_dns_prefetch( $urls, $relation_type ) {
        if ( 'dns-prefetch' === $relation_type ) {
            $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
            $urls = array_diff( $urls, array( $emoji_svg_url ) );
        }
        return $urls;
    }
}
```

#### Database Settings

```php
'emojis' => [
    'id'          => 'pf-200',
    'type'        => 'switch',
    'label'       => 'Disable Emojis',
    'description' => 'Disable WordPress emoji scripts to improve performance',
    'active'      => true,  // ← Simple on/off toggle
    'slug'        => 'emojis',
    'menu'        => 'general',
    'package'     => 'free'
]
```

#### Instantiation Flow

```php
// Reflection finds: Constructor exists but has 0 parameters

if ( ! $constructor || 0 === $constructor->getNumberOfParameters() ) {
    return new $class();
}

// Result:
new DisableEmojis();
```

#### Result

✅ **Feature loads immediately without parameters!**

- All emoji-related scripts and styles are removed
- TinyMCE emoji plugin disabled
- DNS prefetch for emoji CDN removed

---

### Use Case 3: Feature with Default Values

#### Feature Class

```php
// includes/Landmark/Advanced/LazyLoadImages.php (Hypothetical)
namespace TheAminul\PageFlash\Landmark\Advanced;

class LazyLoadImages {
    private $threshold;
    private $placeholder;
    
    /**
     * Constructor with sensible defaults
     *
     * @param int    $threshold   Distance in pixels to trigger lazy load
     * @param string $placeholder Placeholder type: 'blur', 'spinner', 'none'
     */
    public function __construct( $threshold = 300, $placeholder = 'blur' ) {
        $this->threshold  = $threshold;
        $this->placeholder = $placeholder;
        
        add_filter( 'the_content', array( $this, 'add_lazy_load' ), 99 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }
    
    public function add_lazy_load( $content ) {
        // Add data-src and loading="lazy" attributes
        // Use threshold and placeholder settings
        return $content;
    }
    
    public function enqueue_assets() {
        wp_enqueue_script( 'pageflash-lazy-load' );
        wp_localize_script( 'pageflash-lazy-load', 'lazyLoadConfig', [
            'threshold' => $this->threshold,
            'placeholder' => $this->placeholder
        ]);
    }
}
```

#### Scenario A: User Configured Settings

```php
'lazy_load' => [
    'active' => true,
    'input' => [
        'threshold' => [
            'value' => 500  // ← User wants 500px threshold
        ],
        'placeholder' => [
            'value' => 'spinner'  // ← User prefers spinner
        ]
    ]
]

// Instantiation:
// Parameter 'threshold':
//   → Check: $settings['input']['threshold']['value'] = 500 ✅
// Parameter 'placeholder':
//   → Check: $settings['input']['placeholder']['value'] = 'spinner' ✅

// Result:
new LazyLoadImages( 500, 'spinner' );
```

#### Scenario B: User Didn't Configure (Uses Defaults)

```php
'lazy_load' => [
    'active' => true
    // No input values provided
]

// Instantiation:
// Parameter 'threshold':
//   Priority 1: $settings['input']['threshold']['value'] → Not found
//   Priority 2: $settings['threshold'] → Not found
//   Priority 3: Default value from constructor → 300 ✅
$args[0] = 300;

// Parameter 'placeholder':
//   Priority 1: $settings['input']['placeholder']['value'] → Not found
//   Priority 2: $settings['placeholder'] → Not found
//   Priority 3: Default value from constructor → 'blur' ✅
$args[1] = 'blur';

// Result:
new LazyLoadImages( 300, 'blur' );
```

#### Result

✅ **Feature uses sensible defaults when user hasn't configured!**

- First-time users get working feature immediately
- Advanced users can customize behavior
- No breaking changes if settings missing

---

## Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│ 1. USER ACTION: Admin saves settings in React UI           │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. API REQUEST: PUT /pageflash/v1/landmark/heartbeat       │
│    {                                                        │
│      active: true,                                          │
│      input: {                                               │
│        behavior: { value: 'allow_posts' },                  │
│        frequency: { value: 120 }                            │
│      }                                                       │
│    }                                                         │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. API VALIDATION: LandmarkAPI.php                         │
│    ✅ Validates request                                     │
│    ✅ Checks license (if Pro feature)                       │
│    ✅ Merges with existing settings                         │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. DATABASE: update_option()                               │
│    wp_options → 'pageflash_landmarks'                       │
│    [..., 'heartbeat' => [...]]                              │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 5. WORDPRESS LOAD: Next page request                        │
│    plugins_loaded hook fires                                │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 6. LANDMARK INIT: Landmark.php                             │
│    → new General()                                          │
│    → new NoReload()                                         │
│    → new Advanced()                                         │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 7. MANAGER CONSTRUCT: General extends BootManager          │
│    protected $namespace = 'general';                        │
│    parent::__construct() → init_features()                  │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 8. SETTINGS READ: Helper::get_settings()                   │
│    Returns: get_option('pageflash_landmarks')['data']       │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 9. FEATURE DISCOVERY: Boot::get_features('general')        │
│    Returns array of registered features for namespace       │
│    ['heartbeat' => [class, package, priority, ...]]         │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 10. FEATURE LOOP: foreach ($features as $key => $config)   │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 11. VALIDATION CHECKS:                                      │
│     ✅ Feature enabled in registry                          │
│     ✅ Feature active in settings ($settings['active'])     │
│     ✅ License valid (Pro/Agency features only)             │
│     ✅ Dependencies met (requires => [...])                 │
│     ✅ Custom checks (can_load_feature())                   │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 12. LOAD FEATURE: load_feature($key, $config, $settings)   │
│     $feature_settings = $settings['heartbeat']              │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 13. INSTANTIATE: instantiate_feature($class, $settings)    │
│     ↓                                                        │
│     Reflection analysis                                     │
│     ↓                                                        │
│     Extract parameters: ['behavior', 'frequency']           │
│     ↓                                                        │
│     Match values from $settings['input'][param]['value']    │
│     ↓                                                        │
│     $args = ['allow_posts', 120]                            │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 14. CREATE INSTANCE:                                        │
│     new DisableHeartbeat('allow_posts', 120)                │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│ 15. FEATURE ACTIVE:                                         │
│     ✅ Hooks registered (init, heartbeat_settings)          │
│     ✅ Feature logic runs with user's settings              │
│     ✅ WordPress Heartbeat controlled as configured         │
└─────────────────────────────────────────────────────────────┘
```

---

## Best Practices

### ✅ When to Use Constructor Parameters

#### 1. **Behavior Modes**
```php
public function __construct( $mode = 'disable' )
// Values: 'disable', 'modify', 'allow_posts', 'custom'
```

**Use for:** Features that operate differently based on user selection

**Example:** DisableHeartbeat with 'disable_everywhere', 'allow_posts', 'disable_dashboard'

#### 2. **Numeric Thresholds/Limits**
```php
public function __construct( $limit = 100, $timeout = 60, $interval = 30 )
```

**Use for:** Features with configurable numeric boundaries

**Examples:**
- Lazy load distance threshold
- Cache TTL
- Query limits
- Image quality settings

#### 3. **URLs or Paths**
```php
public function __construct( $cdn_url = '', $asset_path = '/assets/' )
```

**Use for:** Features that work with external resources

**Examples:**
- CDN integration
- Custom upload paths
- External API endpoints

#### 4. **Multiple Related Settings**
```php
public function __construct( $width = 800, $height = 600, $quality = 85, $format = 'webp' )
```

**Use for:** Features with grouped configuration options

**Examples:**
- Image optimization settings
- Video processing parameters
- Cache configuration

#### 5. **Strategy Patterns**
```php
public function __construct( $cache_strategy = 'file', $compression = 'gzip' )
```

**Use for:** Features that support different implementation strategies

**Examples:**
- Cache drivers (file, redis, memcached)
- Compression methods
- Optimization algorithms

### ❌ When NOT to Use Constructor Parameters

#### 1. **Simple On/Off Toggles**

```php
// ❌ BAD: Unnecessary parameter
public function __construct( $enabled = true ) {
    if ( ! $enabled ) return;
    // ...
}

// ✅ GOOD: Control via 'active' field in settings
public function __construct() {
    // Feature is only instantiated when active
    add_action( 'init', array( $this, 'do_something' ) );
}
```

#### 2. **Complex Objects or Arrays**

```php
// ❌ BAD: Too complex for constructor
public function __construct( array $config ) {
    // Hard to match from settings
}

// ✅ GOOD: Simple scalar values
public function __construct( $behavior, $frequency ) {
    // Easy to extract from settings
}
```

#### 3. **Dynamic WordPress Data**

```php
// ❌ BAD: Data that changes or needs fresh value
public function __construct( $current_user_id, $post_id ) {
    // These should be fetched when needed
}

// ✅ GOOD: Get dynamic data in methods
public function __construct( $cache_duration ) {
    $this->cache_duration = $cache_duration;
}

public function process() {
    $user_id = get_current_user_id(); // Fresh value
    $post_id = get_the_ID();
    // ...
}
```

#### 4. **Plugin/Theme Detection**

```php
// ❌ BAD: Environment checks in constructor
public function __construct( $is_woocommerce_active ) {
    // Detection logic belongs in methods
}

// ✅ GOOD: Check when needed
public function __construct() {
    add_action( 'init', array( $this, 'maybe_init' ) );
}

public function maybe_init() {
    if ( class_exists( 'WooCommerce' ) ) {
        // Do WooCommerce-specific logic
    }
}
```

#### 5. **WordPress Options**

```php
// ❌ BAD: Passing WordPress options
public function __construct( $site_url, $blog_name ) {
    // Get these directly when needed
}

// ✅ GOOD: Retrieve WordPress data in methods
public function __construct( $custom_setting ) {
    $this->custom_setting = $custom_setting;
}

public function render() {
    $site_url = get_site_url();
    $blog_name = get_bloginfo( 'name' );
    // ...
}
```

---

## Debugging Tips

### Enable Debug Logging

Add temporary logging to `BootManager::instantiate_feature()`:

```php
protected function instantiate_feature( $class, $settings ) {
    try {
        // Add this for debugging:
        error_log( '=== Instantiating Feature ===' );
        error_log( 'Class: ' . $class );
        error_log( 'Settings: ' . print_r( $settings, true ) );
        
        $reflection  = new \ReflectionClass( $class );
        $constructor = $reflection->getConstructor();

        if ( ! $constructor || 0 === $constructor->getNumberOfParameters() ) {
            error_log( 'No parameters needed' );
            return new $class();
        }

        $args = array();
        foreach ( $constructor->getParameters() as $param ) {
            $name = $param->getName();
            
            // Log parameter matching:
            error_log( "Parameter: {$name}" );

            if ( isset( $settings['input'][ $name ]['value'] ) ) {
                $value = $settings['input'][ $name ]['value'];
                error_log( "  → Found in nested: {$value}" );
                $args[] = $value;
            } elseif ( isset( $settings[ $name ] ) ) {
                $value = $settings[ $name ];
                error_log( "  → Found in direct: {$value}" );
                $args[] = $value;
            } elseif ( $param->isDefaultValueAvailable() ) {
                $value = $param->getDefaultValue();
                error_log( "  → Using default: {$value}" );
                $args[] = $value;
            } else {
                error_log( "  → Using null" );
                $args[] = null;
            }
        }

        error_log( 'Final args: ' . print_r( $args, true ) );
        
        return $reflection->newInstanceArgs( $args );
    } catch ( \Exception $e ) {
        error_log( 'ERROR: ' . $e->getMessage() );
        return new $class();
    }
}
```

### Check Feature Loading

Add this to a mu-plugin or theme's functions.php:

```php
add_action( 'init', function() {
    // Get the General manager instance
    $general = new \TheAminul\PageFlash\Landmark\General\General();
    $loaded = $general->get_features();

    error_log( '=== Loaded Features ===' );
    foreach ( $loaded as $key => $instance ) {
        error_log( "{$key} → " . get_class( $instance ) );
    }
}, 999 );
```

### Verify Settings Structure

Create a debug endpoint:

```php
add_action( 'rest_api_init', function() {
    register_rest_route( 'pageflash-debug/v1', '/settings', [
        'methods' => 'GET',
        'callback' => function() {
            $settings = \TheAminul\PageFlash\Helpers\Helper::get_settings();
            return [
                'heartbeat' => $settings['heartbeat'] ?? null,
                'emojis' => $settings['emojis'] ?? null,
            ];
        },
        'permission_callback' => function() {
            return current_user_can( 'manage_options' );
        }
    ]);
});
```

Access: `/wp-json/pageflash-debug/v1/settings`

### Inspect Feature Instance

```php
add_action( 'init', function() {
    $general = new \TheAminul\PageFlash\Landmark\General\General();
    $heartbeat = $general->get_feature( 'heartbeat' );
    
    if ( $heartbeat ) {
        $reflection = new ReflectionClass( $heartbeat );
        
        echo '<pre>';
        echo 'Class: ' . get_class( $heartbeat ) . "\n";
        
        foreach ( $reflection->getProperties() as $prop ) {
            $prop->setAccessible( true );
            $value = $prop->getValue( $heartbeat );
            echo "{$prop->getName()}: {$value}\n";
        }
        echo '</pre>';
    }
}, 999 );
```

### Test Parameter Extraction

Create a test feature:

```php
namespace TheAminul\PageFlash\Landmark\General;

class TestFeature {
    public function __construct( $param1 = 'default1', $param2 = 'default2', $param3 = 'default3' ) {
        error_log( "TestFeature instantiated with: param1={$param1}, param2={$param2}, param3={$param3}" );
    }
}
```

Register and activate it, then check error logs to see what values were passed.

---

## Advanced Examples

### Example 1: Feature with Optional Parameters

```php
class CacheControl {
    private $driver;
    private $ttl;
    private $prefix;
    
    public function __construct( $driver = 'file', $ttl = 3600, $prefix = '' ) {
        $this->driver = $driver;
        $this->ttl = $ttl;
        $this->prefix = $prefix ?: 'pageflash_';
        
        // Initialize cache based on driver
        switch ( $this->driver ) {
            case 'redis':
                $this->init_redis();
                break;
            case 'memcached':
                $this->init_memcached();
                break;
            default:
                $this->init_file_cache();
        }
    }
}
```

**Settings structure:**
```php
'cache_control' => [
    'active' => true,
    'input' => [
        'driver' => ['value' => 'redis'],
        'ttl' => ['value' => 7200],
        'prefix' => ['value' => 'my_site_']
    ]
]
```

### Example 2: Feature with Type Casting

```php
class ImageOptimizer {
    private $quality;
    private $max_width;
    private $convert_to_webp;
    
    public function __construct( $quality = 85, $max_width = 1920, $convert_to_webp = true ) {
        // Ensure proper types
        $this->quality = (int) $quality;
        $this->max_width = (int) $max_width;
        $this->convert_to_webp = (bool) $convert_to_webp;
        
        add_filter( 'wp_handle_upload', array( $this, 'optimize_upload' ) );
    }
    
    public function optimize_upload( $file ) {
        // Use settings to optimize
        return $file;
    }
}
```

### Example 3: Feature with Validation

```php
class MinifyAssets {
    private $minify_js;
    private $minify_css;
    private $exclude_patterns;
    
    public function __construct( $minify_js = true, $minify_css = true, $exclude_patterns = '' ) {
        $this->minify_js = (bool) $minify_js;
        $this->minify_css = (bool) $minify_css;
        
        // Parse exclude patterns
        $this->exclude_patterns = array_filter(
            array_map( 'trim', explode( ',', $exclude_patterns ) )
        );
        
        if ( $this->minify_js ) {
            add_filter( 'script_loader_tag', array( $this, 'minify_script' ), 10, 3 );
        }
        
        if ( $this->minify_css ) {
            add_filter( 'style_loader_tag', array( $this, 'minify_style' ), 10, 4 );
        }
    }
    
    private function should_exclude( $handle ) {
        foreach ( $this->exclude_patterns as $pattern ) {
            if ( false !== strpos( $handle, $pattern ) ) {
                return true;
            }
        }
        return false;
    }
}
```

**Settings:**
```php
'minify_assets' => [
    'active' => true,
    'input' => [
        'minify_js' => ['value' => true],
        'minify_css' => ['value' => true],
        'exclude_patterns' => ['value' => 'jquery,google-analytics,gtag']
    ]
]
```

---

## Summary

The `instantiate_feature()` method is a powerful component that:

✅ **Eliminates hardcoding** - No manual feature instantiation  
✅ **Enables user configuration** - Features automatically receive user settings  
✅ **Supports multiple patterns** - No-args, single-arg, multi-arg constructors  
✅ **Provides fallbacks** - Uses defaults when configuration is missing  
✅ **Handles errors gracefully** - Safe fallback instantiation  
✅ **Scales effortlessly** - Add features without changing boot logic

This approach makes PageFlash's feature system:
- **Maintainable** - Clear separation of concerns
- **Extensible** - Easy to add new features
- **User-friendly** - Settings immediately affect behavior
- **Robust** - Handles edge cases and errors

---

**Last Updated:** January 23, 2026  
**Related Documentation:**
- [FEATURE-MANAGEMENT-SYSTEM.md](./FEATURE-MANAGEMENT-SYSTEM.md)
- [Boot.php](../includes/Landmark/Boot.php)
- [BootManager.php](../includes/Landmark/BootManager.php)

---

**Questions or Issues?**

1. Check the debugging tips section
2. Review real-world use cases
3. Inspect your feature class constructor
4. Verify database settings structure
5. Contact PageFlash support with error logs
