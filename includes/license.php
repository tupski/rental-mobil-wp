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
 * Aktifkan mode debug untuk lisensi
 */
define('RENTAL_MOBIL_LICENSE_DEBUG', true);

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

    // Tambahkan notifikasi admin jika lisensi tidak valid
    add_action('admin_notices', 'rental_mobil_license_admin_notice');

    // Batasi fitur jika lisensi tidak valid
    if (!rental_mobil_is_license_valid()) {
        add_filter('rental_mobil_feature_enabled', 'rental_mobil_disable_premium_features', 10, 2);

        // Batasi akses ke halaman tambah/edit kendaraan
        add_action('admin_init', 'rental_mobil_restrict_admin_pages');
    }
}
add_action('init', 'rental_mobil_license_init');

/**
 * Tampilkan notifikasi admin jika lisensi tidak valid
 */
function rental_mobil_license_admin_notice() {
    // Hanya tampilkan untuk admin
    if (!current_user_can('manage_options')) {
        return;
    }

    // Tampilkan pesan jika pengguna mencoba mengakses halaman yang dibatasi
    if (isset($_GET['page']) && $_GET['page'] === 'rental-mobil' && isset($_GET['tab']) && $_GET['tab'] === 'license' && isset($_GET['restricted'])) {
        $class = 'notice notice-error';
        $message = __('Anda mencoba mengakses fitur yang memerlukan lisensi valid. Silakan aktivasi lisensi Anda terlebih dahulu.', 'rental-mobil-wp');
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
    }

    $license_status = rental_mobil_get_license_status();
    $license_key = rental_mobil_get_license_key();

    // Jika tidak ada kunci lisensi atau status tidak valid
    if (empty($license_key) || $license_status !== 'valid') {
        $class = 'notice notice-error';
        $message = '';

        if (empty($license_key)) {
            $message = sprintf(
                __('Plugin Rental Mobil WP memerlukan lisensi yang valid. Silakan <a href="%s">aktivasi lisensi</a> untuk menggunakan semua fitur.', 'rental-mobil-wp'),
                admin_url('admin.php?page=rental-mobil&tab=license')
            );
        } elseif ($license_status === 'invalid') {
            $message = sprintf(
                __('Lisensi Rental Mobil WP tidak valid. Silakan <a href="%s">periksa lisensi Anda</a>.', 'rental-mobil-wp'),
                admin_url('admin.php?page=rental-mobil&tab=license')
            );
        } elseif ($license_status === 'expired') {
            $message = sprintf(
                __('Lisensi Rental Mobil WP telah kedaluwarsa. Silakan <a href="%s">perbarui lisensi Anda</a>.', 'rental-mobil-wp'),
                admin_url('admin.php?page=rental-mobil&tab=license')
            );
        }

        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
    }
}

/**
 * Nonaktifkan fitur premium jika lisensi tidak valid
 */
function rental_mobil_disable_premium_features($enabled, $feature) {
    // Daftar fitur premium yang memerlukan lisensi valid
    $premium_features = array(
        'kendaraan_unggulan',
        'booking_online',
        'filter_lanjutan',
        'galeri_kendaraan',
        'custom_style',
        'tambah_kendaraan',
        'edit_kendaraan',
        'hapus_kendaraan',
        'semua_fitur'
    );

    // Jika fitur termasuk dalam daftar premium, nonaktifkan
    if (in_array($feature, $premium_features)) {
        return false;
    }

    return $enabled;
}

/**
 * Batasi akses ke halaman admin jika lisensi tidak valid
 */
function rental_mobil_restrict_admin_pages() {
    global $pagenow;

    // Daftar halaman yang dibatasi
    $restricted_pages = array(
        'post-new.php' => array('post_type' => 'kendaraan'),
        'post.php' => array('post_type' => 'kendaraan', 'action' => 'edit')
    );

    // Cek apakah halaman saat ini dibatasi
    if (isset($restricted_pages[$pagenow])) {
        $params = $restricted_pages[$pagenow];
        $is_restricted = true;

        // Cek parameter tambahan
        foreach ($params as $key => $value) {
            if (!isset($_GET[$key]) || $_GET[$key] != $value) {
                $is_restricted = false;
                break;
            }
        }

        // Jika halaman dibatasi, redirect ke halaman lisensi
        if ($is_restricted) {
            wp_redirect(admin_url('admin.php?page=rental-mobil&tab=license&restricted=1'));
            exit;
        }
    }
}

/**
 * Tambahkan overlay untuk halaman admin jika lisensi tidak valid
 */
