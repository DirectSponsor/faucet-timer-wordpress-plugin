<?php
/**
 * Plugin Name: Faucet Timer for ClickForCharity
 * Plugin URI: https://clickforcharity.net
 * Description: A personal faucet timer plugin that allows users to track their cryptocurrency faucets and PTC sites with countdown timers.
 * Version: 1.0.0
 * Author: ClickForCharity
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FAUCET_TIMER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FAUCET_TIMER_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('FAUCET_TIMER_VERSION', '1.0.0');

class FaucetTimerPlugin {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Add shortcode
        add_shortcode('faucet_timer', array($this, 'display_faucet_timer'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // AJAX handlers
        add_action('wp_ajax_save_faucet_sites', array($this, 'save_faucet_sites'));
        add_action('wp_ajax_get_faucet_sites', array($this, 'get_faucet_sites'));
        add_action('wp_ajax_delete_faucet_site', array($this, 'delete_faucet_site'));
        add_action('wp_ajax_update_visit_time', array($this, 'update_visit_time'));
        
        // Add menu page
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    public function activate() {
        // Create database table for storing user faucet sites
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'faucet_timer_sites';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            site_name varchar(255) NOT NULL,
            site_url varchar(500) NOT NULL,
            timer_minutes int(11) NOT NULL,
            last_visited datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public function deactivate() {
        // Cleanup if needed
    }
    
    public function enqueue_scripts() {
        if (is_user_logged_in()) {
            wp_enqueue_script('faucet-timer-js', FAUCET_TIMER_PLUGIN_URL . 'assets/faucet-timer.js', array('jquery'), FAUCET_TIMER_VERSION, true);
            wp_enqueue_style('faucet-timer-css', FAUCET_TIMER_PLUGIN_URL . 'assets/faucet-timer.css', array(), FAUCET_TIMER_VERSION);
            
            // Localize script for AJAX
            wp_localize_script('faucet-timer-js', 'faucet_timer_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('faucet_timer_nonce')
            ));
        }
    }
    
    public function display_faucet_timer($atts) {
        if (!is_user_logged_in()) {
            return '<p>Please <a href="' . wp_login_url(get_permalink()) . '">log in</a> to use the Faucet Timer.</p>';
        }
        
        ob_start();
        include FAUCET_TIMER_PLUGIN_PATH . 'templates/faucet-timer-display.php';
        return ob_get_clean();
    }
    
    public function save_faucet_sites() {
        check_ajax_referer('faucet_timer_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'faucet_timer_sites';
        
        $user_id = get_current_user_id();
        $site_name = sanitize_text_field($_POST['site_name']);
        $site_url = esc_url_raw($_POST['site_url']);
        $timer_minutes = intval($_POST['timer_minutes']);
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'site_name' => $site_name,
                'site_url' => $site_url,
                'timer_minutes' => $timer_minutes
            ),
            array('%d', '%s', '%s', '%d')
        );
        
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Site added successfully!', 'id' => $wpdb->insert_id));
        } else {
            wp_send_json_error('Failed to add site.');
        }
    }
    
    public function get_faucet_sites() {
        check_ajax_referer('faucet_timer_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'faucet_timer_sites';
        
        $user_id = get_current_user_id();
        
        $sites = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_id = %d ORDER BY site_name ASC",
            $user_id
        ));
        
        wp_send_json_success($sites);
    }
    
    public function delete_faucet_site() {
        check_ajax_referer('faucet_timer_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'faucet_timer_sites';
        
        $user_id = get_current_user_id();
        $site_id = intval($_POST['site_id']);
        
        $result = $wpdb->delete(
            $table_name,
            array(
                'id' => $site_id,
                'user_id' => $user_id
            ),
            array('%d', '%d')
        );
        
        if ($result !== false) {
            wp_send_json_success('Site deleted successfully!');
        } else {
            wp_send_json_error('Failed to delete site.');
        }
    }
    
    public function update_visit_time() {
        check_ajax_referer('faucet_timer_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'faucet_timer_sites';
        
        $user_id = get_current_user_id();
        $site_id = intval($_POST['site_id']);
        
        $result = $wpdb->update(
            $table_name,
            array('last_visited' => current_time('mysql')),
            array(
                'id' => $site_id,
                'user_id' => $user_id
            ),
            array('%s'),
            array('%d', '%d')
        );
        
        if ($result !== false) {
            wp_send_json_success('Visit time updated!');
        } else {
            wp_send_json_error('Failed to update visit time.');
        }
    }
    
    public function add_admin_menu() {
        add_options_page(
            'Faucet Timer Settings',
            'Faucet Timer',
            'manage_options',
            'faucet-timer-settings',
            array($this, 'admin_page')
        );
    }
    
    public function admin_page() {
        include FAUCET_TIMER_PLUGIN_PATH . 'templates/admin-page.php';
    }
}

// Initialize the plugin
new FaucetTimerPlugin();
