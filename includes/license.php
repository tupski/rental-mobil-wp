<?php
/**
 * Fungsi-fungsi untuk menangani lisensi plugin
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * URL API untuk verifikasi lisensi
 */
define('RENTAL_MOBIL_LICENSE_API_URL', 'https://verifikasi.tupski.web.id/api');

/**
 * Inisialisasi fungsi lisensi
 */
function rental_mobil_license_init() {
    // Tambahkan AJAX handler untuk aktivasi lisensi
    add_action('wp_ajax_rental_mobil_activate_license', 'rental_mobil_activate_license_ajax');
    
    // Tambahkan AJAX handler untuk deaktivasi lisensi
    add_action('wp_ajax_rental_mobil_deactivate_license', 'rental_mobil_deactivate_license_ajax');
    
    // Tambahkan script untuk halaman pengaturan lisensi
    add_action('admin_enqueue_scripts', 'rental_mobil_license_scripts');
    
    // Tambahkan pengecekan lisensi secara berkala
    add_action('rental_mobil_daily_license_check', 'rental_mobil_check_license');
    
    // Jadwalkan pengecekan lisensi harian jika belum ada
    if (!wp_next_scheduled('rental_mobil_daily_license_check')) {
        wp_schedule_event(time(), 'daily', 'rental_mobil_daily_license_check');
    }
}
add_action('init', 'rental_mobil_license_init');

/**
 * Enqueue scripts untuk halaman pengaturan lisensi
 */
function rental_mobil_license_scripts($hook) {
    // Hanya load di halaman pengaturan plugin
    if (strpos($hook, 'rental-mobil') !== false) {
        wp_enqueue_script('rental-mobil-license', RENTAL_MOBIL_PLUGIN_URL . 'assets/js/license.js', array('jquery'), RENTAL_MOBIL_VERSION, true);
        
        // Localize script untuk AJAX
        wp_localize_script('rental-mobil-license', 'rental_mobil_license', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rental_mobil_license_nonce'),
            'activating' => __('Mengaktifkan...', 'rental-mobil-wp'),
            'deactivating' => __('Menonaktifkan...', 'rental-mobil-wp'),
            'activate' => __('Aktivasi Lisensi', 'rental-mobil-wp'),
            'deactivate' => __('Nonaktifkan Lisensi', 'rental-mobil-wp'),
            'error' => __('Terjadi kesalahan. Silakan coba lagi.', 'rental-mobil-wp')
        ));
    }
}

/**
 * AJAX handler untuk aktivasi lisensi
 */
function rental_mobil_activate_license_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_license_nonce')) {
        wp_send_json_error(array('message' => __('Verifikasi keamanan gagal.', 'rental-mobil-wp')));
    }
    
    // Verifikasi permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Anda tidak memiliki izin untuk melakukan tindakan ini.', 'rental-mobil-wp')));
    }
    
    // Dapatkan kunci lisensi
    $license_key = rental_mobil_get_license_key();
    
    if (empty($license_key)) {
        wp_send_json_error(array('message' => __('Kunci lisensi tidak boleh kosong.', 'rental-mobil-wp')));
    }
    
    // Verifikasi lisensi
    $response = rental_mobil_verify_license($license_key);
    
    if ($response['success']) {
        // Simpan status lisensi
        $options = rental_mobil_get_options();
        $options['license_status'] = 'valid';
        $options['license_expires'] = isset($response['data']['expires']) ? $response['data']['expires'] : '';
        
        update_option('rental_mobil_options', $options);
        
        wp_send_json_success(array(
            'message' => __('Lisensi berhasil diaktifkan.', 'rental-mobil-wp'),
            'status' => 'valid',
            'expires' => isset($response['data']['expires']) ? $response['data']['expires'] : ''
        ));
    } else {
        // Simpan status lisensi tidak valid
        $options = rental_mobil_get_options();
        $options['license_status'] = 'invalid';
        
        update_option('rental_mobil_options', $options);
        
        wp_send_json_error(array('message' => $response['message']));
    }
}

/**
 * AJAX handler untuk deaktivasi lisensi
 */
