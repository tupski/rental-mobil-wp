<?php
/**
 * AJAX Handlers
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * AJAX handler untuk filter kendaraan
 */
add_action('wp_ajax_rental_mobil_filter', 'rental_mobil_filter_ajax');
add_action('wp_ajax_nopriv_rental_mobil_filter', 'rental_mobil_filter_ajax');
function rental_mobil_filter_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_nonce')) {
        wp_send_json_error(array('message' => __('Verifikasi keamanan gagal.', 'rental-mobil-wp')));
    }

    // Dapatkan parameter filter
    $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
    $merk = isset($_POST['merk']) ? sanitize_text_field($_POST['merk']) : '';
    $transmisi = isset($_POST['transmisi']) ? sanitize_text_field($_POST['transmisi']) : '';
    $bahan_bakar = isset($_POST['bahan_bakar']) ? sanitize_text_field($_POST['bahan_bakar']) : '';
    $tipe = isset($_POST['tipe']) ? sanitize_text_field($_POST['tipe']) : '';
    $tahun = isset($_POST['tahun']) ? sanitize_text_field($_POST['tahun']) : '';
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';
    $jumlah = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 9; // Default 9 kendaraan per halaman

    // Cek parameter halaman dari POST
    $paged = 1; // Default halaman 1
    if (isset($_POST['halaman'])) {
        $paged = intval($_POST['halaman']);
    } elseif (isset($_POST['paged'])) {
        $paged = intval($_POST['paged']);
    }

    // Buat array untuk menyimpan filter aktif
    $active_filters = array();

    if (!empty($keyword)) {
        $active_filters['keyword'] = array(
            'label' => __('Kata Kunci', 'rental-mobil-wp'),
            'value' => $keyword
        );
    }

    if (!empty($merk)) {
        $term = get_term_by('slug', $merk, 'merk_kendaraan');
        if ($term) {
            $active_filters['merk'] = array(
                'label' => __('Merk', 'rental-mobil-wp'),
                'value' => $term->name
            );
        }
    }

    if (!empty($transmisi)) {
        $term = get_term_by('slug', $transmisi, 'transmisi');
        if ($term) {
            $active_filters['transmisi'] = array(
                'label' => __('Transmisi', 'rental-mobil-wp'),
                'value' => $term->name
            );
        }
    }

    if (!empty($bahan_bakar)) {
        $term = get_term_by('slug', $bahan_bakar, 'bahan_bakar');
        if ($term) {
            $active_filters['bahan_bakar'] = array(
                'label' => __('Bahan Bakar', 'rental-mobil-wp'),
                'value' => $term->name
            );
        }
    }

    if (!empty($tipe)) {
        $term = get_term_by('slug', $tipe, 'tipe_kendaraan');
        if ($term) {
            $active_filters['tipe'] = array(
                'label' => __('Tipe', 'rental-mobil-wp'),
                'value' => $term->name
            );
        }
    }

    if (!empty($tahun)) {
        $term = get_term_by('slug', $tahun, 'tahun_kendaraan');
        if ($term) {
            $active_filters['tahun'] = array(
                'label' => __('Tahun', 'rental-mobil-wp'),
                'value' => $term->name
            );
        }
    }

    // Query kendaraan
    $args = array(
        'post_type'      => 'kendaraan',
        'posts_per_page' => $jumlah,
        'orderby'        => $orderby,
        'order'          => $order,
        'paged'          => $paged,
    );

    // Jika orderby adalah harga, tambahkan meta_key
    if ($orderby === 'meta_value_num') {
        $args['meta_key'] = '_rental_mobil_harga_sewa';
    } elseif ($orderby === 'price_high') {
        $args['meta_key'] = '_rental_mobil_harga_sewa';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } elseif ($orderby === 'price_low') {
        $args['meta_key'] = '_rental_mobil_harga_sewa';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'ASC';
    }

    // Jika ada keyword, tambahkan pencarian
    if (!empty($keyword)) {
        $args['s'] = $keyword;
    }

    // Tambahkan filter berdasarkan parameter
    $tax_query = array();

    if (!empty($merk)) {
        $tax_query[] = array(
            'taxonomy' => 'merk_kendaraan',
            'field'    => 'slug',
            'terms'    => $merk,
        );
    }

    if (!empty($transmisi)) {
        $tax_query[] = array(
            'taxonomy' => 'transmisi',
            'field'    => 'slug',
            'terms'    => $transmisi,
        );
    }

    if (!empty($bahan_bakar)) {
        $tax_query[] = array(
            'taxonomy' => 'bahan_bakar',
            'field'    => 'slug',
            'terms'    => $bahan_bakar,
        );
    }

    if (!empty($tipe)) {
        $tax_query[] = array(
            'taxonomy' => 'tipe_kendaraan',
            'field'    => 'slug',
            'terms'    => $tipe,
        );
    }

    if (!empty($tahun)) {
        $tax_query[] = array(
            'taxonomy' => 'tahun_kendaraan',
            'field'    => 'slug',
            'terms'    => $tahun,
        );
    }

    // Jika ada tax_query, tambahkan ke args
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
        if (count($tax_query) > 1) {
            $args['tax_query']['relation'] = 'AND';
        }
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

        // Tambahkan paginasi
        echo '<div class="rental-mobil-pagination">';
        echo '<div class="rental-mobil-pagination-info">';
        echo sprintf(
            __('Menampilkan %1$s dari %2$s kendaraan', 'rental-mobil-wp'),
            min($query->post_count, $query->found_posts),
            $query->found_posts
        );
        echo '</div>';

        echo '<div class="rental-mobil-pagination-links" data-max-pages="' . $query->max_num_pages . '" data-current-page="' . $paged . '">';

        // Tombol Previous
        if ($paged > 1) {
            echo '<a href="#" class="rental-mobil-pagination-prev" data-page="' . ($paged - 1) . '">' . __('« Sebelumnya', 'rental-mobil-wp') . '</a>';
        } else {
            echo '<span class="rental-mobil-pagination-prev disabled">' . __('« Sebelumnya', 'rental-mobil-wp') . '</span>';
        }

        // Nomor halaman
        $start_page = max(1, $paged - 2);
        $end_page = min($query->max_num_pages, $paged + 2);

        if ($start_page > 1) {
            echo '<a href="#" class="rental-mobil-pagination-number" data-page="1">1</a>';
            if ($start_page > 2) {
                echo '<span class="rental-mobil-pagination-dots">...</span>';
            }
        }

        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $paged) {
                echo '<span class="rental-mobil-pagination-number current">' . $i . '</span>';
            } else {
                echo '<a href="#" class="rental-mobil-pagination-number" data-page="' . $i . '">' . $i . '</a>';
            }
        }

        if ($end_page < $query->max_num_pages) {
            if ($end_page < $query->max_num_pages - 1) {
                echo '<span class="rental-mobil-pagination-dots">...</span>';
            }
            echo '<a href="#" class="rental-mobil-pagination-number" data-page="' . $query->max_num_pages . '">' . $query->max_num_pages . '</a>';
        }

        // Tombol Next
        if ($paged < $query->max_num_pages) {
            echo '<a href="#" class="rental-mobil-pagination-next" data-page="' . ($paged + 1) . '">' . __('Selanjutnya »', 'rental-mobil-wp') . '</a>';
        } else {
            echo '<span class="rental-mobil-pagination-next disabled">' . __('Selanjutnya »', 'rental-mobil-wp') . '</span>';
        }

        echo '</div>'; // .rental-mobil-pagination-links
        echo '</div>'; // .rental-mobil-pagination
    } else {
        echo '<p>' . __('Tidak ada kendaraan yang ditemukan.', 'rental-mobil-wp') . '</p>';
    }

    wp_reset_postdata();

    $html = ob_get_clean();

    wp_send_json_success(array(
        'html' => $html,
        'count' => $query->found_posts,
        'active_filters' => $active_filters,
        'max_pages' => $query->max_num_pages,
        'current_page' => $paged
    ));
}

