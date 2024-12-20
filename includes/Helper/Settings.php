<?php

namespace PageFlash\Helper;

use PageFlash\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * PageFlash Settings API wrapper class
 *
 * @version 1.0 (3-Nov-2023)
 * @package PageFlash
 * @link https://www.theaminul.com/pageflash
 * @src https://github.com/tareq1988/wordpress-settings-api-class
 */

class Settings {

	/**
	 * Settings sections array
	 *
	 * @since PageFlash 1.0.0
	 * @var array
	 */
	protected $settings_sections = array();
	/**
	 * Settings fields array
	 *
	 * @since PageFlash 1.0.0
	 * @var array
	 */
	protected $settings_fields = array();

	/**
	 * Set settings sections
	 *
	 * @since PageFlash 1.0.0
	 * @param array $sections Setting sections array
	 */
	public function set_sections( $sections ) {
		$this->settings_sections = $sections;
		return $this;
	}

	/**
	 * Add a single section
	 *
	 * @since PageFlash 1.0.0
	 * @param array $section
	 */
	public function add_section( $section ) {
		$this->settings_sections[] = $section;
		return $this;
	}

	/**
	 * Set settings fields
	 *
	 * @since PageFlash 1.0.0
	 * @param array $fields Settings fields array
	 */
	public function set_fields( $fields ) {
		$this->settings_fields = $fields;
		return $this;
	}

	public function add_field( $section, $field ) {
		$defaults                            = array(
			'name'  => '',
			'label' => '',
			'desc'  => '',
			'type'  => 'text',
		);
		$arg                                 = wp_parse_args( $field, $defaults );
		$this->settings_fields[ $section ][] = $arg;
		return $this;
	}
	/**
	 * Initializes and registers the settings sections and fields in WordPress.
	 *
	 * This function should be called at the `admin_init` hook.
	 * It registers the settings sections and fields to make them ready for use.
	 *
	 * @since PageFlash 1.0.0
	 */
	public function admin_init() {
		// Register settings sections
		foreach ( $this->settings_sections as $section ) {
			if ( false == get_option( $section['id'] ) ) {
				add_option( $section['id'] );
			}

			if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
				$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
				$callback        = function () use ( $section ) {

					echo wp_kses_post( $section['desc'] );
				};
			} elseif ( isset( $section['callback'] ) ) {
				$callback = $section['callback'];
			} else {
				$callback = null;
			}

			add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
		}

		// Register settings fields
		foreach ( $this->settings_fields as $section => $fields ) {
			foreach ( $fields as $option ) {
				$type = isset( $option['type'] ) ? $option['type'] : 'text';
				$args = array(
					'id'                => $option['name'],
					'label_for'         => "{$section}[{$option['name']}]",
					'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
					'name'              => isset( $option['label'] ) ? $option['label'] : '',
					'section'           => $section,
					'size'              => isset( $option['size'] ) ? $option['size'] : null,
					'options'           => isset( $option['options'] ) ? $option['options'] : '',
					'std'               => isset( $option['default'] ) ? $option['default'] : '',
					'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
					'type'              => $type,
				);
				add_settings_field( "{$section}[{$option['name']}]", isset( $option['label'] ) ? $option['label'] : '', array( $this, 'callback_' . $type ), $section, $section, $args );
			}
		}

