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
 * Nonaktifkan verifikasi SSL untuk debugging
 */
define('RENTAL_MOBIL_LICENSE_SSL_VERIFY', false);

/**
 * Inisialisasi fungsi lisensi
 */
function rental_mobil_license_init() {
    // Tambahkan AJAX handler untuk aktivasi lisensi
    add_action('wp_ajax_rental_mobil_activate_license', 'rental_mobil_activate_license_ajax');

    // Tambahkan AJAX handler untuk deaktivasi lisensi
    add_action('wp_ajax_rental_mobil_deactivate_license', 'rental_mobil_deactivate_license_ajax');

    // Tambahkan AJAX handler untuk memeriksa status lisensi
    add_action('wp_ajax_rental_mobil_check_license_status', 'rental_mobil_ajax_check_license_status');

    // Tambahkan script untuk halaman pengaturan lisensi
    add_action('admin_enqueue_scripts', 'rental_mobil_license_scripts');

    // Tambahkan pengecekan lisensi secara berkala
    add_action('rental_mobil_daily_license_check', 'rental_mobil_check_license');

    // Jadwalkan pengecekan lisensi harian jika belum ada
    if (!wp_next_scheduled('rental_mobil_daily_license_check')) {
        wp_schedule_event(time(), 'daily', 'rental_mobil_daily_license_check');
    }

    // Cek lisensi saat halaman pengaturan lisensi dimuat
    add_action('admin_init', 'rental_mobil_maybe_check_license');

    // Tambahkan notifikasi admin jika lisensi tidak valid
    add_action('admin_notices', 'rental_mobil_license_admin_notice');

    // Periksa status lisensi saat plugin dimuat
    rental_mobil_force_check_license_status();

    // Batasi fitur jika lisensi tidak valid
    if (!rental_mobil_is_license_valid()) {
        add_filter('rental_mobil_feature_enabled', 'rental_mobil_disable_premium_features', 10, 2);

        // Batasi akses ke halaman tambah/edit kendaraan
        add_action('admin_init', 'rental_mobil_restrict_admin_pages');
    }
}

/**
 * Paksa pemeriksaan status lisensi saat plugin dimuat
 */