function rental_mobil_deactivate_license_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_license_nonce')) {
        wp_send_json_error(array('message' => __('Verifikasi keamanan gagal.', 'rental-mobil-wp')));
    }
    
    // Verifikasi permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Anda tidak memiliki izin untuk melakukan tindakan ini.', 'rental-mobil-wp')));
    }
    
    // Dapatkan kunci lisensi
    $license_key = rental_mobil_get_license_key();
    
    if (empty($license_key)) {
        wp_send_json_error(array('message' => __('Kunci lisensi tidak boleh kosong.', 'rental-mobil-wp')));
    }
    
    // Deaktivasi lisensi
    $response = rental_mobil_deactivate_license($license_key);
    
    if ($response['success']) {
        // Hapus status lisensi
        $options = rental_mobil_get_options();
        $options['license_status'] = '';
        $options['license_expires'] = '';
        
        update_option('rental_mobil_options', $options);
        
        wp_send_json_success(array(
            'message' => __('Lisensi berhasil dinonaktifkan.', 'rental-mobil-wp')
        ));
    } else {
        wp_send_json_error(array('message' => $response['message']));
    }
}

/**
 * Verifikasi lisensi dengan API
 */
function rental_mobil_verify_license($license_key) {
    // Dapatkan domain saat ini
    $domain = parse_url(home_url(), PHP_URL_HOST);
    
    // Buat request ke API
    $response = wp_remote_post(RENTAL_MOBIL_LICENSE_API_URL . '/verify', array(
        'timeout' => 15,
        'body' => array(
            'license_key' => $license_key,
            'domain' => $domain,
            'plugin' => 'rental-mobil-wp',
            'version' => RENTAL_MOBIL_VERSION
        )
    ));
    
    // Cek error
    if (is_wp_error($response)) {
        return array(
            'success' => false,
            'message' => $response->get_error_message()
        );
    }
    
    // Dapatkan response body
    $response_body = wp_remote_retrieve_body($response);
    $response_data = json_decode($response_body, true);
    
    // Cek response
    if (!isset($response_data['success'])) {
        return array(
            'success' => false,
            'message' => __('Respons API tidak valid.', 'rental-mobil-wp')
        );
    }
    
    return $response_data;
}

/**
 * Deaktivasi lisensi dengan API
 */
function rental_mobil_deactivate_license($license_key) {
    // Dapatkan domain saat ini
    $domain = parse_url(home_url(), PHP_URL_HOST);
    
    // Buat request ke API
    $response = wp_remote_post(RENTAL_MOBIL_LICENSE_API_URL . '/deactivate', array(
        'timeout' => 15,
        'body' => array(
            'license_key' => $license_key,
            'domain' => $domain,
            'plugin' => 'rental-mobil-wp'
        )
    ));
    
    // Cek error
    if (is_wp_error($response)) {
        return array(
            'success' => false,
            'message' => $response->get_error_message()
        );
    }
    
    // Dapatkan response body
    $response_body = wp_remote_retrieve_body($response);
    $response_data = json_decode($response_body, true);
    
    // Cek response
    if (!isset($response_data['success'])) {
        return array(
            'success' => false,
            'message' => __('Respons API tidak valid.', 'rental-mobil-wp')
        );
    }
    
    return $response_data;
}

/**
 * Cek status lisensi
 */
function rental_mobil_check_license() {
    // Dapatkan kunci lisensi
    $license_key = rental_mobil_get_license_key();
    $license_status = rental_mobil_get_license_status();
    
    // Jika tidak ada kunci lisensi atau status tidak valid, tidak perlu cek
    if (empty($license_key) || $license_status !== 'valid') {
        return;
    }
    
    // Verifikasi lisensi
    $response = rental_mobil_verify_license($license_key);
    
    // Update status lisensi
    $options = rental_mobil_get_options();
    
    if ($response['success']) {
        $options['license_status'] = 'valid';
        $options['license_expires'] = isset($response['data']['expires']) ? $response['data']['expires'] : '';
    } else {
        $options['license_status'] = 'invalid';
    }
    
    update_option('rental_mobil_options', $options);
}

/**
 * Cek apakah lisensi valid
 */
function rental_mobil_is_license_valid() {
    $license_status = rental_mobil_get_license_status();
    return $license_status === 'valid';
}