/**
 * AJAX handler untuk pencarian kendaraan
 */
add_action('wp_ajax_rental_mobil_search', 'rental_mobil_search_ajax');
add_action('wp_ajax_nopriv_rental_mobil_search', 'rental_mobil_search_ajax');
function rental_mobil_search_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_nonce')) {
        wp_send_json_error(array('message' => __('Verifikasi keamanan gagal.', 'rental-mobil-wp')));
    }

    // Dapatkan keyword
    $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';

    if (empty($keyword)) {
        wp_send_json_error(array('message' => __('Silakan masukkan kata kunci pencarian.', 'rental-mobil-wp')));
    }

    // Query kendaraan
    $args = array(
        'post_type'      => 'kendaraan',
        'posts_per_page' => 10,
        's'              => $keyword,
    );

    $query = new WP_Query($args);

    $results = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Dapatkan data kendaraan
            $id = get_the_ID();
            $title = get_the_title();
            $permalink = get_permalink();
            $thumbnail = get_the_post_thumbnail_url($id, 'thumbnail');
            $harga = get_post_meta($id, '_rental_mobil_harga_sewa', true);
            $harga_formatted = 'Rp ' . number_format($harga, 0, ',', '.');

            // Dapatkan terms
            $merk_terms = get_the_terms($id, 'merk_kendaraan');
            $merk = !empty($merk_terms) && !is_wp_error($merk_terms) ? $merk_terms[0]->name : '';

            $transmisi_terms = get_the_terms($id, 'transmisi');
            $transmisi = !empty($transmisi_terms) && !is_wp_error($transmisi_terms) ? $transmisi_terms[0]->name : '';

            $results[] = array(
                'id' => $id,
                'title' => $title,
                'permalink' => $permalink,
                'thumbnail' => $thumbnail,
                'harga' => $harga_formatted,
                'merk' => $merk,
                'transmisi' => $transmisi,
            );
        }
    }

    wp_reset_postdata();

    wp_send_json_success(array(
        'results' => $results,
        'count' => count($results),
    ));
}