		// Create settings in the options table
		foreach ( $this->settings_sections as $section ) {
			register_setting( $section['id'], $section['id'], array( $this, 'sanitize_options' ) );
		}
	}

	/**
	 * Get field description for display.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	public function get_field_description( $args ) {
		if ( ! empty( $args['desc'] ) ) {
			$desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
		} else {
			$desc = '';
		}

		return $desc;
	}

	/**
	 * Displays a text field for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	public function callback_text( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$type  = isset( $args['type'] ) ? $args['type'] : 'text';
		$html  = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"/>', $type, $size, $args['section'], $args['id'], $value );
		$html .= $this->get_field_description( $args );
		echo wp_kses( $html, Helper::get_kses_array() );
	}

	/**
	 * Displays a URL field for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	public function callback_url( $args ) {
		$this->callback_text( $args );
	}

	/**
	 * Displays a number field for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	public function callback_number( $args ) {
		$this->callback_text( $args );
	}

	/**
	 * Displays a checkbox for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	public function callback_checkbox( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$html  = '<fieldset>';
		$html .= sprintf( '<label for="wpuf-%1$s[%2$s]">', $args['section'], $args['id'] );
		$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
		$html .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ) );
		$html .= sprintf( '%1$s</label>', $args['desc'] );
		$html .= '</fieldset>';
		echo wp_kses( $html, Helper::get_kses_array() );
	}

	/**
	 * Displays a multicheckbox for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	public function callback_multicheck( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$html  = '<fieldset>';
		foreach ( $args['options'] as $key => $label ) {
			$checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
			$html   .= sprintf( '<label for="wpuf-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
			$html   .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
			$html   .= sprintf( '%1$s</label><br>', $label );
		}

		$html .= $this->get_field_description( $args );
		$html .= '</fieldset>';
		echo wp_kses( $html, Helper::get_kses_array() );
	}


	/**
	 * Displays a radio group for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	public function callback_radio( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$html  = '<fieldset>';
		foreach ( $args['options'] as $key => $label ) {
			$html .= sprintf( '<label for=wpuf-"%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
			$html .= sprintf( '<input type="radio" class="radio" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
			$html .= sprintf( '%1$s</label><br>', $label );
		}

		$html .= $this->get_field_description( $args );
		$html .= '</fieldset>';
		echo wp_kses( $html, Helper::get_kses_array() );
	}

	/**
	 * Displays a selectbox for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	public function callback_select( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$html  = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );
		foreach ( $args['options'] as $key => $label ) {
			$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
		}

		$html .= sprintf( '</select>' );
		$html .= $this->get_field_description( $args );
		echo wp_kses( $html, Helper::get_kses_array() );
	}

	/**
	 * Displays a textarea for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	public function callback_textarea( $args ) {
		$value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$html  = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $size, $args['section'], $args['id'], $value );
		$html .= $this->get_field_description( $args );
		echo wp_kses( $html, Helper::get_kses_array() );
	}

	/**
	 * Displays an HTML block for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @return string
	 * @since PageFlash 1.0.0
	 */
	public function callback_html( $args ) {
		echo wp_kses( $this->get_field_description( $args ), Helper::get_kses_array() );
	}

	/**
	 * Displays a rich text textarea for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	public function callback_wysiwyg( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : '500px';
		echo '<div style="max-width: ' . esc_attr( $size ) . ';">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already Security Escaped

		$editor_settings = array(
			'teeny'         => true,
			'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
			'textarea_rows' => 10,
		);
		if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
			$editor_settings = array_merge( $editor_settings, $args['options'] );
		}

		wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );
		echo '</div>';
		echo wp_kses( $this->get_field_description( $args ), Helper::get_kses_array() );
	}


	/**
	 * Displays a file upload field for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	function callback_file( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$id    = $args['section'] . '[' . $args['id'] . ']';
		$label = isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : esc_html__( 'Choose File', 'pageflash' );
		$html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
		$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
		$html .= $this->get_field_description( $args );
		echo wp_kses( $html, Helper::get_kses_array() );
	}

	/**
	 * Displays a password field for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	function callback_password( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$html  = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
		$html .= $this->get_field_description( $args );
		echo wp_kses( $html, Helper::get_kses_array() );
	}

	/**
	 * Displays a color picker field for a settings field.
	 *
	 * @param array $args Settings field args.
	 * @since PageFlash 1.0.0
	 */
	function callback_color( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$html  = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $args['section'], $args['id'], $value, $args['std'] );
		$html .= $this->get_field_description( $args );
		echo wp_kses( $html, Helper::get_kses_array() );
	}

	/**
	 * Sanitize callback for Settings API.
	 *
	 * @param array $options Settings options.
	 * @return array Sanitized options.
	 * @since PageFlash 1.0.0
	 */
	function sanitize_options( $options ) {
		foreach ( $options as $option_slug => $option_value ) {
			$sanitize_callback = $this->get_sanitize_callback( $option_slug );
			// If callback is set, call it
			if ( $sanitize_callback ) {
				$options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
			}
		}

		return $options;
	}

	/**
	 * Get sanitization callback for a given option slug.
	 *
	 * @param string $slug Option slug.
	 * @return mixed String or bool false.
	 * @since PageFlash 1.0.0
	 */
	function get_sanitize_callback( $slug = '' ) {
		if ( empty( $slug ) ) {
			return false;
		}

		// Iterate over registered fields and see if we can find the proper callback
		foreach ( $this->settings_fields as $section => $options ) {
			foreach ( $options as $option ) {
				if ( $option['name'] == $slug ) {
					// Return the callback name
					return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
				}
			}
		}

		return false;
	}

	/**
	 * Get the value of a settings field.
	 *
	 * @param string $option  Settings field name.
	 * @param string $section The section name this field belongs to.
	 * @param string $default Default text if it's not found.
	 * @return string
	 * @since PageFlash 1.0.0
	 */
	function get_option( $option, $section, $default = '' ) {
		$options = get_option( $section );
		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}

		return $default;
	}

	/**
	 * Show navigations as tabs.
	 *
	 * Shows all the settings section labels as tabs.
	 *
	 * @since PageFlash 1.0.0
	 */
	function show_navigation() {
		$html = '<h2 class="nav-tab-wrapper">';
		foreach ( $this->settings_sections as $tab ) {
			$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
		}

		$html .= '</h2>';
		echo wp_kses( $html, Helper::get_kses_array() );
	}

	/**
	 * Show the section settings forms.
	 *
	 * This function displays every section in a different form.
	 *
	 * @since PageFlash 1.0.0
	 */
	function show_forms() {
		?>
		<div class="metabox-holder">
			<?php
			foreach ( $this->settings_sections as $form ) {
				?>
				<div id="<?php echo esc_attr( $form['id'] ); ?>" class="group" style="display: none;">
					<form method="post" action="options.php">
						<?php
							do_action( 'wsa_form_top_' . $form['id'], $form );
						settings_fields( $form['id'] );
						do_settings_sections( $form['id'] );
						do_action( 'wsa_form_bottom_' . $form['id'], $form );
						?>
						<div style="padding-left: 10px">
							<?php submit_button(); ?>
						</div>
					</form>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
}
