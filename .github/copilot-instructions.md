# WordPress Plugin Development Guidelines for AI Agents

This document provides comprehensive instructions for AI agents, GitHub Copilot, and automated coding tools when working with WordPress plugin development. Follow these guidelines to ensure code quality, security, accessibility, and adherence to WordPress standards.

---

## 1. WordPress Coding Standards

### 1.1 PHP Coding Standards
- Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use **Yoda conditions** for comparisons: `if ( true === $condition )`
- Use **tabs for indentation**, not spaces
- Opening braces `{` should be on the same line as the statement
- Use `elseif`, not `else if`
- Space after control structures: `if ( condition ) {`, `foreach ( $array as $item ) {`
- Use single quotes for strings unless variable interpolation or special characters are needed
- Always use full PHP opening tags `<?php`, never short tags `<?`
- Add trailing commas in multi-line arrays for better version control diffs

**Example:**
```php
<?php
if ( true === $is_active ) {
	foreach ( $items as $item ) {
		echo esc_html( $item['name'] );
	}
}
```

### 1.2 JavaScript Coding Standards
- Follow [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- Use **tabs for indentation**
- Use `const` and `let`, avoid `var`
- Use camelCase for variable and function names
- Use single quotes for strings
- Add semicolons at the end of statements
- Use strict equality operators: `===` and `!==`
- Use jQuery wrapped in IIFE when working with WordPress: `( function( $ ) { ... } )( jQuery );`
- For modern React/Block development, follow ES6+ standards

**Example:**
```javascript
// jQuery example
( function( $ ) {
	'use strict';
	
	const handleClick = function( event ) {
		event.preventDefault();
		const $target = $( event.target );
		// Logic here
	};
	
	$( document ).ready( handleClick );
} )( jQuery );

// JavaScript ES6+ example
const initializeApp = () => {
	const elements = document.querySelectorAll( '.my-element' );
	
	elements.forEach( ( element ) => {
		element.addEventListener( 'click', ( event ) => {
			event.preventDefault();
			const { dataset } = event.currentTarget;
			console.log( dataset.value );
		} );
	} );
};

document.addEventListener( 'DOMContentLoaded', initializeApp );
```

### 1.3 CSS Coding Standards
- Follow [WordPress CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/)
- Use **tabs for indentation**
- Use lowercase for selectors and properties
- Use shorthand properties where possible
- Add space after colons: `property: value;`
- Use single line for single declarations, multiple lines for multiple declarations
- Include vendor prefixes when necessary (use autoprefixer)
- Avoid ID selectors for styling; use classes instead

**Example:**
```css
.wp-block-custom {
	display: flex;
	align-items: center;
	padding: 1rem;
	background-color: #fff;
}
```

### 1.4 HTML Coding Standards
- Follow [WordPress HTML Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/html/)
- Use **tabs for indentation**
- Use lowercase for tags and attributes
- Always quote attribute values with double quotes
- Use semantic HTML5 elements
- Self-closing tags should have a space before `/>`
- Add ARIA attributes for accessibility

---

## 2. WordPress Security Best Practices

### 2.1 Data Sanitization, Validation, and Escaping

**CRITICAL: Always sanitize input and escape output**

#### Input Sanitization
When receiving user input, always sanitize:
- `sanitize_text_field()` - for text inputs
- `sanitize_email()` - for email addresses
- `sanitize_url()` - for URLs
- `sanitize_key()` - for keys/slugs
- `absint()` - for positive integers
- `wp_kses()` or `wp_kses_post()` - for HTML content
- `sanitize_textarea_field()` - for textarea content

**Example:**
```php
<?php
$username = sanitize_text_field( $_POST['username'] );
$email    = sanitize_email( $_POST['email'] );
$post_id  = absint( $_POST['post_id'] );
```

#### Output Escaping
When outputting data, always escape:
- `esc_html()` - for HTML content
- `esc_attr()` - for HTML attributes
- `esc_url()` - for URLs
- `esc_js()` - for JavaScript strings
- `esc_textarea()` - for textarea values
- `wp_kses_post()` - for post content with allowed HTML

**Example:**
```php
<div class="<?php echo esc_attr( $class_name ); ?>">
	<a href="<?php echo esc_url( $link ); ?>">
		<?php echo esc_html( $title ); ?>
	</a>
</div>
```

### 2.2 Nonce Verification
Always use nonces for form submissions and AJAX requests:

**Creating nonces:**
```php
wp_nonce_field( 'my_action_name', 'my_nonce_field' );
```

**Verifying nonces:**
```php
if ( ! isset( $_POST['my_nonce_field'] ) || ! wp_verify_nonce( $_POST['my_nonce_field'], 'my_action_name' ) ) {
	wp_die( 'Security check failed' );
}
```

**For AJAX:**
```javascript
$.ajax({
	url: ajaxurl,
	data: {
		action: 'my_ajax_action',
		nonce: myPlugin.nonce,
	}
});
```

```php
check_ajax_referer( 'my-nonce-name', 'nonce' );
```

### 2.3 Capability Checks
Always verify user permissions before executing privileged actions:

```php
if ( ! current_user_can( 'edit_posts' ) ) {
	wp_die( 'Unauthorized access' );
}
```

Common capabilities:
- `edit_posts` - can edit posts
- `manage_options` - can manage plugin settings
- `publish_posts` - can publish posts
- `delete_posts` - can delete posts

### 2.4 Database Security
- Use `$wpdb->prepare()` for all database queries with variables
- Never directly concatenate variables into SQL queries

**Example:**
```php
global $wpdb;

$results = $wpdb->get_results(
	$wpdb->prepare(
		"SELECT * FROM {$wpdb->prefix}table WHERE user_id = %d AND status = %s",
		$user_id,
		$status
	)
);
```

### 2.5 File Security
- Add `index.php` files to all directories to prevent directory listing
- Use `ABSPATH` checks in all PHP files:
  ```php
  if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
  }
  ```
- Validate file uploads and check MIME types
- Store sensitive files outside the web root when possible

---

## 3. WordPress Accessibility Standards (WCAG 2.1 Level AA)

### 3.1 Semantic HTML
- Use proper heading hierarchy (h1 → h2 → h3, no skipping)
- Use semantic elements: `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>`
- Use `<button>` for actions, `<a>` for navigation
- Use lists (`<ul>`, `<ol>`) for related items

### 3.2 ARIA Attributes
- Add `aria-label` for icon-only buttons
- Use `aria-describedby` to link descriptions
- Use `aria-live` for dynamic content updates
- Add `role` attributes when semantic HTML isn't sufficient
- Use `aria-hidden="true"` for decorative elements

**Example:**
```php
<button 
	type="button" 
	aria-label="<?php esc_attr_e( 'Close dialog', 'text-domain' ); ?>"
	aria-expanded="false">
	<span aria-hidden="true">×</span>
</button>
```

### 3.3 Keyboard Navigation
- Ensure all interactive elements are keyboard accessible
- Use `tabindex="0"` to add elements to tab order (use sparingly)
- Never use positive tabindex values
- Implement focus styles (don't remove outline without replacement)
- Support keyboard shortcuts (Escape to close, Enter to submit, etc.)

### 3.4 Color and Contrast
- Minimum contrast ratio 4.5:1 for normal text
- Minimum contrast ratio 3:1 for large text (18pt+ or 14pt+ bold)
- Don't rely on color alone to convey information
- Add patterns or text labels in addition to colors

### 3.5 Form Accessibility
- Use `<label>` elements properly associated with inputs
- Add descriptive error messages
- Group related inputs with `<fieldset>` and `<legend>`
- Use `required` attribute and `aria-required="true"`
- Provide clear instructions

**Example:**
```php
<label for="user-email">
	<?php esc_html_e( 'Email Address', 'text-domain' ); ?>
	<span class="required" aria-label="required">*</span>
</label>
<input 
	type="email" 
	id="user-email" 
	name="user_email" 
	required 
	aria-required="true"
	aria-describedby="email-description" />
<p id="email-description" class="description">
	<?php esc_html_e( 'We will never share your email.', 'text-domain' ); ?>
</p>
```

### 3.6 Images and Media
- Always add meaningful `alt` text for images
- Use empty `alt=""` for decorative images
- Provide captions or transcripts for audio/video
- Don't use images of text when HTML text is possible

---

## 4.A React based WordPress Plugin Development Guidelines

### 4.A.1 WordPress React Ecosystem

WordPress uses React extensively in the block editor (Gutenberg) and modern admin interfaces. Follow these guidelines when building React-based WordPress plugins.

#### Core WordPress React Packages

Use WordPress-provided React packages instead of npm React:

```javascript
// Good: Use WordPress packages
import { createElement, useState, useEffect } from '@wordpress/element';
import { Button, TextControl, PanelBody } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';

// Avoid: Direct React imports (unless necessary for specific features)
// import React, { useState, useEffect } from 'react';
```

**Key WordPress Packages:**
- `@wordpress/element` - React wrapper and hooks
- `@wordpress/components` - UI component library
- `@wordpress/data` - State management (Redux-based)
- `@wordpress/api-fetch` - WordPress REST API client
- `@wordpress/i18n` - Internationalization
- `@wordpress/hooks` - Filter and action system
- `@wordpress/compose` - Higher-order components and hooks

### 4.A.2 Component Structure and Best Practices

#### Functional Components with Hooks

Always use functional components with hooks (React 16.8+):

```javascript
import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * UserProfile component displays user information.
 *
 * @param {Object} props Component props
 * @param {number} props.userId User ID to display
 * @return {JSX.Element} Rendered component
 */
function UserProfile( { userId } ) {
	const [ user, setUser ] = useState( null );
	const [ loading, setLoading ] = useState( true );

	useEffect( () => {
		// Fetch user data
		fetchUser( userId ).then( ( userData ) => {
			setUser( userData );
			setLoading( false );
		} );
	}, [ userId ] );

	if ( loading ) {
		return <div>{ __( 'Loading...', 'text-domain' ) }</div>;
	}

	return (
		<div className="user-profile">
			<h2>{ user.name }</h2>
			<p>{ user.email }</p>
		</div>
	);
}

export default UserProfile;
```

#### Component Organization

Follow react.dev best practices for component structure:

**Single Responsibility Principle:**
```javascript
// Bad: Component doing too much
function UserDashboard() {
	// User data fetching
	// Settings management
	// Notifications
	// Analytics
	// ...hundreds of lines
}

// Good: Split into focused components
function UserDashboard() {
	return (
		<>
			<UserProfile />
			<UserSettings />
			<UserNotifications />
			<UserAnalytics />
		</>
	);
}
```

**Props Destructuring:**
```javascript
// Good: Destructure props for clarity
function PostTitle( { title, level = 2, className } ) {
	const Heading = `h${ level }`;
	return <Heading className={ className }>{ title }</Heading>;
}

// Avoid: Using props object directly
function PostTitle( props ) {
	return <h2>{ props.title }</h2>;
}
```

### 4.A.3 State Management

#### Local State with useState

Use `useState` for component-local state:

```javascript
import { useState } from '@wordpress/element';

function SearchForm() {
	const [ searchTerm, setSearchTerm ] = useState( '' );
	const [ results, setResults ] = useState( [] );

	const handleSearch = ( term ) => {
		setSearchTerm( term );
		// Perform search
		searchPosts( term ).then( setResults );
	};

	return (
		<div>
			<input
				type="text"
				value={ searchTerm }
				onChange={ ( e ) => handleSearch( e.target.value ) }
			/>
			<ResultsList results={ results } />
		</div>
	);
}
```

#### Global State with @wordpress/data

For shared state across components, use WordPress Data API:

**Define a Store:**
```javascript
import { createReduxStore, register } from '@wordpress/data';

const DEFAULT_STATE = {
	settings: {},
	loading: false,
	error: null,
};

const actions = {
	setSettings( settings ) {
		return {
			type: 'SET_SETTINGS',
			settings,
		};
	},
	setLoading( loading ) {
		return {
			type: 'SET_LOADING',
			loading,
		};
	},
};

const selectors = {
	getSettings( state ) {
		return state.settings;
	},
	isLoading( state ) {
		return state.loading;
	},
};

const reducer = ( state = DEFAULT_STATE, action ) => {
	switch ( action.type ) {
		case 'SET_SETTINGS':
			return {
				...state,
				settings: action.settings,
			};
		case 'SET_LOADING':
			return {
				...state,
				loading: action.loading,
			};
		default:
			return state;
	}
};

const store = createReduxStore( 'my-plugin/settings', {
	reducer,
	actions,
	selectors,
} );

register( store );
```

**Use the Store:**
```javascript
import { useSelect, useDispatch } from '@wordpress/data';

function SettingsPanel() {
	const settings = useSelect( ( select ) =>
		select( 'my-plugin/settings' ).getSettings()
	);

	const { setSettings } = useDispatch( 'my-plugin/settings' );

	const updateSetting = ( key, value ) => {
		setSettings( {
			...settings,
			[ key ]: value,
		} );
	};

	return (
		<div>
			<TextControl
				label="Site Title"
				value={ settings.siteTitle }
				onChange={ ( value ) => updateSetting( 'siteTitle', value ) }
			/>
		</div>
	);
}
```

### 4.A.4 React Hooks Best Practices

#### Custom Hooks for Reusable Logic

Create custom hooks for shared functionality:

```javascript
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

/**
 * Custom hook to fetch and manage post data.
 *
 * @param {number} postId Post ID to fetch
 * @return {Object} Hook result with post data and loading state
 */
function usePost( postId ) {
	const [ post, setPost ] = useState( null );
	const [ loading, setLoading ] = useState( true );
	const [ error, setError ] = useState( null );

	useEffect( () => {
		if ( ! postId ) {
			return;
		}

		setLoading( true );
		apiFetch( { path: `/wp/v2/posts/${ postId }` } )
			.then( ( data ) => {
				setPost( data );
				setLoading( false );
			} )
			.catch( ( err ) => {
				setError( err );
				setLoading( false );
			} );
	}, [ postId ] );

	return { post, loading, error };
}

// Usage
function PostEditor( { postId } ) {
	const { post, loading, error } = usePost( postId );

	if ( loading ) {
		return <Spinner />;
	}

	if ( error ) {
		return <Notice status="error">{ error.message }</Notice>;
	}

	return <PostForm post={ post } />;
}
```

#### useEffect Dependencies

Always specify correct dependencies:

```javascript
// Good: Correct dependencies
useEffect( () => {
	fetchData( userId, postType );
}, [ userId, postType ] );

// Bad: Missing dependencies (ESLint will warn)
useEffect( () => {
	fetchData( userId, postType );
}, [] );

// Good: Empty array for mount-only effects
useEffect( () => {
	// Only runs once on mount
	initializePlugin();
}, [] );
```

#### Cleanup in useEffect

Always cleanup side effects:

```javascript
useEffect( () => {
	const subscription = subscribeToData( ( data ) => {
		setData( data );
	} );

	// Cleanup function
	return () => {
		subscription.unsubscribe();
	};
}, [] );
```

### 4.A.5 WordPress Components Library

Use `@wordpress/components` for consistent UI

### 4.A.6 WordPress REST API Integration

Use `@wordpress/api-fetch` for API requests:

```javascript
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';

// GET request
async function fetchPosts( params = {} ) {
	const path = addQueryArgs( '/wp/v2/posts', {
		per_page: 10,
		...params,
	} );

	try {
		const posts = await apiFetch( { path } );
		return posts;
	} catch ( error ) {
		console.error( 'Error fetching posts:', error );
		throw error;
	}
}

// POST request
async function createPost( postData ) {
	try {
		const post = await apiFetch( {
			path: '/wp/v2/posts',
			method: 'POST',
			data: postData,
		} );
		return post;
	} catch ( error ) {
		console.error( 'Error creating post:', error );
		throw error;
	}
}

// Custom endpoint
async function getPluginData() {
	try {
		const data = await apiFetch( {
			path: '/my-plugin/v1/data',
			method: 'GET',
		} );
		return data;
	} catch ( error ) {
		console.error( 'Error fetching plugin data:', error );
		throw error;
	}
}
```

### 4.A.7 Performance Optimization

#### React.memo for Component Memoization

Prevent unnecessary re-renders:

```javascript
import { memo } from '@wordpress/element';

// Expensive component that should only re-render when props change
const PostList = memo( function PostList( { posts, onPostClick } ) {
	return (
		<ul>
			{ posts.map( ( post ) => (
				<li key={ post.id } onClick={ () => onPostClick( post ) }>
					{ post.title }
				</li>
			) ) }
		</ul>
	);
} );

// Custom comparison function
const PostListOptimized = memo(
	PostList,
	( prevProps, nextProps ) => {
		// Only re-render if post IDs changed
		return prevProps.posts.map( ( p ) => p.id ).join() ===
			nextProps.posts.map( ( p ) => p.id ).join();
	}
);
```

#### useMemo and useCallback (Optional)

Optimize expensive calculations and callbacks:

```javascript
import { useMemo, useCallback } from '@wordpress/element';

function PostFilter( { posts, filters } ) {
	// Memoize expensive filtering operation
	const filteredPosts = useMemo( () => {
		return posts.filter( ( post ) => {
			return Object.entries( filters ).every( ( [ key, value ] ) => {
				return post[ key ] === value;
			} );
		} );
	}, [ posts, filters ] );

	// Memoize callback to prevent child re-renders
	const handlePostClick = useCallback( ( postId ) => {
		console.log( 'Clicked post:', postId );
		// Handle click
	}, [] );

	return (
		<PostList posts={ filteredPosts } onPostClick={ handlePostClick } />
	);
}
```

#### Lazy Loading Components (Optional)

Split large components for better performance:

```javascript
import { lazy, Suspense } from '@wordpress/element';
import { Spinner } from '@wordpress/components';

// Lazy load heavy components
const AdvancedEditor = lazy( () => import( './components/AdvancedEditor' ) );
const AnalyticsDashboard = lazy( () => import( './components/Analytics' ) );

function PluginAdmin() {
	const [ activeTab, setActiveTab ] = useState( 'editor' );

	return (
		<div>
			<TabNav activeTab={ activeTab } onChange={ setActiveTab } />
			<Suspense fallback={ <Spinner /> }>
				{ activeTab === 'editor' && <AdvancedEditor /> }
				{ activeTab === 'analytics' && <AnalyticsDashboard /> }
			</Suspense>
		</div>
	);
}
```

---

## 4.B WordPress Block Development (Gutenberg)

### 4.B.1 Block Registration
- Use `register_block_type()` with `block.json` manifest
- Define all block attributes in `block.json`
- Use proper category: `text`, `media`, `design`, `widgets`, `theme`, `embed`
- Add proper icons (Dashicons or custom SVG)

**Example block.json:**
```json
{
	"$schema": "https://schemas.wp.org/trunk/block.json",
	"apiVersion": 3,
	"name": "plugin-name/block-name",
	"title": "Block Title",
	"category": "widgets",
	"icon": "admin-site",
	"description": "Block description",
	"supports": {
		"spacing": {
			"padding": true,
			"margin": true
		}
	},
	"textdomain": "plugin-name",
	"editorScript": "file:./index.js",
	"editorStyle": "file:./index.css",
	"style": "file:./style-index.css"
}
```

### 4.B.2 Block Attributes
- Use proper attribute types: `string`, `number`, `boolean`, `array`, `object`
- Define default values
- Use `source` and `selector` for dynamic blocks
- Keep attribute names in camelCase

### 4.B.3 Block Supports
Enable appropriate block supports:
- `align` - alignment options
- `anchor` - custom HTML anchor
- `color` - color settings
- `spacing` - padding and margin
- `typography` - font settings

### 4.B.4 React Best Practices
- Use functional components with hooks
- Destructure props: `const { attributes, setAttributes } = props;`
- Use `useBlockProps()` for wrapper element
- Use `InspectorControls` for sidebar settings
- Use `BlockControls` for toolbar controls
- Import components from `@wordpress/components`

### 4.B.5 Following react.dev Best Practices
- Keep components small and focused
- Use hooks for state and side effects
- Avoid unnecessary re-renders with `React.memo`
- Use context for global state management
- custom hooks for reusable logic
- Specifically stong following react.dev guidelines on component structure and state management
---

## 5. Internationalization (i18n)

### 5.1 Text Domain
- Use consistent text domain throughout plugin
- Define text domain in plugin header and `block.json`
- Load translations: `load_plugin_textdomain()`

### 5.2 Translation Functions

**PHP:**
- `__( 'Text', 'text-domain' )` - returns translated string
- `_e( 'Text', 'text-domain' )` - echoes translated string
- `_x( 'Text', 'context', 'text-domain' )` - with context
- `_n( 'Singular', 'Plural', $count, 'text-domain' )` - plural forms
- `esc_html__()`, `esc_html_e()`, `esc_attr__()`, `esc_attr_e()` - with escaping

**JavaScript:**
```javascript
import { __ } from '@wordpress/i18n';

const title = __( 'Block Title', 'text-domain' );
```

### 5.3 Translation Guidelines
- Keep strings simple and complete
- Don't break sentences for concatenation
- Use placeholders with `sprintf()`:
  ```php
  sprintf( __( 'Hello, %s!', 'text-domain' ), $name )
  ```
- Add translator comments when context is needed:
  ```php
  /* translators: %s: user name */
  sprintf( __( 'Welcome, %s!', 'text-domain' ), $name )
  ```

---

## 6. WordPress Hooks and Filters

### 6.1 Actions
Use actions to execute code at specific points:
```php
add_action( 'init', 'my_custom_init_function' );
add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts' );
add_action( 'admin_menu', 'my_add_admin_menu' );
```

### 6.2 Filters
Use filters to modify data:
```php
add_filter( 'the_content', 'my_content_filter' );
add_filter( 'wp_title', 'my_custom_title', 10, 2 );
```

### 6.3 Custom Hooks
Create custom hooks for extensibility:
```php
// Action
do_action( 'my_plugin_custom_action', $arg1, $arg2 );

// Filter
$value = apply_filters( 'my_plugin_custom_filter', $value, $context );
```

### 6.4 Hook Naming
- Prefix all custom hooks with plugin slug
- Use descriptive names: `{plugin_slug}_{action}_before`, `{plugin_slug}_{action}_after`
- Document all custom hooks

---

## 7. Performance Best Practices

### 7.1 Asset Loading
- Enqueue scripts and styles properly using `wp_enqueue_script()` and `wp_enqueue_style()`
- Only load assets on pages where needed
- Use script dependencies correctly
- Add `true` for footer loading when possible
- Minify and concatenate assets for production

**Example:**
```php
function my_plugin_enqueue_scripts() {
	if ( is_singular( 'post' ) ) {
		wp_enqueue_style(
			'my-plugin-style',
			plugins_url( 'css/style.css', __FILE__ ),
			array(),
			'1.0.0'
		);
		
		wp_enqueue_script(
			'my-plugin-script',
			plugins_url( 'js/script.js', __FILE__ ),
			array( 'jquery' ),
			'1.0.0',
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'my_plugin_enqueue_scripts' );
```

### 7.2 Database Optimization
- Use WordPress caching functions: `wp_cache_set()`, `wp_cache_get()`
- Use transients for expensive operations: `set_transient()`, `get_transient()`
- Limit database queries in loops
- Use `WP_Query` parameters efficiently
- Add database indexes when needed

### 7.3 Caching
```php
$cached_data = get_transient( 'my_plugin_data' );

if ( false === $cached_data ) {
	$cached_data = expensive_operation();
	set_transient( 'my_plugin_data', $cached_data, HOUR_IN_SECONDS );
}
```

---

## 8. File and Folder Structure

### 8.1 Standard Plugin Structure

**General WordPress Plugin Structure Guidelines:**
For detailed file and folder structure of this project, refer to the [File Structure section in ARCHITECTURE.md](./ARCHITECTURE.md#plugin-structure).


### 8.2 Class File Naming
- Use `class-{classname}.php` format
- One class per file
- Class names should match file names: `class-admin.php` → `class Admin`

---

## 9. Code Documentation

### 9.1 DocBlocks
Use PHPDoc format for all functions and classes:

```php
/**
 * Short description.
 *
 * Long description with more details about what the
 * function does and why it exists.
 *
 * @since 1.0.0
 *
 * @param string $param1 Description of param1.
 * @param int    $param2 Description of param2.
 * @return bool True on success, false on failure.
 */
function my_function( $param1, $param2 ) {
	// Function code
}
```

### 9.2 Inline Comments
- Use `//` for single-line comments
- Use `/* */` for multi-line comments
- Explain complex logic, don't state the obvious
- Write comments in clear, complete sentences

### 9.3 File Headers
All plugin files should include headers:

```php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
```

---

## 10. Testing and Quality Assurance

### 10.1 Code Validation
- Use [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) with WordPress rules
- Install WordPress Coding Standards: `composer require --dev wp-coding-standards/wpcs`
- Run PHPCS: `phpcs --standard=WordPress plugin-name/`
- Auto-fix issues: `phpcbf --standard=WordPress plugin-name/`

### 10.2 JavaScript Linting
- Use ESLint with WordPress configuration
- Install: `npm install --save-dev @wordpress/eslint-plugin`
- Add to `.eslintrc.json`:
  ```json
  {
	"extends": "plugin:@wordpress/eslint-plugin/recommended"
  }
  ```

### 10.3 Accessibility Testing
- Test with keyboard only (no mouse)
- Test with screen readers (NVDA, JAWS, VoiceOver)
- Use browser extensions: axe DevTools, WAVE
- Validate HTML: [W3C Validator](https://validator.w3.org/)

### 10.4 Security Testing
- Check for XSS vulnerabilities
- Test SQL injection prevention
- Verify nonce implementation
- Test capability checks
- Use [Plugin Check](https://wordpress.org/plugins/plugin-check/) plugin

### 10.5 Browser Testing
Test in multiple browsers:
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

---

## 11. Code Review Checklist

### 11.1 Security Review
- [ ] All user inputs are sanitized
- [ ] All outputs are escaped properly
- [ ] Nonces are implemented for forms and AJAX
- [ ] Capability checks are in place
- [ ] Database queries use `$wpdb->prepare()`
- [ ] File uploads are validated
- [ ] No direct file access (ABSPATH check)

### 11.2 WordPress Standards Review
- [ ] Follows WordPress PHP coding standards (Yoda conditions, spacing, braces)
- [ ] Follows WordPress JavaScript coding standards
- [ ] Follows WordPress CSS coding standards
- [ ] Uses tabs for indentation
- [ ] Proper naming conventions (snake_case for PHP, camelCase for JS)

### 11.3 Functionality Review
- [ ] Functions are properly namespaced or prefixed
- [ ] Hooks are used correctly
- [ ] Enqueue functions are used for scripts/styles
- [ ] No deprecated functions are used
- [ ] Error handling is implemented
- [ ] Edge cases are handled

### 11.4 Accessibility Review
- [ ] Semantic HTML is used
- [ ] ARIA attributes are present where needed
- [ ] Color contrast meets WCAG AA standards
- [ ] Keyboard navigation works
- [ ] Focus indicators are visible
- [ ] Form labels are properly associated
- [ ] Alt text is present for images

### 11.5 Internationalization Review
- [ ] All strings use translation functions
- [ ] Text domain is consistent
- [ ] Translator comments are added where needed
- [ ] No string concatenation that breaks translations
- [ ] `sprintf()` is used for variable strings

### 11.6 Performance Review
- [ ] Assets are only loaded where needed
- [ ] Database queries are optimized
- [ ] Caching is implemented for expensive operations
- [ ] No queries in loops
- [ ] Transients are used appropriately

### 11.7 Documentation Review
- [ ] All functions have DocBlocks
- [ ] Complex logic has inline comments
- [ ] Plugin header is complete
- [ ] README.txt follows WordPress standards
- [ ] Custom hooks are documented

### 11.8 React Best Practices Checklist

- [ ] Use functional components with hooks exclusively
- [ ] Destructure props for readability
- [ ] Use WordPress packages (@wordpress/element, @wordpress/components)
- [ ] Implement proper prop types or TypeScript interfaces
- [ ] Add JSDoc comments for all components
- [ ] Use custom hooks for reusable logic
- [ ] Memoize expensive operations with useMemo/useCallback
- [ ] Implement error boundaries for critical sections
- [ ] Follow react.dev component composition patterns
- [ ] Keep components small and focused (Single Responsibility)
- [ ] Use WordPress Data API for global state
- [ ] Properly cleanup effects with return functions
- [ ] Add loading and error states for async operations
- [ ] Use WordPress i18n functions for all user-facing strings
- [ ] Test components with @testing-library/react
- [ ] Optimize with React.memo when appropriate
- [ ] Follow accessibility guidelines (ARIA, semantic HTML)

---

## 12. AI Agent Specific Instructions

### 12.1 When Writing Code
1. **Always prioritize security first** - sanitize, validate, escape
2. **Follow WordPress standards exactly** - tabs, Yoda conditions, spacing
3. **Add proper documentation** - DocBlocks for all functions
4. **Consider accessibility** - semantic HTML, ARIA, keyboard support
5. **Use WordPress functions** - don't reinvent the wheel
6. **Add internationalization** - wrap all strings in translation functions
7. **Include error handling** - check for failures and provide feedback
8. **Optimize performance** - limit queries, use caching where appropriate

### 12.2 When Reviewing Code
1. Check for security vulnerabilities first
2. Verify WordPress coding standards compliance
3. Test accessibility with keyboard and screen reader simulation
4. Validate internationalization implementation
5. Review performance implications
6. Ensure proper documentation exists
7. Check for deprecated functions
8. Verify proper use of hooks and filters

### 12.3 When Generating Blocks
1. Always use `block.json` manifest approach
2. Include proper `supports` configuration
3. Use `useBlockProps()` in both edit and save
4. Implement `InspectorControls` for settings
5. Add proper ARIA labels
6. Include translation functions
7. Follow React hooks best practices
8. Add proper TypeScript types if using TypeScript

### 12.4 Error Response
When you cannot complete a task safely:
- Explain WHY you cannot do it (security, standards violation, etc.)
- Suggest the correct approach or alternative
- Provide references to WordPress documentation
- Never compromise security for convenience

---

## 13. References and Resources

### Official WordPress Documentation
- [WordPress Developer Resources](https://developer.wordpress.org/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Plugin Developer Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Theme Developer Handbook](https://developer.wordpress.org/themes/)
- [Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [WordPress APIs](https://codex.wordpress.org/WordPress_APIs)

### Security Resources
- [WordPress Plugin Security](https://developer.wordpress.org/plugins/security/)
- [Data Validation](https://developer.wordpress.org/apis/security/data-validation/)
- [Sanitizing Data](https://developer.wordpress.org/apis/security/sanitizing/)
- [Escaping Output](https://developer.wordpress.org/apis/security/escaping/)
- [Nonces](https://developer.wordpress.org/apis/security/nonces/)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)

### Accessibility Resources
- [WordPress Accessibility Handbook](https://make.wordpress.org/accessibility/handbook/)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [WebAIM Resources](https://webaim.org/)
- [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [A11Y Style Guide](https://a11y-style-guide.com/)

### Internationalization Resources
- [WordPress i18n Handbook](https://developer.wordpress.org/apis/handbook/internationalization/)
- [How to Internationalize Your Plugin](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/)
- [Localization in Block Editor](https://developer.wordpress.org/block-editor/how-to-guides/internationalization/)

### Block Development Resources
- [Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [Create Block Tool](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/)
- [Block API Reference](https://developer.wordpress.org/block-editor/reference-guides/block-api/)
- [@wordpress/scripts Package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/)

### Testing and Validation Tools
- [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards)
- [Plugin Check Plugin](https://wordpress.org/plugins/plugin-check/)
- [Query Monitor Plugin](https://wordpress.org/plugins/query-monitor/)
- [axe DevTools](https://www.deque.com/axe/devtools/)
- [WAVE Browser Extension](https://wave.webaim.org/extension/)

### Performance Resources
- [WordPress Performance Best Practices](https://make.wordpress.org/core/handbook/testing/performance/)
- [Optimization Handbook](https://developer.wordpress.org/advanced-administration/performance/optimization/)
- [Caching in WordPress](https://developer.wordpress.org/apis/handbook/transients/)

### Community Resources
- [WordPress Stack Exchange](https://wordpress.stackexchange.com/)
- [Make WordPress](https://make.wordpress.org/)
---

## 14. Version History

- **Version 1.0.0** (2024-12-06): Initial comprehensive guidelines for AI agents working with WordPress plugin development

---

## 15. License

This document is licensed under GPL v2 or later, consistent with WordPress licensing requirements.

---

**Remember: Security, accessibility, and WordPress standards are non-negotiable. Always prioritize user safety and inclusive design.**
