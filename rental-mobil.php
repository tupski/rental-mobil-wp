<?php
/**
 * Plugin Name: Rental Mobil WP
 * Plugin URI: https://tupski.web.id/rental-mobil-wp
 * Description: Plugin WordPress untuk rental mobil dengan fitur menampilkan daftar kendaraan, detail, dan booking.
 * Version: 1.4.0
 * Author: Angga Artupas
 * Author URI: https://tupski.web.id
 * Text Domain: rental-mobil-wp
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

// Definisikan konstanta plugin
define('RENTAL_MOBIL_VERSION', '1.4.0');
define('RENTAL_MOBIL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RENTAL_MOBIL_PLUGIN_URL', plugin_dir_url(__FILE__));

// Aktifkan plugin
register_activation_hook(__FILE__, 'rental_mobil_activate');
function rental_mobil_activate() {
    // Buat custom post type dan taxonomies
    require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/post-types.php';
    rental_mobil_register_post_types();

    // Flush rewrite rules
    flush_rewrite_rules();
}

// Tambahkan fungsi untuk flush rewrite rules saat plugin diaktifkan
add_action('init', 'rental_mobil_rewrite_flush', 20);
function rental_mobil_rewrite_flush() {
    // Cek apakah perlu flush rewrite rules
    if (get_option('rental_mobil_flush_rewrite_rules')) {
        flush_rewrite_rules();
        delete_option('rental_mobil_flush_rewrite_rules');
    }
}

// Set option untuk flush rewrite rules saat plugin diaktifkan
function rental_mobil_set_flush_rewrite_rules() {
    update_option('rental_mobil_flush_rewrite_rules', true);
}
register_activation_hook(__FILE__, 'rental_mobil_set_flush_rewrite_rules');

// Deaktifasi plugin
register_deactivation_hook(__FILE__, 'rental_mobil_deactivate');
function rental_mobil_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Load plugin files
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/post-types.php';
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/shortcodes.php';
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/settings.php';
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/admin-columns.php';

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'rental_mobil_enqueue_scripts');
function rental_mobil_enqueue_scripts() {
    wp_enqueue_style('rental-mobil-style', RENTAL_MOBIL_PLUGIN_URL . 'assets/css/style.css', array(), RENTAL_MOBIL_VERSION);
    wp_enqueue_script('rental-mobil-script', RENTAL_MOBIL_PLUGIN_URL . 'assets/js/script.js', array('jquery'), RENTAL_MOBIL_VERSION, true);

    // Localize script untuk AJAX
    wp_localize_script('rental-mobil-script', 'rental_mobil_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('rental_mobil_nonce')
    ));
}

// Enqueue admin scripts and styles
add_action('admin_enqueue_scripts', 'rental_mobil_enqueue_admin_scripts');
function rental_mobil_enqueue_admin_scripts($hook) {
    // Load di halaman plugin rental mobil dan halaman edit kendaraan
    if (strpos($hook, 'rental-mobil') !== false || (get_current_screen()->post_type === 'kendaraan' && get_current_screen()->base === 'edit')) {
        wp_enqueue_style('rental-mobil-admin-style', RENTAL_MOBIL_PLUGIN_URL . 'assets/css/admin.css', array(), RENTAL_MOBIL_VERSION);
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
    }
}

// Tambahkan menu admin
add_action('admin_menu', 'rental_mobil_admin_menu');
function rental_mobil_admin_menu() {
    add_menu_page(
        __('Rental Mobil', 'rental-mobil-wp'),
        __('Rental Mobil', 'rental-mobil-wp'),
        'manage_options',
        'rental-mobil',
        'rental_mobil_settings_page',
        'dashicons-car',
        30
    );
}

// Inisialisasi plugin
add_action('init', 'rental_mobil_init');
function rental_mobil_init() {
    // Register post types dan taxonomies
    rental_mobil_register_post_types();

    // Load translations
    load_plugin_textdomain('rental-mobil-wp', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
