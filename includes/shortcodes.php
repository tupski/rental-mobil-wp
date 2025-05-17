<?php
/**
 * Register Shortcodes
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Shortcode untuk menampilkan daftar kendaraan
 */
add_shortcode('daftar_kendaraan', 'rental_mobil_daftar_shortcode');
function rental_mobil_daftar_shortcode($atts) {
    // Cek lisensi
    if (!rental_mobil_is_license_valid()) {
        return rental_mobil_license_notice();
    }

    // Dapatkan pengaturan urutan kendaraan shortcode
    $order_settings = rental_mobil_get_shortcode_order_settings();

    $atts = shortcode_atts(array(
        'jumlah' => -1,
        'merk' => '',
        'transmisi' => '',
        'bahan_bakar' => '',
        'tipe' => '',
        'tahun' => '',
        'orderby' => $order_settings['orderby'],
        'order' => $order_settings['order'],
    ), $atts, 'daftar_kendaraan');

    // Mulai output buffering
    ob_start();

    // Buka container full width
    echo '<div class="rental-mobil-container">';

    // Tampilkan layout baru
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/layout-kendaraan.php';

    // Tandai bahwa shortcode daftar kendaraan digunakan
    global $rental_mobil_shortcodes_used;
    $rental_mobil_shortcodes_used['daftar_kendaraan'] = true;

    // Tutup container
    echo '</div>';

    // Ambil output buffering dan kembalikan
    return ob_get_clean();
}

/**
 * Shortcode untuk menampilkan detail kendaraan
 */
add_shortcode('detail_kendaraan', 'rental_mobil_detail_shortcode');
function rental_mobil_detail_shortcode($atts) {
    // Cek lisensi
    if (!rental_mobil_is_license_valid()) {
        return rental_mobil_license_notice();
    }

    $atts = shortcode_atts(array(
        'id' => 0,
    ), $atts, 'detail_kendaraan');

    // Jika tidak ada ID, gunakan post ID saat ini
    if (empty($atts['id'])) {
        global $post;
        if ($post && $post->post_type === 'kendaraan') {
            $atts['id'] = $post->ID;
        } else {
            return '<p>' . __('ID kendaraan tidak valid.', 'rental-mobil-wp') . '</p>';
        }
    }

    // Mulai output buffering
    ob_start();

    // Dapatkan kendaraan
    $kendaraan = get_post($atts['id']);

    if ($kendaraan && $kendaraan->post_type === 'kendaraan') {
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/detail-kendaraan.php';

        // Tandai bahwa shortcode detail kendaraan digunakan
        global $rental_mobil_shortcodes_used;
        $rental_mobil_shortcodes_used['detail_kendaraan'] = true;
    } else {
        echo '<p>' . __('Kendaraan tidak ditemukan.', 'rental-mobil-wp') . '</p>';
    }

    // Ambil output buffering dan kembalikan
    return ob_get_clean();
}

/**
 * Shortcode untuk menampilkan kendaraan unggulan
 */
add_shortcode('kendaraan_unggulan', 'rental_mobil_unggulan_shortcode');
function rental_mobil_unggulan_shortcode($atts) {
    // Cek apakah fitur kendaraan unggulan diaktifkan
    if (!rental_mobil_is_feature_enabled('kendaraan_unggulan')) {
        return rental_mobil_license_notice();
    }

    // Dapatkan pengaturan slider
    $slider_settings = rental_mobil_get_slider_settings();

    // Dapatkan pengaturan urutan kendaraan
    $order_settings = rental_mobil_get_homepage_order_settings();

    $atts = shortcode_atts(array(
        'jumlah' => 5,
        'tipe' => 'featured', // 'featured' atau 'popular'
        'judul' => '',
        'auto_slide' => $slider_settings['auto_slide'] ? 'true' : 'false',
        'loop' => $slider_settings['loop'] ? 'true' : 'false',
        'speed' => $slider_settings['speed'],
        'interval' => $slider_settings['interval'],
        'orderby' => $order_settings['orderby'],
        'order' => $order_settings['order'],
    ), $atts, 'kendaraan_unggulan');

    // Mulai output buffering
    ob_start();

    // Set parameter untuk template
    $type = $atts['tipe'];
    $limit = $atts['jumlah'];
    $title = !empty($atts['judul']) ? $atts['judul'] : '';
    $auto_slide = filter_var($atts['auto_slide'], FILTER_VALIDATE_BOOLEAN);
    $loop = filter_var($atts['loop'], FILTER_VALIDATE_BOOLEAN);
    $speed = absint($atts['speed']);
    $interval = absint($atts['interval']);
    $orderby = $atts['orderby'];
    $order = $atts['order'];

    // Tampilkan slider
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/slider-kendaraan.php';

    // Tandai bahwa shortcode kendaraan unggulan digunakan
    global $rental_mobil_shortcodes_used;
    $rental_mobil_shortcodes_used['kendaraan_unggulan'] = true;

    // Ambil output buffering dan kembalikan
    return ob_get_clean();
}

/**
 * Shortcode untuk menampilkan kendaraan pilihan admin di homepage
 */