/**
 * AJAX handler untuk autocomplete
 */
add_action('wp_ajax_rental_mobil_autocomplete', 'rental_mobil_autocomplete_ajax');
add_action('wp_ajax_nopriv_rental_mobil_autocomplete', 'rental_mobil_autocomplete_ajax');
function rental_mobil_autocomplete_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_nonce')) {
        wp_send_json_error(array('message' => __('Verifikasi keamanan gagal.', 'rental-mobil-wp')));
    }

    // Dapatkan keyword
    $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';

    if (empty($keyword)) {
        wp_send_json_success(array('results' => array()));
    }

    // Query kendaraan
    $args = array(
        'post_type'      => 'kendaraan',
        'posts_per_page' => 5,
        's'              => $keyword,
    );

    $query = new WP_Query($args);

    $results = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Dapatkan data kendaraan
            $id = get_the_ID();
            $title = get_the_title();
            $permalink = get_permalink();

            $results[] = array(
                'id' => $id,
                'title' => $title,
                'permalink' => $permalink,
            );
        }
    }

    wp_reset_postdata();

    wp_send_json_success(array(
        'results' => $results,
    ));
}

/**
 * AJAX handler untuk mendapatkan galeri kendaraan
 */
add_action('wp_ajax_rental_mobil_get_gallery', 'rental_mobil_get_gallery_ajax');
add_action('wp_ajax_nopriv_rental_mobil_get_gallery', 'rental_mobil_get_gallery_ajax');
function rental_mobil_get_gallery_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Dapatkan ID kendaraan
    $kendaraan_id = isset($_POST['kendaraan_id']) ? intval($_POST['kendaraan_id']) : 0;

    if (empty($kendaraan_id)) {
        wp_send_json_error('ID kendaraan tidak valid');
    }

    // Dapatkan galeri kendaraan
    $gallery_ids = rental_mobil_get_galeri($kendaraan_id);
    $gallery = array();

    if (!empty($gallery_ids)) {
        foreach ($gallery_ids as $attachment_id) {
            $full_image = wp_get_attachment_image_src($attachment_id, 'large');
            $thumbnail = wp_get_attachment_image_src($attachment_id, 'thumbnail');

            if ($full_image && $thumbnail) {
                $gallery[] = array(
                    'id' => $attachment_id,
                    'url' => $full_image[0],
                    'thumbnail' => $thumbnail[0]
                );
            }
        }
    }

    wp_send_json_success(array(
        'gallery' => $gallery
    ));
}

