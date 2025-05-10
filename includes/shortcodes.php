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

    // Tampilkan filter
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/filter-kendaraan.php';

    // Query kendaraan
    $args = array(
        'post_type'      => 'kendaraan',
        'posts_per_page' => $atts['jumlah'],
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
    );

    // Gunakan tax_query dengan optimasi untuk filter berdasarkan taxonomy

    // Tambahkan filter berdasarkan parameter
    if (!empty($atts['merk'])) {
        $merk_terms = get_terms(array(
            'taxonomy' => 'merk_kendaraan',
            'slug' => explode(',', $atts['merk']),
            'fields' => 'ids',
        ));

        if (!empty($merk_terms) && !is_wp_error($merk_terms)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'merk_kendaraan',
                'field'    => 'term_id',
                'terms'    => $merk_terms,
                'operator' => 'IN',
            );
        }
    }

    if (!empty($atts['transmisi'])) {
        $transmisi_terms = get_terms(array(
            'taxonomy' => 'transmisi',
            'slug' => explode(',', $atts['transmisi']),
            'fields' => 'ids',
        ));

        if (!empty($transmisi_terms) && !is_wp_error($transmisi_terms)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'transmisi',
                'field'    => 'term_id',
                'terms'    => $transmisi_terms,
                'operator' => 'IN',
            );
        }
    }

    if (!empty($atts['bahan_bakar'])) {
        $bahan_bakar_terms = get_terms(array(
            'taxonomy' => 'bahan_bakar',
            'slug' => explode(',', $atts['bahan_bakar']),
            'fields' => 'ids',
        ));

        if (!empty($bahan_bakar_terms) && !is_wp_error($bahan_bakar_terms)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'bahan_bakar',
                'field'    => 'term_id',
                'terms'    => $bahan_bakar_terms,
                'operator' => 'IN',
            );
        }
    }

    if (!empty($atts['tipe'])) {
        $tipe_terms = get_terms(array(
            'taxonomy' => 'tipe_kendaraan',
            'slug' => explode(',', $atts['tipe']),
            'fields' => 'ids',
        ));

        if (!empty($tipe_terms) && !is_wp_error($tipe_terms)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'tipe_kendaraan',
                'field'    => 'term_id',
                'terms'    => $tipe_terms,
                'operator' => 'IN',
            );
        }
    }

    if (!empty($atts['tahun'])) {
        $tahun_terms = get_terms(array(
            'taxonomy' => 'tahun_kendaraan',
            'slug' => explode(',', $atts['tahun']),
            'fields' => 'ids',
        ));

        if (!empty($tahun_terms) && !is_wp_error($tahun_terms)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'tahun_kendaraan',
                'field'    => 'term_id',
                'terms'    => $tahun_terms,
                'operator' => 'IN',
            );
        }
    }

    // Jika ada lebih dari satu tax_query, tambahkan relation AND
    if (isset($args['tax_query']) && count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="rental-mobil-grid">';
        while ($query->have_posts()) {
            $query->the_post();
            include RENTAL_MOBIL_PLUGIN_DIR . 'templates/card-kendaraan.php';
        }
        echo '</div>';
    } else {
        echo '<p>' . __('Tidak ada kendaraan yang ditemukan.', 'rental-mobil-wp') . '</p>';
    }

    wp_reset_postdata();

    // Tampilkan modal booking
    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/booking-modal.php';

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
    } else {
        echo '<p>' . __('Kendaraan tidak ditemukan.', 'rental-mobil-wp') . '</p>';
    }

    // Ambil output buffering dan kembalikan
    return ob_get_clean();
}

/**
 * AJAX handler untuk filter kendaraan
 */