function rental_mobil_admin_license_overlay() {
    // Hanya tampilkan di halaman admin plugin
    $screen = get_current_screen();
    if (!$screen || strpos($screen->id, 'kendaraan') === false) {
        return;
    }

    // Cek status lisensi
    if (rental_mobil_is_license_valid()) {
        return;
    }

    // Tampilkan overlay
    ?>
    <div class="rental-mobil-license-overlay">
        <div class="rental-mobil-license-overlay-content">
            <h2><?php _e('Lisensi Tidak Valid', 'rental-mobil-wp'); ?></h2>
            <p><?php _e('Anda memerlukan lisensi yang valid untuk menggunakan fitur ini.', 'rental-mobil-wp'); ?></p>
            <p>
                <a href="<?php echo admin_url('admin.php?page=rental-mobil&tab=license'); ?>" class="button button-primary">
                    <?php _e('Aktivasi Lisensi', 'rental-mobil-wp'); ?>
                </a>
                <a href="<?php echo admin_url('index.php'); ?>" class="button">
                    <?php _e('Kembali ke Dashboard', 'rental-mobil-wp'); ?>
                </a>
            </p>
        </div>
    </div>
    <?php
}
add_action('admin_footer', 'rental_mobil_admin_license_overlay');

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
        // Hapus status lisensi dan semua data terkait
        $options = rental_mobil_get_options();
        $options['license_status'] = '';
        $options['license_expires'] = '';
        $options['license_customer'] = '';
        $options['license_created_at'] = '';
        $options['license_domain_count'] = '';
        $options['license_max_domains'] = '';

        update_option('rental_mobil_options', $options);

        wp_send_json_success(array(
            'message' => __('Lisensi berhasil dinonaktifkan dan domain dihapus dari lisensi.', 'rental-mobil-wp')
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

    // Log debug
    rental_mobil_license_debug_log('Memulai verifikasi lisensi', array(
        'license_key' => $license_key,
        'domain' => $domain,
        'api_url' => RENTAL_MOBIL_LICENSE_API_URL
    ));

    // Coba metode GET terlebih dahulu (untuk kompatibilitas dengan plugin WordPress)
    $url = add_query_arg(
        array(
            'license_key' => $license_key,
            'domain' => $domain,
            'plugin' => 'rental-mobil-wp',
            'version' => RENTAL_MOBIL_VERSION
        ),
        RENTAL_MOBIL_LICENSE_API_URL . '/verify'
    );

    rental_mobil_license_debug_log('Mencoba metode GET', array('url' => $url));

    $response = wp_remote_get($url, array(
        'timeout' => 15,
        'sslverify' => false // Nonaktifkan verifikasi SSL untuk debugging
    ));

    // Jika metode GET gagal, coba metode POST sebagai fallback
    if (is_wp_error($response)) {
        rental_mobil_license_debug_log('Metode GET gagal, mencoba metode POST', array(
            'error' => $response->get_error_message()
        ));

        $response = wp_remote_post(RENTAL_MOBIL_LICENSE_API_URL . '/verify', array(
            'timeout' => 15,
            'sslverify' => false, // Nonaktifkan verifikasi SSL untuk debugging
            'body' => array(
                'license_key' => $license_key,
                'domain' => $domain,
                'plugin' => 'rental-mobil-wp',
                'version' => RENTAL_MOBIL_VERSION
            )
        ));
    }

    // Cek error
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        rental_mobil_license_debug_log('Kedua metode gagal', array('error' => $error_message));

        return array(
            'success' => false,
            'message' => $error_message
        );
    }

    // Dapatkan response body
    $response_body = wp_remote_retrieve_body($response);
    $response_code = wp_remote_retrieve_response_code($response);

    rental_mobil_license_debug_log('Respons diterima', array(
        'code' => $response_code,
        'body' => $response_body
    ));

    $response_data = json_decode($response_body, true);

    // Cek response
    if (!isset($response_data['success'])) {
        rental_mobil_license_debug_log('Respons API tidak valid', array('response' => $response_data));

        return array(
            'success' => false,
            'message' => __('Respons API tidak valid.', 'rental-mobil-wp')
        );
    }

    // Jika sukses, simpan data tambahan
    if ($response_data['success'] && isset($response_data['data'])) {
        // Simpan data tambahan seperti tanggal kedaluwarsa dan nama pelanggan
        $options = rental_mobil_get_options();
        $options['license_expires'] = isset($response_data['data']['expires_at']) ? $response_data['data']['expires_at'] : '';
        $options['license_customer'] = isset($response_data['data']['customer_name']) ? $response_data['data']['customer_name'] : '';

        // Simpan tanggal pembuatan lisensi jika tersedia
        if (isset($response_data['data']['created_at'])) {
            $options['license_created_at'] = $response_data['data']['created_at'];
        } else {
            // Jika tidak tersedia, gunakan tanggal saat ini
            $options['license_created_at'] = date('Y-m-d');
        }

        // Simpan domain terdaftar
        if (isset($response_data['data']['domain_count'])) {
            $options['license_domain_count'] = $response_data['data']['domain_count'];
        }

        // Simpan jumlah maksimal domain
        if (isset($response_data['data']['max_domains'])) {
            $options['license_max_domains'] = $response_data['data']['max_domains'];
        }

        update_option('rental_mobil_options', $options);

        rental_mobil_license_debug_log('Lisensi valid', array(
            'customer' => $options['license_customer'],
            'expires' => $options['license_expires'],
            'created_at' => $options['license_created_at']
        ));
    } else {
        rental_mobil_license_debug_log('Lisensi tidak valid', array('response' => $response_data));
    }

    return $response_data;
}