/**
 * AJAX handler untuk mendapatkan data kendaraan
 */
add_action('wp_ajax_rental_mobil_get_kendaraan_data', 'rental_mobil_get_kendaraan_data_ajax');
add_action('wp_ajax_nopriv_rental_mobil_get_kendaraan_data', 'rental_mobil_get_kendaraan_data_ajax');
function rental_mobil_get_kendaraan_data_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Dapatkan ID kendaraan
    $kendaraan_id = isset($_POST['kendaraan_id']) ? intval($_POST['kendaraan_id']) : 0;

    if (empty($kendaraan_id)) {
        wp_send_json_error('ID kendaraan tidak valid');
    }

    // Dapatkan data kendaraan
    $kendaraan = get_post($kendaraan_id);

    if (!$kendaraan || $kendaraan->post_type !== 'kendaraan') {
        wp_send_json_error('Kendaraan tidak ditemukan');
    }

    // Dapatkan meta data
    $harga_harian = get_post_meta($kendaraan_id, '_rental_mobil_harga_sewa', true);
    $harga_mingguan = get_post_meta($kendaraan_id, '_rental_mobil_harga_sewa_mingguan', true);
    $harga_bulanan = get_post_meta($kendaraan_id, '_rental_mobil_harga_sewa_bulanan', true);
    $is_featured = get_post_meta($kendaraan_id, '_rental_mobil_featured', true);
    $is_popular = get_post_meta($kendaraan_id, '_rental_mobil_popular', true);

    // Format harga
    $harga_harian_formatted = 'Rp ' . number_format($harga_harian, 0, ',', '.');
    $harga_mingguan_formatted = 'Rp ' . number_format($harga_mingguan, 0, ',', '.');
    $harga_bulanan_formatted = 'Rp ' . number_format($harga_bulanan, 0, ',', '.');

    // Dapatkan terms
    $merk_terms = get_the_terms($kendaraan_id, 'merk_kendaraan');
    $merk = !empty($merk_terms) && !is_wp_error($merk_terms) ? $merk_terms[0]->name : '';

    $transmisi_terms = get_the_terms($kendaraan_id, 'transmisi');
    $transmisi = !empty($transmisi_terms) && !is_wp_error($transmisi_terms) ? $transmisi_terms[0]->name : '';

    $bahan_bakar_terms = get_the_terms($kendaraan_id, 'bahan_bakar');
    $bahan_bakar = !empty($bahan_bakar_terms) && !is_wp_error($bahan_bakar_terms) ? $bahan_bakar_terms[0]->name : '';

    $tahun_terms = get_the_terms($kendaraan_id, 'tahun_kendaraan');
    $tahun = !empty($tahun_terms) && !is_wp_error($tahun_terms) ? $tahun_terms[0]->name : '';

    // Dapatkan featured image
    $featured_image = get_the_post_thumbnail_url($kendaraan_id, 'large');

    // Siapkan data untuk response
    $data = array(
        'title' => $kendaraan->post_title,
        'harga_harian' => $harga_harian_formatted,
        'harga_mingguan' => $harga_mingguan_formatted,
        'harga_bulanan' => $harga_bulanan_formatted,
        'merk' => $merk,
        'transmisi' => $transmisi,
        'bahan_bakar' => $bahan_bakar,
        'tahun' => $tahun,
        'is_featured' => !empty($is_featured),
        'is_popular' => !empty($is_popular),
        'featured_image' => $featured_image
    );

    wp_send_json_success($data);
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

    // Dapatkan nama kendaraan
    $kendaraan_title = get_the_title($kendaraan_id);

    // Dapatkan form fields dari pengaturan
    $form_fields = rental_mobil_get_form_fields();

    // Buat array placeholder dan nilai
    $placeholders = array('{nama_kendaraan}');
    $values = array($kendaraan_title);

    // Tambahkan semua field dari form ke array placeholder dan nilai
    foreach ($form_fields as $field) {
        $field_id = $field['id'];
        $placeholder = '{' . $field_id . '}';
        $value = isset($_POST[$field_id]) ? sanitize_text_field($_POST[$field_id]) : '';

        $placeholders[] = $placeholder;
        $values[] = $value;
    }

    // Tambahkan placeholder dinamis untuk semua field form
    $dynamic_placeholders = array();
    $dynamic_values = array();

    foreach ($_POST as $key => $value) {
        // Lewati kunci yang bukan field form (seperti action, nonce, dll)
        if (in_array($key, array('action', 'nonce', 'kendaraan_id'))) {
            continue;
        }

        // Buat placeholder dinamis jika belum ada
        $dynamic_placeholder = '{' . $key . '}';
        if (!in_array($dynamic_placeholder, $placeholders)) {
            $dynamic_placeholders[] = $dynamic_placeholder;
            $dynamic_values[] = sanitize_text_field($value);
        }
    }

    // Gabungkan placeholder dinamis dengan placeholder yang sudah ada
    $placeholders = array_merge($placeholders, $dynamic_placeholders);
    $values = array_merge($values, $dynamic_values);

    // Ganti placeholder dengan data sebenarnya
    $message = str_replace($placeholders, $values, $message_template);

    wp_send_json_success(array(
        'whatsapp_number' => $whatsapp_number,
        'message' => $message
    ));
}

