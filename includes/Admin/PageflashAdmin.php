<?php
namespace PageFlash\Admin;
use PageFlash\Helper\PageFlashSettingsAPI;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * PageFlash Admin Class.
 *
 * This class handles the administration-related functionality for the PageFlash plugin.
 *
 * @package PageFlash
 * @since 1.0.0
 */
class PageFlashAdmin {
    private $settings_api;

    /**
     * Constructor for the PageFlashAdmin class.
     *
     * Initializes the settings API and adds necessary actions.
     * @since 1.0.0
     */
    public function __construct() {
        $this->settings_api = new PageFlashSettingsAPI();

        add_action('admin_init', array($this, 'pageflash_admin_init'));
        add_action('admin_menu', array($this, 'pageflash_admin_menu'));
    }

    /**
     * Initialize PageFlash admin settings.
     *
     * Sets up the sections and fields, and initializes settings.
     * @since 1.0.0
     */
    public function pageflash_admin_init() {
        // Set the settings
        $this->settings_api->set_sections($this->get_settings_sections());
        $this->settings_api->set_fields($this->get_settings_fields());

        // Initialize settings
        $this->settings_api->admin_init();
    }

    /**
     * Add options page to the admin menu.
     *
     * Registers the PageFlash settings page in the WordPress admin menu.
     * @since 1.0.0
     */
    public function pageflash_admin_menu() {
        add_options_page(
            esc_html__( 'PageFlash Settings', 'pageflash' ),
            esc_html__( 'PageFlash', 'pageflash' ),
            'manage_options', 
            'pageflash_settings', 
            array($this, 'plugin_page')
        );
    }

    /**
     * Define the settings sections.
     *
     * @return array Settings sections
     * @since 1.0.0
     */
    public function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'pageflash_basics',
                'title' => esc_html__('Basic Settings', 'pageflash'),
                'desc'  => esc_html__('Basic settings for PageFlash', 'pageflash'),
            ),
            // array(
            //     'id'    => 'pageflash_advanced',
            //     'title' => __('Advanced Settings', 'pageflash'),
            //     'desc'  => __('Advanced settings for PageFlash', 'pageflash'),
            // ),
        );
        return $sections;
    }

    /**
     * Define the settings fields.
     *
     * @return array Settings fields
     * @since 1.0.0
     */
    public function get_settings_fields() {
        $settings_fields = array(
            'pageflash_basics' => array(
                array(
                    'name'  => 'checkbox',
                    'label' => esc_html__( 'Checkbox', 'pageflash' ),
                    'desc'  => esc_html__( 'Checkbox Label', 'pageflash' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'radio',
                    'label'   => esc_html__( 'Radio Button', 'pageflash' ),
                    'desc'    => esc_html__( 'A radio button', 'pageflash' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => esc_html__( 'A Dropdown', 'pageflash' ),
                    'desc'    => esc_html__( 'Dropdown description', 'pageflash' ),
                    'type'    => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
            ),
            // 'pageflash_advanced' => array(
            //     // Add advanced settings fields here
            // ),
        );

        return $settings_fields;
    }

    /**
     * Display the plugin settings page.
     *
     * Renders the PageFlash settings page in the WordPress admin area.
     * @since 1.0.0
     */
    public function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages and return them as an array of page names with key-value pairs.
     *
     * @return array Page names with key-value pairs
     * @since 1.0.0
     */
    public function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ($pages) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }
}