function rental_mobil_force_check_license_status() {
    // Dapatkan opsi
    $options = rental_mobil_get_options();

    // Periksa apakah lisensi baru saja dinonaktifkan
    if (isset($options['license_deactivated']) && $options['license_deactivated'] === 'yes') {
        // Jika lisensi dinonaktifkan dalam 5 menit terakhir, jangan periksa lagi
        if (isset($options['license_deactivated_time']) && (time() - $options['license_deactivated_time']) < 300) {
            rental_mobil_license_debug_log('Melewati pemeriksaan lisensi karena baru saja dinonaktifkan');
            return;
        }
    }

    // Dapatkan kunci lisensi
    $license_key = rental_mobil_get_license_key();

    // Jika ada kunci lisensi, cek statusnya
    if (!empty($license_key)) {
        // Log debug
        rental_mobil_license_debug_log('Memulai pemeriksaan paksa status lisensi', array(
            'license_key' => $license_key
        ));

        // Verifikasi lisensi
        $response = rental_mobil_verify_license($license_key);

        // Log hasil verifikasi
        rental_mobil_license_debug_log('Hasil pemeriksaan paksa status lisensi', array(
            'success' => $response['success'] ? 'yes' : 'no',
            'response' => $response
        ));
    } else {
        // Jika tidak ada kunci lisensi, pastikan status lisensi kosong
        $options = rental_mobil_get_options();
        if (!empty($options['license_status'])) {
            $options['license_status'] = '';
            update_option('rental_mobil_options', $options, 'yes');

            // Refresh opsi dari database untuk memastikan konsistensi
            wp_cache_delete('rental_mobil_options', 'options');
            wp_cache_delete('alloptions', 'options');

            rental_mobil_license_debug_log('Mengosongkan status lisensi karena kunci lisensi kosong');
        }
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
            'checking' => __('Memeriksa...', 'rental-mobil-wp'),
            'activate' => __('Aktivasi Lisensi', 'rental-mobil-wp'),
            'deactivate' => __('Nonaktifkan Lisensi', 'rental-mobil-wp'),
            'active_text' => __('Aktif', 'rental-mobil-wp'),
            'inactive_text' => __('Tidak Aktif', 'rental-mobil-wp'),
            'invalid_text' => __('Lisensi Salah', 'rental-mobil-wp'),
            'expired_text' => __('Kadaluarsa', 'rental-mobil-wp'),
            'expires_text' => __('Kedaluwarsa pada: %s', 'rental-mobil-wp'),
            'error' => __('Terjadi kesalahan. Silakan coba lagi.', 'rental-mobil-wp'),
            'confirm_title' => __('Konfirmasi Nonaktifkan Lisensi', 'rental-mobil-wp'),
            'confirm_deactivate' => __('Anda yakin ingin menghapus lisensi?', 'rental-mobil-wp'),
            'confirm_note' => __('Lisensi tetap aktif, dan Anda dapat menggunakannya di domain lain.', 'rental-mobil-wp'),
            'confirm' => __('Ya, Nonaktifkan', 'rental-mobil-wp'),
            'cancel' => __('Batal', 'rental-mobil-wp')
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

    // Dapatkan kunci lisensi dari POST request
    $license_key = isset($_POST['license_key']) ? sanitize_text_field($_POST['license_key']) : '';

    // Log semua data POST untuk debugging
    rental_mobil_license_debug_log('Data POST aktivasi lisensi', array(
        'post_data' => $_POST,
        'license_key' => $license_key
    ));

    // Jika tidak ada di POST, coba ambil dari opsi
    if (empty($license_key)) {
        $license_key = rental_mobil_get_license_key();
        rental_mobil_license_debug_log('Menggunakan kunci lisensi dari opsi', array(
            'license_key' => $license_key
        ));
    }

    if (empty($license_key)) {
        wp_send_json_error(array('message' => __('Kunci lisensi tidak boleh kosong.', 'rental-mobil-wp')));
    }

    // Simpan kunci lisensi ke opsi terlebih dahulu
    $options = rental_mobil_get_options();
    $options['license_key'] = $license_key;
    update_option('rental_mobil_options', $options, 'yes');

    // Refresh opsi dari database untuk memastikan konsistensi
    wp_cache_delete('rental_mobil_options', 'options');
    wp_cache_delete('alloptions', 'options');

    // Log kunci lisensi untuk debugging
    rental_mobil_license_debug_log('Mencoba aktivasi lisensi', array(
        'license_key' => $license_key,
        'domain' => parse_url(home_url(), PHP_URL_HOST)
    ));

    // Verifikasi lisensi
    $response = rental_mobil_verify_license($license_key);

    // Log respons verifikasi
    rental_mobil_license_debug_log('Respons verifikasi lisensi', array(
        'response' => $response
    ));

    if ($response['success']) {
        // Simpan status lisensi dan semua data terkait
        $options = rental_mobil_get_options();
        $options['license_status'] = 'valid';
        $options['license_key'] = $license_key; // Pastikan kunci lisensi disimpan

        // Simpan data dari respons API
        if (isset($response['data'])) {
            // Tanggal kedaluwarsa (periksa kedua format yang mungkin)
            if (isset($response['data']['expires_at'])) {
                $options['license_expires'] = $response['data']['expires_at'];
            } elseif (isset($response['data']['expires'])) {
                $options['license_expires'] = $response['data']['expires'];
            }

            // Nama pelanggan
            if (isset($response['data']['customer_name'])) {
                $options['license_customer'] = $response['data']['customer_name'];
            }

            // Tanggal pembuatan lisensi
            if (isset($response['data']['created_at'])) {
                $options['license_created_at'] = $response['data']['created_at'];
            } else {
                // Jika tidak tersedia, gunakan tanggal saat ini
                $options['license_created_at'] = date('Y-m-d');
            }

            // Domain terdaftar
            if (isset($response['data']['domain_count'])) {
                $options['license_domain_count'] = $response['data']['domain_count'];
            }

            // Jumlah maksimal domain
            if (isset($response['data']['max_domains'])) {
                $options['license_max_domains'] = $response['data']['max_domains'];
            }
        }

        // Simpan opsi dengan autoload=yes untuk memastikan selalu tersedia
        $update_result = update_option('rental_mobil_options', $options, 'yes');

        // Log hasil update untuk debugging
        rental_mobil_license_debug_log('Hasil update opsi aktivasi lisensi', array(
            'update_result' => $update_result ? 'success' : 'failed',
            'license_status' => $options['license_status'],
            'options' => $options
        ));

        // Refresh opsi dari database untuk memastikan konsistensi
        wp_cache_delete('rental_mobil_options', 'options');
        wp_cache_delete('alloptions', 'options');

        // Verifikasi bahwa status lisensi telah disimpan dengan benar
        $saved_options = get_option('rental_mobil_options', array(), false);
        rental_mobil_license_debug_log('Status lisensi setelah aktivasi', array(
            'saved_status' => isset($saved_options['license_status']) ? $saved_options['license_status'] : 'not set',
            'saved_key' => isset($saved_options['license_key']) ? $saved_options['license_key'] : 'not set'
        ));

        wp_send_json_success(array(
            'message' => __('Lisensi berhasil diaktifkan.', 'rental-mobil-wp'),
            'status' => 'valid',
            'expires' => isset($options['license_expires']) ? $options['license_expires'] : '',
            'customer' => isset($options['license_customer']) ? $options['license_customer'] : '',
            'reload' => true // Tambahkan flag untuk me-reload halaman
        ));
    } else {
        // Coba sekali lagi dengan metode alternatif
        rental_mobil_license_debug_log('Mencoba metode alternatif aktivasi lisensi', array(
            'license_key' => $license_key
        ));

        // Coba aktivasi langsung ke API
        $domain = parse_url(home_url(), PHP_URL_HOST);
        $url = add_query_arg(
            array(
                'license_key' => $license_key,
                'domain' => $domain,
                'plugin' => 'rental-mobil-wp',
                'version' => RENTAL_MOBIL_VERSION
            ),
            RENTAL_MOBIL_LICENSE_API_URL . '/activate'
        );

        $response = wp_remote_get($url, array(
            'timeout' => 15,
            'sslverify' => RENTAL_MOBIL_LICENSE_SSL_VERIFY
        ));

        if (!is_wp_error($response)) {
            $response_body = wp_remote_retrieve_body($response);
            $response_data = json_decode($response_body, true);

            rental_mobil_license_debug_log('Respons aktivasi langsung', array(
                'response' => $response_data
            ));

            if (isset($response_data['success']) && $response_data['success']) {
                // Simpan status lisensi dan semua data terkait
                $options = rental_mobil_get_options();
                $options['license_status'] = 'valid';
                $options['license_key'] = $license_key; // Pastikan kunci lisensi disimpan

                // Simpan data dari respons API
                if (isset($response_data['data'])) {
                    // Tanggal kedaluwarsa
                    if (isset($response_data['data']['expires_at'])) {
                        $options['license_expires'] = $response_data['data']['expires_at'];
                    }

                    // Nama pelanggan
                    if (isset($response_data['data']['customer_name'])) {
                        $options['license_customer'] = $response_data['data']['customer_name'];
                    }

                    // Domain terdaftar
                    if (isset($response_data['data']['domain_count'])) {
                        $options['license_domain_count'] = $response_data['data']['domain_count'];
                    }

                    // Jumlah maksimal domain
                    if (isset($response_data['data']['max_domains'])) {
                        $options['license_max_domains'] = $response_data['data']['max_domains'];
                    }
                }

                // Simpan opsi dengan autoload=yes untuk memastikan selalu tersedia
                $update_result = update_option('rental_mobil_options', $options, 'yes');

                // Refresh opsi dari database untuk memastikan konsistensi
                wp_cache_delete('rental_mobil_options', 'options');
                wp_cache_delete('alloptions', 'options');

                // Verifikasi bahwa status lisensi telah disimpan dengan benar
                $saved_options = get_option('rental_mobil_options', array(), false);
                rental_mobil_license_debug_log('Status lisensi setelah aktivasi langsung', array(
                    'saved_status' => isset($saved_options['license_status']) ? $saved_options['license_status'] : 'not set',
                    'saved_key' => isset($saved_options['license_key']) ? $saved_options['license_key'] : 'not set'
                ));

                wp_send_json_success(array(
                    'message' => __('Lisensi berhasil diaktifkan.', 'rental-mobil-wp'),
                    'status' => 'valid',
                    'expires' => isset($options['license_expires']) ? $options['license_expires'] : '',
                    'customer' => isset($options['license_customer']) ? $options['license_customer'] : '',
                    'reload' => true // Tambahkan flag untuk me-reload halaman
                ));
            }
        }

        // Jika masih gagal, kirim pesan error tetapi tetap simpan kunci lisensi
        $options = rental_mobil_get_options();
        $options['license_key'] = $license_key; // Simpan kunci lisensi meskipun gagal
        $options['license_status'] = 'invalid';

        // Simpan opsi dengan autoload=yes untuk memastikan selalu tersedia
        $update_result = update_option('rental_mobil_options', $options, 'yes');

        // Refresh opsi dari database untuk memastikan konsistensi
        wp_cache_delete('rental_mobil_options', 'options');
        wp_cache_delete('alloptions', 'options');

        // Simpan kunci lisensi meskipun verifikasi gagal
        $options = rental_mobil_get_options();
        $options['license_key'] = $license_key;
        $options['license_status'] = 'invalid';
        update_option('rental_mobil_options', $options, 'yes');

        // Refresh opsi dari database untuk memastikan konsistensi
        wp_cache_delete('rental_mobil_options', 'options');
        wp_cache_delete('alloptions', 'options');
        wp_cache_flush();

        // Kirim respons error dengan flag reload=true
        wp_send_json_error(array(
            'message' => isset($response['message']) ? $response['message'] : __('Lisensi tidak valid.', 'rental-mobil-wp'),
            'reload' => true // Tambahkan flag untuk me-reload halaman
        ));
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

    // Log untuk debugging
    rental_mobil_license_debug_log('Mencoba deaktivasi lisensi via AJAX', array(
        'license_key' => $license_key,
        'domain' => parse_url(home_url(), PHP_URL_HOST)
    ));

    // Deaktivasi lisensi
    $response = rental_mobil_deactivate_license($license_key);

    // Log respons deaktivasi
    rental_mobil_license_debug_log('Respons deaktivasi lisensi', array(
        'response' => $response
    ));

    // Hapus lisensi dari database terlepas dari respons API
    // Ini memastikan lisensi dihapus bahkan jika API gagal

    // Simpan kunci lisensi lama untuk debugging
    $old_license_key = $license_key;

    // Hapus opsi rental_mobil_options secara langsung
    delete_option('rental_mobil_options');

    // Log hasil delete untuk debugging
    rental_mobil_license_debug_log('Hasil delete opsi rental_mobil_options', array(
        'old_license_key' => $old_license_key
    ));

    // Buat opsi baru tanpa data lisensi
    $options = array(
        'filter_options' => array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun'),
        'homepage_vehicles' => array(),
        'license_status' => '',
        'license_key' => '',
        'license_expires' => '',
        'license_customer' => '',
        'license_created_at' => '',
        'license_domain_count' => '',
        'license_max_domains' => '',
        'license_deactivated' => 'yes',
        'license_deactivated_time' => time()
    );

    // Simpan opsi baru
    $update_result = update_option('rental_mobil_options', $options, 'yes');

    // Log hasil update untuk debugging
    rental_mobil_license_debug_log('Hasil update opsi baru setelah deaktivasi', array(
        'update_result' => $update_result ? 'success' : 'failed',
        'options' => $options
    ));

    // Pembersihan cache yang agresif
    wp_cache_delete('rental_mobil_options', 'options');
    wp_cache_delete('alloptions', 'options');
    wp_cache_flush();

    // Verifikasi bahwa lisensi benar-benar dihapus
    $saved_options = get_option('rental_mobil_options', array(), false);

    // Log hasil verifikasi
    rental_mobil_license_debug_log('Verifikasi lisensi dihapus', array(
        'saved_options_exists' => !empty($saved_options) ? 'yes' : 'no',
        'saved_license_key' => isset($saved_options['license_key']) ? $saved_options['license_key'] : 'not set',
        'saved_license_status' => isset($saved_options['license_status']) ? $saved_options['license_status'] : 'not set'
    ));

    // Jika masih ada kunci lisensi, coba metode terakhir
    if (!empty($saved_options['license_key'])) {
        // Metode terakhir: gunakan SQL langsung
        global $wpdb;
        $option_name = 'rental_mobil_options';

        // Dapatkan nilai opsi saat ini
        $current_value = $wpdb->get_var($wpdb->prepare(
            "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
            $option_name
        ));

        if ($current_value) {
            // Unserialize nilai opsi
            $unserialized = maybe_unserialize($current_value);

            // Hapus data lisensi
            if (is_array($unserialized)) {
                $unserialized['license_key'] = '';
                $unserialized['license_status'] = '';
                $unserialized['license_expires'] = '';
                $unserialized['license_customer'] = '';
                $unserialized['license_created_at'] = '';
                $unserialized['license_domain_count'] = '';
                $unserialized['license_max_domains'] = '';
                $unserialized['license_deactivated'] = 'yes';
                $unserialized['license_deactivated_time'] = time();

                // Serialize kembali dan update
                $serialized = maybe_serialize($unserialized);
                $result = $wpdb->update(
                    $wpdb->options,
                    array('option_value' => $serialized),
                    array('option_name' => $option_name)
                );

                // Log hasil update SQL
                rental_mobil_license_debug_log('Hasil update SQL langsung', array(
                    'result' => $result !== false ? 'success' : 'failed',
                    'rows_affected' => $result
                ));
            }
        }

        // Pembersihan cache lagi
        wp_cache_delete('rental_mobil_options', 'options');
        wp_cache_delete('alloptions', 'options');
        wp_cache_flush();
    }

    // Kirim respons sukses
    if ($response['success']) {
        wp_send_json_success(array(
            'message' => __('Lisensi berhasil dinonaktifkan. Plugin tidak akan berfungsi sampai Anda mengaktifkan lisensi lagi.', 'rental-mobil-wp'),
            'reload' => true
        ));
    } else {
        wp_send_json_success(array(
            'message' => __('Lisensi berhasil dinonaktifkan secara lokal. Plugin tidak akan berfungsi sampai Anda mengaktifkan lisensi lagi.', 'rental-mobil-wp'),
            'reload' => true
        ));
    }
}

/**
 * Verifikasi lisensi dengan API
 */
function rental_mobil_verify_license($license_key) {
    // Dapatkan domain saat ini
    $domain = parse_url(home_url(), PHP_URL_HOST);

    // Pastikan kunci lisensi tidak kosong
    if (empty($license_key)) {
        rental_mobil_license_debug_log('Kunci lisensi kosong');
        return array(
            'success' => false,
            'message' => __('Kunci lisensi tidak boleh kosong.', 'rental-mobil-wp')
        );
    }

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
        'sslverify' => RENTAL_MOBIL_LICENSE_SSL_VERIFY
    ));

    // Jika metode GET gagal, coba metode POST sebagai fallback
    if (is_wp_error($response)) {
        rental_mobil_license_debug_log('Metode GET gagal, mencoba metode POST', array(
            'error' => $response->get_error_message()
        ));

        $response = wp_remote_post(RENTAL_MOBIL_LICENSE_API_URL . '/verify', array(
            'timeout' => 15,
            'sslverify' => RENTAL_MOBIL_LICENSE_SSL_VERIFY,
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
    if (isset($response_data['success'])) {
        // Log data respons untuk debugging
        rental_mobil_license_debug_log('Data respons API', array(
            'response_data' => $response_data,
            'success' => $response_data['success'] ? 'yes' : 'no'
        ));

        // Simpan data tambahan seperti tanggal kedaluwarsa dan nama pelanggan
        $options = rental_mobil_get_options();

        // Jika respons sukses dan ada data
        if ($response_data['success'] && isset($response_data['data'])) {
            // Periksa status lisensi dari respons API
            $api_status = isset($response_data['data']['status']) ? $response_data['data']['status'] : '';

            // Log status dari API
            rental_mobil_license_debug_log('Status lisensi dari API', array(
                'api_status' => $api_status
            ));

            // Jika status dari API adalah 'active', set status lisensi ke 'valid'
            if ($api_status === 'active') {
                $options['license_status'] = 'valid';
            } else {
                // Jika status bukan 'active', set status lisensi ke 'invalid'
                $options['license_status'] = 'invalid';

                // Log status tidak aktif
                rental_mobil_license_debug_log('Status lisensi dari API tidak aktif', array(
                    'api_status' => $api_status
                ));

                // Return dengan pesan error
                return array(
                    'success' => false,
                    'message' => __('Lisensi tidak aktif di server.', 'rental-mobil-wp')
                );
            }
        } else {
            // Jika respons tidak sukses, set status lisensi ke 'invalid'
            $options['license_status'] = 'invalid';

            // Log status tidak valid
            rental_mobil_license_debug_log('Respons API tidak sukses', array(
                'success' => $response_data['success'] ? 'yes' : 'no',
                'message' => isset($response_data['message']) ? $response_data['message'] : 'tidak ada pesan'
            ));

            // Return dengan pesan error dari API atau pesan default
            return array(
                'success' => false,
                'message' => isset($response_data['message']) ? $response_data['message'] : __('Lisensi tidak valid.', 'rental-mobil-wp')
            );
        }

        // Tanggal kedaluwarsa (periksa kedua format yang mungkin)
        if (isset($response_data['data']['expires_at'])) {
            $options['license_expires'] = $response_data['data']['expires_at'];
        } elseif (isset($response_data['data']['expires'])) {
            $options['license_expires'] = $response_data['data']['expires'];
        }

        // Nama pelanggan
        if (isset($response_data['data']['customer_name'])) {
            $options['license_customer'] = $response_data['data']['customer_name'];
        }

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

        // Simpan kunci lisensi
        $options['license_key'] = isset($response_data['data']['license_key']) ? $response_data['data']['license_key'] : $license_key;

        // Simpan opsi dengan autoload=yes untuk memastikan selalu tersedia
        $update_result = update_option('rental_mobil_options', $options, 'yes');

        // Refresh opsi dari database untuk memastikan konsistensi
        wp_cache_delete('rental_mobil_options', 'options');
        wp_cache_delete('alloptions', 'options');

        // Verifikasi bahwa status lisensi telah disimpan dengan benar
        $saved_options = get_option('rental_mobil_options', array(), false);

        rental_mobil_license_debug_log('Lisensi valid', array(
            'customer' => isset($options['license_customer']) ? $options['license_customer'] : 'tidak diketahui',
            'expires' => isset($options['license_expires']) ? $options['license_expires'] : 'tidak diketahui',
            'created_at' => isset($options['license_created_at']) ? $options['license_created_at'] : 'tidak diketahui',
            'domain' => parse_url(home_url(), PHP_URL_HOST),
            'update_result' => $update_result ? 'success' : 'failed',
            'saved_status' => isset($saved_options['license_status']) ? $saved_options['license_status'] : 'not set'
        ));
    } else {
        // Jika tidak sukses, simpan status lisensi tidak valid
        $options = rental_mobil_get_options();
        $options['license_status'] = 'invalid';

        // Simpan opsi dengan autoload=yes untuk memastikan selalu tersedia
        $update_result = update_option('rental_mobil_options', $options, 'yes');

        // Refresh opsi dari database untuk memastikan konsistensi
        wp_cache_delete('rental_mobil_options', 'options');
        wp_cache_delete('alloptions', 'options');

        // Verifikasi bahwa status lisensi telah disimpan dengan benar
        $saved_options = get_option('rental_mobil_options', array(), false);

        rental_mobil_license_debug_log('Lisensi tidak valid', array(
            'response' => $response_data,
            'domain' => parse_url(home_url(), PHP_URL_HOST),
            'update_result' => $update_result ? 'success' : 'failed',
            'saved_status' => isset($saved_options['license_status']) ? $saved_options['license_status'] : 'not set'
        ));
    }

    return $response_data;
}

/**
 * AJAX handler untuk memeriksa status lisensi
 */
function rental_mobil_ajax_check_license_status() {
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

    // Log debug
    rental_mobil_license_debug_log('Hasil pemeriksaan status lisensi', array(
        'response' => $response,
        'domain' => parse_url(home_url(), PHP_URL_HOST)
    ));

    if ($response['success']) {
        // Simpan status lisensi dan semua data terkait
        $options = rental_mobil_get_options();
        $options['license_status'] = 'valid';

        // Simpan data dari respons API
        if (isset($response['data'])) {
            // Tanggal kedaluwarsa (periksa kedua format yang mungkin)
            if (isset($response['data']['expires_at'])) {
                $options['license_expires'] = $response['data']['expires_at'];
            } elseif (isset($response['data']['expires'])) {
                $options['license_expires'] = $response['data']['expires'];
            }

            // Nama pelanggan
            if (isset($response['data']['customer_name'])) {
                $options['license_customer'] = $response['data']['customer_name'];
            }

            // Tanggal pembuatan lisensi
            if (isset($response['data']['created_at'])) {
                $options['license_created_at'] = $response['data']['created_at'];
            } else {
                // Jika tidak tersedia, gunakan tanggal saat ini
                $options['license_created_at'] = date('Y-m-d');
            }

            // Domain terdaftar
            if (isset($response['data']['domain_count'])) {
                $options['license_domain_count'] = $response['data']['domain_count'];
            }

            // Jumlah maksimal domain
            if (isset($response['data']['max_domains'])) {
                $options['license_max_domains'] = $response['data']['max_domains'];
            }
        }

        // Simpan opsi dengan autoload=yes untuk memastikan selalu tersedia
        $update_result = update_option('rental_mobil_options', $options, 'yes');

        // Log hasil update untuk debugging
        rental_mobil_license_debug_log('Hasil update opsi pemeriksaan status lisensi', array(
            'update_result' => $update_result ? 'success' : 'failed',
            'license_status' => $options['license_status'],
            'options' => $options
        ));

        // Refresh opsi dari database untuk memastikan konsistensi
        wp_cache_delete('rental_mobil_options', 'options');
        wp_cache_delete('alloptions', 'options');

        // Verifikasi bahwa status lisensi telah disimpan dengan benar
        $saved_options = get_option('rental_mobil_options', array(), false);
        rental_mobil_license_debug_log('Status lisensi setelah pemeriksaan', array(
            'saved_status' => isset($saved_options['license_status']) ? $saved_options['license_status'] : 'not set'
        ));

        wp_send_json_success(array(
            'message' => __('Status lisensi berhasil diperbarui.', 'rental-mobil-wp'),
            'status' => 'valid',
            'expires' => isset($options['license_expires']) ? $options['license_expires'] : '',
            'customer' => isset($options['license_customer']) ? $options['license_customer'] : ''
        ));
    } else {
        // Simpan status lisensi tidak valid
        $options = rental_mobil_get_options();
        $options['license_status'] = 'invalid';

        // Simpan opsi dengan autoload=yes untuk memastikan selalu tersedia
        $update_result = update_option('rental_mobil_options', $options, 'yes');

        // Log hasil update untuk debugging
        rental_mobil_license_debug_log('Hasil update opsi status lisensi tidak valid', array(
            'update_result' => $update_result ? 'success' : 'failed',
            'license_status' => $options['license_status']
        ));

        // Refresh opsi dari database untuk memastikan konsistensi
        wp_cache_delete('rental_mobil_options', 'options');
        wp_cache_delete('alloptions', 'options');

        // Verifikasi bahwa status lisensi telah disimpan dengan benar
        $saved_options = get_option('rental_mobil_options', array(), false);
        rental_mobil_license_debug_log('Status lisensi setelah gagal pemeriksaan', array(
            'saved_status' => isset($saved_options['license_status']) ? $saved_options['license_status'] : 'not set'
        ));

        wp_send_json_error(array('message' => $response['message']));
    }
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

    // Gunakan URL yang benar untuk deaktivasi lisensi
    // Format: https://verifikasi.tupski.web.id/api/deactivate/?license_key=XXX&domain=XXX
    $url = RENTAL_MOBIL_LICENSE_API_URL . '/deactivate/';
    $url = add_query_arg(
        array(
            'license_key' => $license_key,
            'domain' => $domain
        ),
        $url
    );

    rental_mobil_license_debug_log('Mencoba deaktivasi dengan URL', array('url' => $url));

    // Gunakan metode GET untuk deaktivasi
    $response = wp_remote_get($url, array(
        'timeout' => 15,
        'sslverify' => RENTAL_MOBIL_LICENSE_SSL_VERIFY
    ));

    // Cek error
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        rental_mobil_license_debug_log('Deaktivasi gagal', array('error' => $error_message));

        // Coba metode alternatif dengan URL yang berbeda
        $alt_url = 'https://verifikasi.tupski.web.id/api/deactivate/?license_key=' . urlencode($license_key) . '&domain=' . urlencode($domain);
        rental_mobil_license_debug_log('Mencoba metode alternatif', array('url' => $alt_url));

        $response = wp_remote_get($alt_url, array(
            'timeout' => 15,
            'sslverify' => RENTAL_MOBIL_LICENSE_SSL_VERIFY
        ));

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            rental_mobil_license_debug_log('Metode alternatif juga gagal', array('error' => $error_message));

            return array(
                'success' => false,
                'message' => $error_message
            );
        }
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

        // Jika respons tidak valid, coba parse respons sebagai string
        if (strpos($response_body, 'success') !== false) {
            rental_mobil_license_debug_log('Mencoba parse respons sebagai string', array('body' => $response_body));

            // Jika respons berisi "success", anggap deaktivasi berhasil
            return array(
                'success' => true,
                'message' => __('Lisensi berhasil dinonaktifkan.', 'rental-mobil-wp')
            );
        }

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
    // Dapatkan opsi
    $options = rental_mobil_get_options();

    // Periksa apakah lisensi baru saja dinonaktifkan
    if (isset($options['license_deactivated']) && $options['license_deactivated'] === 'yes') {
        // Jika lisensi dinonaktifkan dalam 5 menit terakhir, jangan periksa lagi
        if (isset($options['license_deactivated_time']) && (time() - $options['license_deactivated_time']) < 300) {
            rental_mobil_license_debug_log('Melewati pemeriksaan berkala lisensi karena baru saja dinonaktifkan');
            return;
        } else {
            // Jika sudah lebih dari 5 menit, hapus flag deaktivasi
            unset($options['license_deactivated']);
            unset($options['license_deactivated_time']);
            update_option('rental_mobil_options', $options, 'yes');

            // Refresh opsi dari database untuk memastikan konsistensi
            wp_cache_delete('rental_mobil_options', 'options');
            wp_cache_delete('alloptions', 'options');
        }
    }

    // Dapatkan kunci lisensi
    $license_key = rental_mobil_get_license_key();
    $license_status = rental_mobil_get_license_status();

    // Jika tidak ada kunci lisensi, tidak perlu cek
    if (empty($license_key)) {
        // Pastikan status lisensi kosong
        if (!empty($license_status)) {
            $options = rental_mobil_get_options();
            $options['license_status'] = '';
            update_option('rental_mobil_options', $options, 'yes');

            // Refresh opsi dari database untuk memastikan konsistensi
            wp_cache_delete('rental_mobil_options', 'options');
            wp_cache_delete('alloptions', 'options');

            rental_mobil_license_debug_log('Mengosongkan status lisensi dari pengecekan berkala karena kunci lisensi kosong');
        }
        return;
    }

    // Jika status tidak valid, tidak perlu cek
    if ($license_status !== 'valid') {
        return;
    }

    // Verifikasi lisensi
    $response = rental_mobil_verify_license($license_key);

    // Log hasil verifikasi
    rental_mobil_license_debug_log('Hasil verifikasi lisensi dari pengecekan berkala', array(
        'response' => $response,
        'domain' => parse_url(home_url(), PHP_URL_HOST)
    ));

    // Update status lisensi dan semua data terkait
    $options = rental_mobil_get_options();

    if ($response['success']) {
        $options['license_status'] = 'valid';

        // Simpan data dari respons API
        if (isset($response['data'])) {
            // Tanggal kedaluwarsa (periksa kedua format yang mungkin)
            if (isset($response['data']['expires_at'])) {
                $options['license_expires'] = $response['data']['expires_at'];
            } elseif (isset($response['data']['expires'])) {
                $options['license_expires'] = $response['data']['expires'];
            }

            // Nama pelanggan
            if (isset($response['data']['customer_name'])) {
                $options['license_customer'] = $response['data']['customer_name'];
            }

            // Domain terdaftar
            if (isset($response['data']['domain_count'])) {
                $options['license_domain_count'] = $response['data']['domain_count'];
            }

            // Jumlah maksimal domain
            if (isset($response['data']['max_domains'])) {
                $options['license_max_domains'] = $response['data']['max_domains'];
            }

            // Hapus flag deaktivasi jika ada
            if (isset($options['license_deactivated'])) {
                unset($options['license_deactivated']);
            }
            if (isset($options['license_deactivated_time'])) {
                unset($options['license_deactivated_time']);
            }
        }
    } else {
        $options['license_status'] = 'invalid';
    }

    // Simpan opsi dengan autoload=yes untuk memastikan selalu tersedia
    $update_result = update_option('rental_mobil_options', $options, 'yes');

    // Log hasil update untuk debugging
    rental_mobil_license_debug_log('Hasil update opsi dari pengecekan berkala', array(
        'update_result' => $update_result ? 'success' : 'failed',
        'license_status' => $options['license_status']
    ));

    // Refresh opsi dari database untuk memastikan konsistensi
    wp_cache_delete('rental_mobil_options', 'options');
    wp_cache_delete('alloptions', 'options');

    // Verifikasi bahwa status lisensi telah disimpan dengan benar
    $saved_options = get_option('rental_mobil_options', array(), false);
    rental_mobil_license_debug_log('Status lisensi setelah pengecekan berkala', array(
        'saved_status' => isset($saved_options['license_status']) ? $saved_options['license_status'] : 'not set',
        'saved_key' => isset($saved_options['license_key']) ? $saved_options['license_key'] : 'not set'
    ));
}

/**
 * Cek lisensi saat halaman pengaturan lisensi dimuat
 */
function rental_mobil_maybe_check_license() {
    // Cek apakah kita berada di halaman pengaturan lisensi
    if (isset($_GET['page']) && $_GET['page'] === 'rental-mobil' &&
        isset($_GET['tab']) && $_GET['tab'] === 'license') {

        // Dapatkan opsi
        $options = rental_mobil_get_options();

        // Periksa apakah lisensi baru saja dinonaktifkan
        if (isset($options['license_deactivated']) && $options['license_deactivated'] === 'yes') {
            // Jika lisensi dinonaktifkan dalam 5 menit terakhir, jangan periksa lagi
            if (isset($options['license_deactivated_time']) && (time() - $options['license_deactivated_time']) < 300) {
                rental_mobil_license_debug_log('Melewati pemeriksaan lisensi dari halaman pengaturan karena baru saja dinonaktifkan');

                // Pastikan status lisensi kosong
                if (!empty($options['license_status'])) {
                    $options['license_status'] = '';
                    update_option('rental_mobil_options', $options, 'yes');

                    // Refresh opsi dari database untuk memastikan konsistensi
                    wp_cache_delete('rental_mobil_options', 'options');
                    wp_cache_delete('alloptions', 'options');
                }

                return;
            } else {
                // Jika sudah lebih dari 5 menit, hapus flag deaktivasi
                unset($options['license_deactivated']);
                unset($options['license_deactivated_time']);
                update_option('rental_mobil_options', $options, 'yes');

                // Refresh opsi dari database untuk memastikan konsistensi
                wp_cache_delete('rental_mobil_options', 'options');
                wp_cache_delete('alloptions', 'options');
            }
        }

        // Dapatkan kunci lisensi
        $license_key = rental_mobil_get_license_key();

        // Jika ada kunci lisensi, cek statusnya
        if (!empty($license_key)) {
            rental_mobil_license_debug_log('Memaksa pengecekan lisensi dari halaman pengaturan', array(
                'license_key' => $license_key,
                'domain' => parse_url(home_url(), PHP_URL_HOST)
            ));

            // Verifikasi lisensi
            $response = rental_mobil_verify_license($license_key);

            // Log hasil verifikasi
            rental_mobil_license_debug_log('Hasil verifikasi lisensi dari halaman pengaturan', array(
                'response' => $response,
                'domain' => parse_url(home_url(), PHP_URL_HOST)
            ));

            // Pastikan status lisensi diperbarui
            $options = rental_mobil_get_options();

            if ($response['success']) {
                $options['license_status'] = 'valid';

                // Hapus flag deaktivasi jika ada
                if (isset($options['license_deactivated'])) {
                    unset($options['license_deactivated']);
                }
                if (isset($options['license_deactivated_time'])) {
                    unset($options['license_deactivated_time']);
                }
            } else {
                $options['license_status'] = 'invalid';
            }

            // Simpan opsi dengan autoload=yes untuk memastikan selalu tersedia
            $update_result = update_option('rental_mobil_options', $options, 'yes');

            // Refresh opsi dari database untuk memastikan konsistensi
            wp_cache_delete('rental_mobil_options', 'options');
            wp_cache_delete('alloptions', 'options');

            // Verifikasi bahwa status lisensi telah disimpan dengan benar
            $saved_options = get_option('rental_mobil_options', array(), false);
            rental_mobil_license_debug_log('Status lisensi setelah pengecekan dari halaman pengaturan', array(
                'saved_status' => isset($saved_options['license_status']) ? $saved_options['license_status'] : 'not set',
                'saved_key' => isset($saved_options['license_key']) ? $saved_options['license_key'] : 'not set'
            ));
        } else {
            // Jika tidak ada kunci lisensi, pastikan status lisensi kosong
            $options = rental_mobil_get_options();
            if (!empty($options['license_status'])) {
                $options['license_status'] = '';
                update_option('rental_mobil_options', $options, 'yes');

                // Refresh opsi dari database untuk memastikan konsistensi
                wp_cache_delete('rental_mobil_options', 'options');
                wp_cache_delete('alloptions', 'options');

                rental_mobil_license_debug_log('Mengosongkan status lisensi dari halaman pengaturan karena kunci lisensi kosong');
            }
        }
    }
}

/**
 * Cek apakah lisensi valid
 */
function rental_mobil_is_license_valid() {
    // Pembersihan cache yang agresif
    wp_cache_delete('rental_mobil_options', 'options');
    wp_cache_delete('alloptions', 'options');
    wp_cache_flush();

    // Dapatkan opsi langsung dari database dengan force refresh
    global $wpdb;
    $option_name = 'rental_mobil_options';

    // Dapatkan nilai opsi langsung dari database
    $option_value = $wpdb->get_var($wpdb->prepare(
        "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
        $option_name
    ));

    if (empty($option_value)) {
        rental_mobil_license_debug_log('Opsi rental_mobil_options tidak ditemukan di database');
        return false;
    }

    // Unserialize nilai opsi
    $options = maybe_unserialize($option_value);

    if (!is_array($options)) {
        rental_mobil_license_debug_log('Opsi rental_mobil_options bukan array yang valid');
        return false;
    }

    // Log opsi untuk debugging
    rental_mobil_license_debug_log('Opsi rental_mobil_options dari database', array(
        'has_license_key' => isset($options['license_key']) ? 'yes' : 'no',
        'has_license_status' => isset($options['license_status']) ? 'yes' : 'no',
        'license_key_empty' => isset($options['license_key']) && empty($options['license_key']) ? 'yes' : 'no',
        'license_status_valid' => isset($options['license_status']) && $options['license_status'] === 'valid' ? 'yes' : 'no'
    ));

    // Periksa apakah lisensi baru saja dinonaktifkan
    if (isset($options['license_deactivated']) && $options['license_deactivated'] === 'yes') {
        // Jika lisensi dinonaktifkan, anggap tidak valid
        rental_mobil_license_debug_log('Lisensi dianggap tidak valid karena baru saja dinonaktifkan');
        return false;
    }

    // Periksa apakah kunci lisensi kosong
    $license_key = isset($options['license_key']) ? $options['license_key'] : '';
    if (empty($license_key)) {
        rental_mobil_license_debug_log('Lisensi dianggap tidak valid karena kunci lisensi kosong');
        return false;
    }

    $license_status = isset($options['license_status']) ? $options['license_status'] : '';

    // Jika status lisensi kosong, anggap tidak valid
    if (empty($license_status)) {
        rental_mobil_license_debug_log('Lisensi dianggap tidak valid karena status lisensi kosong');
        return false;
    }

    // Log status lisensi untuk debugging
    rental_mobil_license_debug_log('Pemeriksaan status lisensi', array(
        'license_key' => $license_key,
        'license_status' => $license_status,
        'is_valid' => $license_status === 'valid' ? 'yes' : 'no'
    ));

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
        if (!wp_mkdir_p($log_dir)) {
            // Jika gagal membuat direktori, coba gunakan direktori uploads
            $upload_dir = wp_upload_dir();
            $log_dir = $upload_dir['basedir'] . '/rental-mobil-logs';

            if (!file_exists($log_dir)) {
                wp_mkdir_p($log_dir);
            }
        }
    }

    // Buat file .htaccess untuk melindungi direktori log
    $htaccess_file = $log_dir . '/.htaccess';
    if (!file_exists($htaccess_file)) {
        $htaccess_content = "Order deny,allow\nDeny from all";
        @file_put_contents($htaccess_file, $htaccess_content);
    }

    // Buat file index.php untuk melindungi direktori log
    $index_file = $log_dir . '/index.php';
    if (!file_exists($index_file)) {
        $index_content = "<?php\n// Silence is golden.";
        @file_put_contents($index_file, $index_content);
    }

    // Format log
    $log_entry = '[' . date('Y-m-d H:i:s') . '] ' . $message;

    if (!empty($data)) {
        // Sanitasi data sensitif
        $sanitized_data = $data;

        // Sembunyikan kunci lisensi dalam log
        if (isset($sanitized_data['license_key'])) {
            $license_key = $sanitized_data['license_key'];
            if (strlen($license_key) > 4) {
                $sanitized_data['license_key'] = '****-****-****-' . substr($license_key, -4);
            }
        }

        // Sanitasi data POST
        if (isset($sanitized_data['post_data']) && isset($sanitized_data['post_data']['license_key'])) {
            $license_key = $sanitized_data['post_data']['license_key'];
            if (strlen($license_key) > 4) {
                $sanitized_data['post_data']['license_key'] = '****-****-****-' . substr($license_key, -4);
            }
        }

        // Sanitasi data respons
        if (isset($sanitized_data['response']) && isset($sanitized_data['response']['data']) && isset($sanitized_data['response']['data']['license_key'])) {
            $license_key = $sanitized_data['response']['data']['license_key'];
            if (strlen($license_key) > 4) {
                $sanitized_data['response']['data']['license_key'] = '****-****-****-' . substr($license_key, -4);
            }
        }

        // Sanitasi data opsi
        if (isset($sanitized_data['options']) && isset($sanitized_data['options']['license_key'])) {
            $license_key = $sanitized_data['options']['license_key'];
            if (strlen($license_key) > 4) {
                $sanitized_data['options']['license_key'] = '****-****-****-' . substr($license_key, -4);
            }
        }

        // Sanitasi data saved_key
        if (isset($sanitized_data['saved_key'])) {
            $license_key = $sanitized_data['saved_key'];
            if (strlen($license_key) > 4) {
                $sanitized_data['saved_key'] = '****-****-****-' . substr($license_key, -4);
            }
        }

        // Encode data sebagai JSON
        $json_data = json_encode($sanitized_data, JSON_PRETTY_PRINT);
        if ($json_data === false) {
            // Jika gagal encode, gunakan var_export
            $json_data = var_export($sanitized_data, true);
        }

        $log_entry .= ' | Data: ' . $json_data;
    }

    $log_entry .= "\n";

    // Tulis log ke file
    $log_file = $log_dir . '/license-debug.log';

    // Batasi ukuran file log (maksimal 1MB)
    if (file_exists($log_file) && filesize($log_file) > 1048576) {
        // Jika file terlalu besar, buat file backup dan mulai file baru
        $backup_file = $log_dir . '/license-debug-' . date('Y-m-d-H-i-s') . '.log';
        @rename($log_file, $backup_file);
    }

    // Tulis log ke file dengan error handling
    if (!@file_put_contents($log_file, $log_entry, FILE_APPEND)) {
        // Jika gagal menulis ke file, coba tulis ke error log PHP
        error_log('Rental Mobil License Debug: ' . $log_entry);
    }

    // Selalu log ke error_log untuk debugging lebih mudah
    error_log('RENTAL MOBIL LICENSE: ' . $message);
}