/**
 * Deaktivasi lisensi dengan API
 */
function rental_mobil_deactivate_license($license_key) {
    // Dapatkan domain saat ini
    $domain = parse_url(home_url(), PHP_URL_HOST);

    // Log debug
    rental_mobil_license_debug_log('Memulai deaktivasi lisensi', array(
        'license_key' => $license_key,
        'domain' => $domain,
        'api_url' => RENTAL_MOBIL_LICENSE_API_URL
    ));

    // Coba metode GET terlebih dahulu (untuk kompatibilitas dengan plugin WordPress)
    $url = add_query_arg(
        array(
            'license_key' => $license_key,
            'domain' => $domain,
            'plugin' => 'rental-mobil-wp'
        ),
        RENTAL_MOBIL_LICENSE_API_URL . '/deactivate'
    );

    rental_mobil_license_debug_log('Mencoba metode GET untuk deaktivasi', array('url' => $url));

    $response = wp_remote_get($url, array(
        'timeout' => 15,
        'sslverify' => false // Nonaktifkan verifikasi SSL untuk debugging
    ));

    // Jika metode GET gagal, coba metode POST sebagai fallback
    if (is_wp_error($response)) {
        rental_mobil_license_debug_log('Metode GET untuk deaktivasi gagal, mencoba metode POST', array(
            'error' => $response->get_error_message()
        ));

        $response = wp_remote_post(RENTAL_MOBIL_LICENSE_API_URL . '/deactivate', array(
            'timeout' => 15,
            'sslverify' => false, // Nonaktifkan verifikasi SSL untuk debugging
            'body' => array(
                'license_key' => $license_key,
                'domain' => $domain,
                'plugin' => 'rental-mobil-wp'
            )
        ));
    }

    // Cek error
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        rental_mobil_license_debug_log('Kedua metode deaktivasi gagal', array('error' => $error_message));

        return array(
            'success' => false,
            'message' => $error_message
        );
    }

    // Dapatkan response body
    $response_body = wp_remote_retrieve_body($response);
    $response_code = wp_remote_retrieve_response_code($response);

    rental_mobil_license_debug_log('Respons deaktivasi diterima', array(
        'code' => $response_code,
        'body' => $response_body
    ));

    $response_data = json_decode($response_body, true);

    // Cek response
    if (!isset($response_data['success'])) {
        rental_mobil_license_debug_log('Respons API deaktivasi tidak valid', array('response' => $response_data));

        return array(
            'success' => false,
            'message' => __('Respons API tidak valid.', 'rental-mobil-wp')
        );
    }

    if ($response_data['success']) {
        rental_mobil_license_debug_log('Lisensi berhasil dinonaktifkan');
    } else {
        rental_mobil_license_debug_log('Lisensi gagal dinonaktifkan', array('response' => $response_data));
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

/**
 * Cek apakah fitur tertentu diaktifkan
 *
 * @param string $feature Nama fitur yang akan diperiksa
 * @return bool True jika fitur diaktifkan, false jika tidak
 */
function rental_mobil_is_feature_enabled($feature) {
    // Default semua fitur diaktifkan
    $enabled = true;

    // Filter untuk menonaktifkan fitur tertentu
    $enabled = apply_filters('rental_mobil_feature_enabled', $enabled, $feature);

    return $enabled;
}

/**
 * Fungsi untuk mencatat log debug
 */
function rental_mobil_license_debug_log($message, $data = array()) {
    if (!defined('RENTAL_MOBIL_LICENSE_DEBUG') || !RENTAL_MOBIL_LICENSE_DEBUG) {
        return;
    }

    $log_dir = WP_CONTENT_DIR . '/rental-mobil-logs';

    // Buat direktori log jika belum ada
    if (!file_exists($log_dir)) {
        wp_mkdir_p($log_dir);
    }

    // Buat file .htaccess untuk melindungi direktori log
    $htaccess_file = $log_dir . '/.htaccess';
    if (!file_exists($htaccess_file)) {
        $htaccess_content = "Order deny,allow\nDeny from all";
        file_put_contents($htaccess_file, $htaccess_content);
    }

    // Buat file index.php untuk melindungi direktori log
    $index_file = $log_dir . '/index.php';
    if (!file_exists($index_file)) {
        $index_content = "<?php\n// Silence is golden.";
        file_put_contents($index_file, $index_content);
    }

    // Format log
    $log_entry = '[' . date('Y-m-d H:i:s') . '] ' . $message;

    if (!empty($data)) {
        $log_entry .= ' | Data: ' . json_encode($data, JSON_PRETTY_PRINT);
    }

    $log_entry .= "\n";

    // Tulis log ke file
    $log_file = $log_dir . '/license-debug.log';
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}