add_action('wp_ajax_rental_mobil_filter', 'rental_mobil_filter_ajax');
add_action('wp_ajax_nopriv_rental_mobil_filter', 'rental_mobil_filter_ajax');
function rental_mobil_filter_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    $merk = isset($_POST['merk']) ? sanitize_text_field($_POST['merk']) : '';
    $transmisi = isset($_POST['transmisi']) ? sanitize_text_field($_POST['transmisi']) : '';
    $bahan_bakar = isset($_POST['bahan_bakar']) ? sanitize_text_field($_POST['bahan_bakar']) : '';
    $tipe = isset($_POST['tipe']) ? sanitize_text_field($_POST['tipe']) : '';
    $tahun = isset($_POST['tahun']) ? sanitize_text_field($_POST['tahun']) : '';
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';

    // Query kendaraan
    $args = array(
        'post_type'      => 'kendaraan',
        'posts_per_page' => -1,
        'orderby'        => $orderby,
        'order'          => $order,
    );

    // Tambahkan filter berdasarkan parameter
    if (!empty($merk)) {
        $merk_terms = get_terms(array(
            'taxonomy' => 'merk_kendaraan',
            'slug' => $merk,
            'fields' => 'ids',
        ));

        if (!empty($merk_terms) && !is_wp_error($merk_terms)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'merk_kendaraan',
                'field'    => 'term_id',
                'terms'    => $merk_terms,
                'operator' => 'IN',
            );
        }
    }

    if (!empty($transmisi)) {
        $transmisi_terms = get_terms(array(
            'taxonomy' => 'transmisi',
            'slug' => $transmisi,
            'fields' => 'ids',
        ));

        if (!empty($transmisi_terms) && !is_wp_error($transmisi_terms)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'transmisi',
                'field'    => 'term_id',
                'terms'    => $transmisi_terms,
                'operator' => 'IN',
            );
        }
    }

    if (!empty($bahan_bakar)) {
        $bahan_bakar_terms = get_terms(array(
            'taxonomy' => 'bahan_bakar',
            'slug' => $bahan_bakar,
            'fields' => 'ids',
        ));

        if (!empty($bahan_bakar_terms) && !is_wp_error($bahan_bakar_terms)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'bahan_bakar',
                'field'    => 'term_id',
                'terms'    => $bahan_bakar_terms,
                'operator' => 'IN',
            );
        }
    }

    if (!empty($tipe)) {
        $tipe_terms = get_terms(array(
            'taxonomy' => 'tipe_kendaraan',
            'slug' => $tipe,
            'fields' => 'ids',
        ));

        if (!empty($tipe_terms) && !is_wp_error($tipe_terms)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'tipe_kendaraan',
                'field'    => 'term_id',
                'terms'    => $tipe_terms,
                'operator' => 'IN',
            );
        }
    }

    if (!empty($tahun)) {
        $tahun_terms = get_terms(array(
            'taxonomy' => 'tahun_kendaraan',
            'slug' => $tahun,
            'fields' => 'ids',
        ));

        if (!empty($tahun_terms) && !is_wp_error($tahun_terms)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'tahun_kendaraan',
                'field'    => 'term_id',
                'terms'    => $tahun_terms,
                'operator' => 'IN',
            );
        }
    }

    // Jika ada lebih dari satu tax_query, tambahkan relation AND
    if (isset($args['tax_query']) && count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        echo '<div class="rental-mobil-grid">';
        while ($query->have_posts()) {
            $query->the_post();
            include RENTAL_MOBIL_PLUGIN_DIR . 'templates/card-kendaraan.php';
        }
        echo '</div>';
    } else {
        echo '<p>' . __('Tidak ada kendaraan yang ditemukan.', 'rental-mobil-wp') . '</p>';
    }

    wp_reset_postdata();

    $html = ob_get_clean();

    wp_send_json_success(array(
        'html' => $html,
    ));
}

/**
 * AJAX handler untuk mendapatkan nomor WhatsApp dan template pesan
 */
add_action('wp_ajax_rental_mobil_get_whatsapp', 'rental_mobil_get_whatsapp_ajax');
add_action('wp_ajax_nopriv_rental_mobil_get_whatsapp', 'rental_mobil_get_whatsapp_ajax');
function rental_mobil_get_whatsapp_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Dapatkan nomor WhatsApp dari pengaturan
    $whatsapp_number = rental_mobil_get_whatsapp_number();

    if (empty($whatsapp_number)) {
        wp_send_json_error('Nomor WhatsApp belum diatur. Silakan hubungi administrator.');
    }

    // Dapatkan template pesan
    $message_template = rental_mobil_get_whatsapp_message();

    // Dapatkan data dari form
    $kendaraan_id = isset($_POST['kendaraan_id']) ? intval($_POST['kendaraan_id']) : 0;
    $nama = isset($_POST['nama']) ? sanitize_text_field($_POST['nama']) : '';
    $domisili = isset($_POST['domisili']) ? sanitize_text_field($_POST['domisili']) : '';
    $tanggal_sewa = isset($_POST['tanggal_sewa']) ? sanitize_text_field($_POST['tanggal_sewa']) : '';
    $jam_sewa = isset($_POST['jam_sewa']) ? sanitize_text_field($_POST['jam_sewa']) : '';
    $durasi_sewa = isset($_POST['durasi_sewa']) ? sanitize_text_field($_POST['durasi_sewa']) : '';
    $satuan_durasi = isset($_POST['satuan_durasi']) ? sanitize_text_field($_POST['satuan_durasi']) : '';

    // Dapatkan nama kendaraan
    $kendaraan_title = get_the_title($kendaraan_id);

    // Ganti placeholder dengan data sebenarnya
    $message = str_replace(
        array(
            '{nama_kendaraan}',
            '{nama}',
            '{domisili}',
            '{tanggal_sewa}',
            '{jam_sewa}',
            '{durasi_sewa}',
            '{satuan_durasi}'
        ),
        array(
            $kendaraan_title,
            $nama,
            $domisili,
            $tanggal_sewa,
            $jam_sewa,
            $durasi_sewa,
            $satuan_durasi
        ),
        $message_template
    );

    wp_send_json_success(array(
        'whatsapp_number' => $whatsapp_number,
        'message' => $message
    ));
}