/**
 * AJAX handler untuk menyimpan form fields
 */
add_action('wp_ajax_rental_mobil_save_form_fields', 'rental_mobil_save_form_fields_ajax');
function rental_mobil_save_form_fields_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_form_builder_nonce')) {
        wp_send_json_error(array('message' => __('Verifikasi keamanan gagal.', 'rental-mobil-wp')));
    }

    // Verifikasi user capability
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Anda tidak memiliki izin untuk melakukan tindakan ini.', 'rental-mobil-wp')));
    }

    // Dapatkan form fields dari POST
    $form_fields = isset($_POST['form_fields']) ? $_POST['form_fields'] : '';

    // Decode JSON
    $form_fields = json_decode(stripslashes($form_fields), true);

    // Validasi form fields
    if (!is_array($form_fields)) {
        wp_send_json_error(array('message' => __('Format data tidak valid.', 'rental-mobil-wp')));
    }

    // Sanitize form fields
    $sanitized_fields = array();
    foreach ($form_fields as $field) {
        $sanitized_field = array(
            'id' => sanitize_text_field($field['id']),
            'label' => sanitize_text_field($field['label']),
            'type' => sanitize_text_field($field['type']),
            'required' => (bool) $field['required'],
            'order' => absint($field['order'])
        );

        if (isset($field['placeholder'])) {
            $sanitized_field['placeholder'] = sanitize_text_field($field['placeholder']);
        }

        if (isset($field['options']) && is_array($field['options'])) {
            $sanitized_options = array();
            foreach ($field['options'] as $option_key => $option_value) {
                $sanitized_options[sanitize_text_field($option_key)] = sanitize_text_field($option_value);
            }
            $sanitized_field['options'] = $sanitized_options;
        }

        $sanitized_fields[] = $sanitized_field;
    }

    // Dapatkan opsi saat ini
    $options = rental_mobil_get_options();

    // Update form fields
    $options['form_fields'] = $sanitized_fields;

    // Simpan opsi
    update_option('rental_mobil_options', $options);

    wp_send_json_success(array(
        'message' => __('Form fields berhasil disimpan.', 'rental-mobil-wp'),
        'form_fields' => $sanitized_fields
    ));
}
