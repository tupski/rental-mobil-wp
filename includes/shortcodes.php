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
    $atts = shortcode_atts(array(
        'jumlah' => -1,
        'merk' => '',
        'transmisi' => '',
        'bahan_bakar' => '',
        'tipe' => '',
        'tahun' => '',
        'orderby' => 'date',
        'order' => 'DESC',
    ), $atts, 'daftar_kendaraan');

    // Mulai output buffering
    ob_start();

    // Buka container full width
    echo '<div class="rental-mobil-container">';

    // Tampilkan layout baru
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/layout-kendaraan.php';

    // Tampilkan modal booking
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/booking-modal.php';

    // Tampilkan modal quick view
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/quick-view-modal.php';

    // Tampilkan modal zoom
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/zoom-modal.php';

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

        // Tampilkan modal booking
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/booking-modal.php';

        // Tampilkan modal quick view
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/quick-view-modal.php';

        // Tampilkan modal zoom
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/zoom-modal.php';
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
    $atts = shortcode_atts(array(
        'jumlah' => 5,
        'tipe' => 'featured', // 'featured' atau 'popular'
        'judul' => '',
    ), $atts, 'kendaraan_unggulan');

    // Mulai output buffering
    ob_start();

    // Set parameter untuk template
    $type = $atts['tipe'];
    $limit = $atts['jumlah'];
    $title = !empty($atts['judul']) ? $atts['judul'] : '';

    // Tampilkan slider
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/slider-kendaraan.php';

    // Tampilkan modal booking
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/booking-modal.php';

    // Tampilkan modal quick view
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/quick-view-modal.php';

    // Tampilkan modal zoom
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/zoom-modal.php';

    // Ambil output buffering dan kembalikan
    return ob_get_clean();
}

/**
 * Shortcode untuk menampilkan kendaraan pilihan admin di homepage
 */
add_shortcode('kendaraan_pilihan', 'rental_mobil_pilihan_shortcode');
function rental_mobil_pilihan_shortcode($atts) {
    $atts = shortcode_atts(array(
        'judul' => __('Kendaraan Pilihan', 'rental-mobil-wp'),
        'jumlah' => -1,
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
        'orderby' => 'post__in', // Mempertahankan urutan dari array
    );

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

        // Tampilkan modal booking
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/booking-modal.php';

        // Tampilkan modal quick view
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/quick-view-modal.php';

        // Tampilkan modal zoom
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/zoom-modal.php';
    } else {
        echo '<p>' . __('Tidak ada kendaraan yang ditemukan.', 'rental-mobil-wp') . '</p>';
    }

    wp_reset_postdata();

    // Ambil output buffering dan kembalikan
    return ob_get_clean();
}
