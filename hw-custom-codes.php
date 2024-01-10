<?php
/**
 * Plugin Name: HelloWP! | Dynamic Learndash for Elementor
 * Description: Build a better frontend for Learndash with Elementor and / or JetEngine
 * Version: 0.3
 * Author: Soczó Kristóf
 * Author URI: https://hellowp.io
 * Plugin URI: https://github.com/Lonsdale201/Dynamic-Learndash-for-Elementor
 */

if (!defined('ABSPATH')) {
    exit; 
}

final class Learndash_Dynamic_Tag {

    public function __construct() {
        register_activation_hook(__FILE__, [$this, 'on_activation']);
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function on_activation() {
        if (!did_action('elementor/loaded') || !class_exists('SFWD_LMS')) {
            deactivate_plugins(plugin_basename(__FILE__));
            add_action('admin_notices', [$this, 'admin_notice_activation_error']);
        }
    }

    public function admin_notice_activation_error() {
        echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('This plugin requires both Elementor and LearnDash to be installed and activated.', 'custom-dynamic-tag') . '</p></div>';
    }

    public function init() {
        add_filter('plugin_row_meta', [$this, 'add_plugin_meta_links'], 10, 2);
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor_plugin']);
            return;
        }

        // Check if LearnDash is installed and activated
        if (!class_exists('SFWD_LMS')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_learndash_plugin']);
            return;
        }

        
        require_once('plugin.php');
    }

    public function add_plugin_meta_links($links, $file) {
        // Ellenőrizni, hogy ez a plugin-e
        if ($file == plugin_basename(__FILE__)) {
            // Hozzáadni az új linket
            $new_links = [
                'documentation' => '<a href="https://github.com/Lonsdale201/Dynamic-Learndash-for-Elementor/wiki" target="_blank">' . esc_html__('Documentation', 'custom-dynamic-tag') . '</a>',
            ];

            $links = array_merge($links, $new_links);
        }

        return $links;
    }

    public function admin_notice_missing_elementor_plugin() {
        $this->admin_notice_missing_plugin('Elementor');
    }

    public function admin_notice_missing_learndash_plugin() {
        $this->admin_notice_missing_plugin('LearnDash');
    }

    private function admin_notice_missing_plugin($plugin_name) {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'custom-dynamic-tag'),
            '<strong>' . esc_html__('HelloWP! | Dynamic Learndash for Elementor and JetEngine', 'custom-dynamic-tag') . '</strong>',
            '<strong>' . esc_html__($plugin_name, 'custom-dynamic-tag') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}

//  Learndash_Dynamic_Tag.
new Learndash_Dynamic_Tag();
