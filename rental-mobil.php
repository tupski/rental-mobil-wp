<?php
/**
 * Plugin Name: Rental Mobil WP
 * Plugin URI: https://tupski.web.id/rental-mobil-wp
 * Description: Plugin WordPress untuk rental mobil dengan fitur menampilkan daftar kendaraan, detail, dan booking.
 * Version: 1.6.6
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
define('RENTAL_MOBIL_VERSION', '1.6.6');
define('RENTAL_MOBIL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RENTAL_MOBIL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RENTAL_MOBIL_PLUGIN_FILE', __FILE__);

// Aktifkan plugin
register_activation_hook(__FILE__, 'rental_mobil_wp_activate');
function rental_mobil_wp_activate() {
    // Buat custom post type dan taxonomies
    require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/post-types.php';
    rental_mobil_register_post_types();

    // Flush rewrite rules
    flush_rewrite_rules();

    // Pastikan pengaturan tidak hilang saat plugin diaktifkan
    $existing_options = get_option('rental_mobil_options', array());
    if (!empty($existing_options)) {
        // Jika pengaturan sudah ada, pastikan disimpan dengan autoload=yes
        update_option('rental_mobil_options', $existing_options, 'yes');

        // Hapus cache opsi untuk memastikan data terbaru
        wp_cache_delete('rental_mobil_options', 'options');
        wp_cache_delete('alloptions', 'options');
    }

    // Simpan versi plugin di database
    update_option('rental_mobil_version', RENTAL_MOBIL_VERSION, 'yes');
}

// Tambahkan fungsi untuk flush rewrite rules saat plugin diaktifkan
add_action('init', 'rental_mobil_wp_rewrite_flush', 20);
function rental_mobil_wp_rewrite_flush() {
    // Cek apakah perlu flush rewrite rules
    if (get_option('rental_mobil_flush_rewrite_rules')) {
        flush_rewrite_rules();
        delete_option('rental_mobil_flush_rewrite_rules');
    }
}

// Set option untuk flush rewrite rules saat plugin diaktifkan
function rental_mobil_wp_set_flush_rewrite_rules() {
    update_option('rental_mobil_flush_rewrite_rules', true);
}
register_activation_hook(__FILE__, 'rental_mobil_wp_set_flush_rewrite_rules');

// Deaktifasi plugin
register_deactivation_hook(__FILE__, 'rental_mobil_wp_deactivate');
function rental_mobil_wp_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Load plugin files
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/post-types.php';
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/settings.php';
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/admin-columns.php';
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/ajax-handlers.php';

// Load shortcodes
require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/shortcodes.php';

// Load form builder dan custom form jika file ada
if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'includes/form-builder.php')) {
    require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/form-builder.php';
}
if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'includes/custom-form.php')) {
    require_once RENTAL_MOBIL_PLUGIN_DIR . 'includes/custom-form.php';
}

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'rental_mobil_wp_enqueue_scripts');
function rental_mobil_wp_enqueue_scripts() {
    // Gunakan kedua opsi untuk memastikan kompatibilitas maksimum

    // Opsi 1: Enqueue dashicons untuk semua pengguna (termasuk yang tidak login)
    wp_enqueue_style('dashicons');

    // Opsi 2: Gunakan Font Awesome sebagai alternatif dan backup
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
    wp_enqueue_style('rental-mobil-icons', RENTAL_MOBIL_PLUGIN_URL . 'assets/css/icons.css', array('font-awesome'), RENTAL_MOBIL_VERSION);

    // Enqueue Select2 untuk semua pengguna (termasuk yang tidak login)
    wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0');
    wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0', true);

    // Enqueue style.css jika file ada
    if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'assets/css/style.css')) {
        wp_enqueue_style('rental-mobil-style', RENTAL_MOBIL_PLUGIN_URL . 'assets/css/style.css', array(), RENTAL_MOBIL_VERSION);
    }

    // Enqueue custom-form.css jika file ada
    if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'assets/css/custom-form.css')) {
        wp_enqueue_style('rental-mobil-custom-form', RENTAL_MOBIL_PLUGIN_URL . 'assets/css/custom-form.css', array(), RENTAL_MOBIL_VERSION);
    }

    // Enqueue script.js jika file ada
    if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'assets/js/script.js')) {
        wp_enqueue_script('rental-mobil-script', RENTAL_MOBIL_PLUGIN_URL . 'assets/js/script.js', array('jquery', 'select2'), RENTAL_MOBIL_VERSION, true);

        // Localize script untuk AJAX
        wp_localize_script('rental-mobil-script', 'rental_mobil_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rental_mobil_nonce'),
            'plugin_url' => RENTAL_MOBIL_PLUGIN_URL
        ));
    }
}

// Enqueue admin scripts and styles
add_action('admin_enqueue_scripts', 'rental_mobil_wp_enqueue_admin_scripts');
function rental_mobil_wp_enqueue_admin_scripts($hook) {
    // Load di halaman plugin rental mobil dan halaman edit kendaraan
    if (strpos($hook, 'rental-mobil') !== false || (get_current_screen() && get_current_screen()->post_type === 'kendaraan' && get_current_screen()->base === 'edit')) {
        // Enqueue admin.css jika file ada
        if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'assets/css/admin.css')) {
            wp_enqueue_style('rental-mobil-admin-style', RENTAL_MOBIL_PLUGIN_URL . 'assets/css/admin.css', array(), RENTAL_MOBIL_VERSION);
        }

        // Enqueue form-builder.css jika file ada
        if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'assets/css/form-builder.css')) {
            wp_enqueue_style('rental-mobil-form-builder', RENTAL_MOBIL_PLUGIN_URL . 'assets/css/form-builder.css', array(), RENTAL_MOBIL_VERSION);
        }

        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');

        // Load jQuery UI untuk sortable
        wp_enqueue_script('jquery-ui-sortable');

        // Load form builder script jika file ada
        if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'assets/js/form-builder.js')) {
            wp_enqueue_script('rental-mobil-form-builder', RENTAL_MOBIL_PLUGIN_URL . 'assets/js/form-builder.js', array('jquery', 'jquery-ui-sortable'), RENTAL_MOBIL_VERSION, true);
        }
    }
}

// Tambahkan menu admin
add_action('admin_menu', 'rental_mobil_wp_admin_menu');
function rental_mobil_wp_admin_menu() {
    // Menu utama: Rental Mobil WP
    add_menu_page(
        __('Rental Mobil WP', 'rental-mobil-wp'),
        __('Rental Mobil WP', 'rental-mobil-wp'),
        'manage_options',
        'rental-mobil-dashboard',
        'rental_mobil_dashboard_page',
        'dashicons-car',
        30
    );

    // Submenu: Semua Kendaraan
    add_submenu_page(
        'rental-mobil-dashboard',
        __('Semua Kendaraan', 'rental-mobil-wp'),
        __('Semua Kendaraan', 'rental-mobil-wp'),
        'manage_options',
        'edit.php?post_type=kendaraan',
        ''
    );

    // Submenu: Tambah Kendaraan Baru
    add_submenu_page(
        'rental-mobil-dashboard',
        __('Tambah Kendaraan Baru', 'rental-mobil-wp'),
        __('Tambah Baru', 'rental-mobil-wp'),
        'manage_options',
        'post-new.php?post_type=kendaraan',
        ''
    );

    // Submenu: Merk Kendaraan
    add_submenu_page(
        'rental-mobil-dashboard',
        __('Merk Kendaraan', 'rental-mobil-wp'),
        __('Merk Kendaraan', 'rental-mobil-wp'),
        'manage_options',
        'edit-tags.php?taxonomy=merk_kendaraan&post_type=kendaraan',
        ''
    );

    // Submenu: Transmisi
    add_submenu_page(
        'rental-mobil-dashboard',
        __('Transmisi', 'rental-mobil-wp'),
        __('Transmisi', 'rental-mobil-wp'),
        'manage_options',
        'edit-tags.php?taxonomy=transmisi&post_type=kendaraan',
        ''
    );

    // Submenu: Bahan Bakar
    add_submenu_page(
        'rental-mobil-dashboard',
        __('Bahan Bakar', 'rental-mobil-wp'),
        __('Bahan Bakar', 'rental-mobil-wp'),
        'manage_options',
        'edit-tags.php?taxonomy=bahan_bakar&post_type=kendaraan',
        ''
    );

    // Submenu: Tipe Kendaraan
    add_submenu_page(
        'rental-mobil-dashboard',
        __('Tipe Kendaraan', 'rental-mobil-wp'),
        __('Tipe Kendaraan', 'rental-mobil-wp'),
        'manage_options',
        'edit-tags.php?taxonomy=tipe_kendaraan&post_type=kendaraan',
        ''
    );

    // Submenu: Tahun Kendaraan
    add_submenu_page(
        'rental-mobil-dashboard',
        __('Tahun Kendaraan', 'rental-mobil-wp'),
        __('Tahun Kendaraan', 'rental-mobil-wp'),
        'manage_options',
        'edit-tags.php?taxonomy=tahun_kendaraan&post_type=kendaraan',
        ''
    );

    // Submenu: Pengaturan
    add_submenu_page(
        'rental-mobil-dashboard',
        __('Pengaturan Rental Mobil', 'rental-mobil-wp'),
        __('Pengaturan', 'rental-mobil-wp'),
        'manage_options',
        'rental-mobil-settings',
        'rental_mobil_settings_page'
    );
}

// Halaman dashboard
function rental_mobil_dashboard_page() {
    // Redirect ke halaman daftar kendaraan
    wp_redirect(admin_url('edit.php?post_type=kendaraan'));
    exit;
}

// Inisialisasi plugin
add_action('init', 'rental_mobil_wp_init');
function rental_mobil_wp_init() {
    // Register post types dan taxonomies
    rental_mobil_register_post_types();

    // Load translations
    load_plugin_textdomain('rental-mobil-wp', false, dirname(plugin_basename(__FILE__)) . '/languages');

    // Inisialisasi variabel global untuk melacak shortcode yang digunakan
    global $rental_mobil_shortcodes_used;
    $rental_mobil_shortcodes_used = array();

    // Pastikan pengaturan tidak hilang saat plugin diupdate
    $plugin_version = get_option('rental_mobil_version', '');
    if ($plugin_version !== RENTAL_MOBIL_VERSION) {
        // Jika versi berbeda, pastikan pengaturan disimpan dengan benar
        $existing_options = get_option('rental_mobil_options', array());
        if (!empty($existing_options)) {
            // Pastikan disimpan dengan autoload=yes dan tidak ada data yang hilang
            update_option('rental_mobil_options', $existing_options, 'yes');

            // Hapus cache opsi untuk memastikan data terbaru
            wp_cache_delete('rental_mobil_options', 'options');
            wp_cache_delete('alloptions', 'options');
        }

        // Update versi plugin di database
        update_option('rental_mobil_version', RENTAL_MOBIL_VERSION, 'yes');
    }
}

// Tambahkan modal ke footer
add_action('wp_footer', 'rental_mobil_wp_add_modals_to_footer', 20);
function rental_mobil_wp_add_modals_to_footer() {
    global $rental_mobil_shortcodes_used;

    // Jika tidak ada shortcode yang digunakan, tidak perlu menambahkan modal
    if (empty($rental_mobil_shortcodes_used)) {
        return;
    }

    // Tampilkan modal booking jika file ada
    if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'templates/booking-modal.php')) {
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/booking-modal.php';
    }

    // Tampilkan modal quick view jika file ada
    if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'templates/quick-view-modal.php')) {
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/quick-view-modal.php';
    }

    // Tampilkan modal zoom jika file ada
    if (file_exists(RENTAL_MOBIL_PLUGIN_DIR . 'templates/zoom-modal.php')) {
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/zoom-modal.php';
    }
}
