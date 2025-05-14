<?php

namespace PageFlash\Helper;

defined( 'ABSPATH' ) || exit;

	/**
	 * Global helper class.
	 *
	 * @since PageFlash 1.0.0
	 */
class Helper {

	/**
	 * Sanitize and return the content using wp_kses.
	 *
	 * This method uses the wp_kses function to sanitize and return content
	 * based on an array of allowed HTML elements and their attributes.
	 *
	 * @since PageFlash 1.0.0
	 *
	 * @param string $content The content to sanitize.
	 * @access public
	 * @return string Sanitized content.
	 */
	public static function sanitize_content( $content ) {
		$allowed_html = self::get_kses_array();
		return wp_kses( $content, $allowed_html );
	}

		/**
		 * Get an array of allowed HTML elements and attributes for content sanitization.
		 *
		 * This method returns an array of allowed HTML elements and their attributes
		 * for use in content sanitization to ensure safe and valid HTML output.
		 *
		 * @since PageFlash 1.0.0
		 * @access public
		 * @return array An array of allowed HTML elements and attributes.
		 */
	public static function get_kses_array() {
		return array(
			'a'                             => array(
				'class'  => array(),
				'href'   => array(),
				'rel'    => array(),
				'title'  => array(),
				'target' => array(),
				'style'  => array(),
			),
			'abbr'                          => array(
				'title' => array(),
			),
			'b'                             => array(
				'class' => array(),
			),
			'blockquote'                    => array(
				'cite' => array(),
			),
			'cite'                          => array(
				'title' => array(),
			),
			'code'                          => array(),
			'pre'                           => array(),
			'del'                           => array(
				'datetime' => array(),
				'title'    => array(),
			),
			'dd'                            => array(),
			'div'                           => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'dl'                            => array(),
			'dt'                            => array(),
			'em'                            => array(),
			'strong'                        => array(),
			'h1'                            => array(
				'class' => array(),
			),
			'h2'                            => array(
				'class' => array(),
			),
			'h3'                            => array(
				'class' => array(),
			),
			'h4'                            => array(
				'class' => array(),
			),
			'h5'                            => array(
				'class' => array(),
			),
			'h6'                            => array(
				'class' => array(),
			),
			'i'                             => array(
				'class' => array(),
			),
			'img'                           => array(
				'alt'     => array(),
				'class'   => array(),
				'height'  => array(),
				'src'     => array(),
				'width'   => array(),
				'style'   => array(),
				'title'   => array(),
				'srcset'  => array(),
				'loading' => array(),
				'sizes'   => array(),
			),
			'figure'                        => array(
				'class' => array(),
			),
			'li'                            => array(
				'class' => array(),
			),
			'ol'                            => array(
				'class' => array(),
			),
			'p'                             => array(
				'class' => array(),
			),
			'q'                             => array(
				'cite'  => array(),
				'title' => array(),
			),
			'span'                          => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'iframe'                        => array(
				'width'       => array(),
				'height'      => array(),
				'scrolling'   => array(),
				'frameborder' => array(),
				'allow'       => array(),
				'src'         => array(),
			),
			'strike'                        => array(),
			'br'                            => array(),
			'table'                         => array(),
			'thead'                         => array(),
			'tbody'                         => array(),
			'tfoot'                         => array(),
			'tr'                            => array(),
			'th'                            => array(),
			'td'                            => array(),
			'colgroup'                      => array(),
			'col'                           => array(),
			'strong'                        => array(),
			'data-wow-duration'             => array(),
			'data-wow-delay'                => array(),
			'data-wallpaper-options'        => array(),
			'data-stellar-background-ratio' => array(),
			'ul'                            => array(
				'class' => array(),
			),
			'svg'                           => array(
				'class'               => true,
				'aria-hidden'         => true,
				'aria-labelledby'     => true,
				'role'                => true,
				'xmlns'               => true,
				'width'               => true,
				'height'              => true,
				'viewbox'             => true,
				'preserveaspectratio' => true,
			),
			'g'                             => array( 'fill' => true ),
			'title'                         => array( 'title' => true ),
			'path'                          => array(
				'd'    => true,
				'fill' => true,
			),
			'label'                         => array(
				'for' => array(),
			),
			'fieldset'                      => array(
				'class' => array(),
			),
			'input'                         => array(
				'class'              => array(),
				'type'               => array(),
				'value'              => array(),
				'name'               => array(),
				'id'                 => array(),
				'data-default-color' => array(),
				'checked'            => array(),

			),
			'select'                        => array(
				'id'    => array(),
				'name'  => array(),
				'class' => array(),
			),
			'option'                        => array(
				'value'    => array(),
				'selected' => array(),
			),
			'textarea'                      => array(
				'cols'  => array(),
				'rows'  => array(),
				'class' => array(),
				'id'    => array(),
				'name'  => array(),
			),
		);
	}
}