add_shortcode('kendaraan_pilihan', 'rental_mobil_pilihan_shortcode');
function rental_mobil_pilihan_shortcode($atts) {
    // Cek apakah fitur kendaraan pilihan diaktifkan
    if (!rental_mobil_is_feature_enabled('kendaraan_pilihan')) {
        return rental_mobil_license_notice();
    }

    // Dapatkan pengaturan urutan kendaraan
    $order_settings = rental_mobil_get_homepage_order_settings();

    $atts = shortcode_atts(array(
        'judul' => __('Kendaraan Pilihan', 'rental-mobil-wp'),
        'jumlah' => -1,
        'orderby' => $order_settings['orderby'],
        'order' => $order_settings['order'],
    ), $atts, 'kendaraan_pilihan');

    // Mulai output buffering
    ob_start();

    // Dapatkan kendaraan pilihan dari pengaturan
    $homepage_vehicles = rental_mobil_get_homepage_vehicles();

    if (empty($homepage_vehicles)) {
        return '<p>' . __('Belum ada kendaraan pilihan yang ditambahkan. Silakan tambahkan di pengaturan plugin.', 'rental-mobil-wp') . '</p>';
    }

    // Batasi jumlah kendaraan jika diperlukan
    if ($atts['jumlah'] > 0 && count($homepage_vehicles) > $atts['jumlah']) {
        $homepage_vehicles = array_slice($homepage_vehicles, 0, $atts['jumlah']);
    }

    // Query kendaraan
    $args = array(
        'post_type' => 'kendaraan',
        'posts_per_page' => -1,
        'post__in' => $homepage_vehicles,
    );

    // Jika orderby adalah meta_value_num (harga) atau harga_harian, tambahkan meta_key
    if ($atts['orderby'] === 'meta_value_num') {
        $args['meta_key'] = '_rental_mobil_harga_sewa';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = $atts['order'];
    } elseif ($atts['orderby'] === 'harga_harian') {
        $args['meta_key'] = '_rental_mobil_harga_sewa';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = $atts['order'];
    } elseif ($atts['orderby'] !== 'post__in') {
        // Gunakan pengaturan urutan kecuali jika orderby adalah post__in
        $args['orderby'] = $atts['orderby'];
        $args['order'] = $atts['order'];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="rental-mobil-homepage-section">';
        echo '<h2 class="rental-mobil-homepage-title">' . esc_html($atts['judul']) . '</h2>';

        echo '<div class="rental-mobil-grid">';
        while ($query->have_posts()) {
            $query->the_post();
            include RENTAL_MOBIL_PLUGIN_DIR . 'templates/card-kendaraan.php';
        }
        echo '</div>';

        echo '</div>';

        // Tandai bahwa shortcode kendaraan grid digunakan
        global $rental_mobil_shortcodes_used;
        $rental_mobil_shortcodes_used['kendaraan_grid'] = true;
    } else {
        echo '<p>' . __('Tidak ada kendaraan yang ditemukan.', 'rental-mobil-wp') . '</p>';
    }

    wp_reset_postdata();

    // Ambil output buffering dan kembalikan
    return ob_get_clean();
}

/**
 * Fungsi untuk menampilkan pesan lisensi tidak valid
 */
function rental_mobil_license_notice() {
    $license_status = rental_mobil_get_license_status();
    $license_key = rental_mobil_get_license_key();

    $message = '<div class="rental-mobil-license-notice">';

    if (empty($license_key)) {
        $message .= '<h3>' . __('Lisensi Belum Diaktifkan', 'rental-mobil-wp') . '</h3>';
        $message .= '<p>' . __('Plugin Rental Mobil WP memerlukan lisensi yang valid untuk menggunakan fitur premium. Silakan aktivasi lisensi Anda di halaman pengaturan plugin.', 'rental-mobil-wp') . '</p>';
    } elseif ($license_status === 'invalid') {
        $message .= '<h3>' . __('Lisensi Tidak Valid', 'rental-mobil-wp') . '</h3>';
        $message .= '<p>' . __('Lisensi yang Anda masukkan tidak valid. Silakan periksa kembali atau hubungi dukungan.', 'rental-mobil-wp') . '</p>';
    } elseif ($license_status === 'expired') {
        $message .= '<h3>' . __('Lisensi Kedaluwarsa', 'rental-mobil-wp') . '</h3>';
        $message .= '<p>' . __('Lisensi Anda telah kedaluwarsa. Silakan perbarui lisensi Anda untuk terus menggunakan fitur premium.', 'rental-mobil-wp') . '</p>';
    } else {
        $message .= '<h3>' . __('Lisensi Tidak Valid', 'rental-mobil-wp') . '</h3>';
        $message .= '<p>' . __('Plugin Rental Mobil WP memerlukan lisensi yang valid untuk menggunakan fitur premium. Silakan aktivasi lisensi Anda di halaman pengaturan plugin.', 'rental-mobil-wp') . '</p>';
    }

    // Tampilkan tombol aktivasi lisensi
    $message .= '<p><a href="' . admin_url('admin.php?page=rental-mobil&tab=license') . '" class="button button-primary">' . __('Kelola Lisensi', 'rental-mobil-wp') . '</a></p>';

    // Tampilkan informasi untuk mendapatkan lisensi
    $message .= '<p class="rental-mobil-license-info">' . __('Belum memiliki lisensi? Kunjungi <a href="https://tupski.web.id/rental-mobil-wp" target="_blank">tupski.web.id</a> untuk mendapatkan lisensi.', 'rental-mobil-wp') . '</p>';

    $message .= '</div>';

    return $message;
}