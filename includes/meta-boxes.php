<?php
/**
 * Register Meta Boxes untuk Custom Fields
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Register Meta Boxes
 */
add_action('add_meta_boxes', 'rental_mobil_register_meta_boxes');
function rental_mobil_register_meta_boxes() {
    add_meta_box(
        'rental_mobil_details',
        __('Detail Kendaraan', 'rental-mobil-wp'),
        'rental_mobil_details_callback',
        'kendaraan',
        'normal',
        'high'
    );
}

/**
 * Meta Box Callback
 */
function rental_mobil_details_callback($post) {
    wp_nonce_field(basename(__FILE__), 'rental_mobil_nonce');
    $harga_sewa = get_post_meta($post->ID, '_rental_mobil_harga_sewa', true);
    $galeri = get_post_meta($post->ID, '_rental_mobil_galeri', true);
    $youtube_url = get_post_meta($post->ID, '_rental_mobil_youtube_url', true);
    ?>
    <div class="rental-mobil-meta-box">
        <p>
            <label for="rental_mobil_harga_sewa"><?php _e('Harga Sewa (per hari)', 'rental-mobil-wp'); ?></label>
            <input type="number" id="rental_mobil_harga_sewa" name="rental_mobil_harga_sewa" value="<?php echo esc_attr($harga_sewa); ?>" class="widefat">
            <span class="description"><?php _e('Masukkan harga sewa kendaraan per hari dalam Rupiah (tanpa titik atau koma)', 'rental-mobil-wp'); ?></span>
        </p>

        <p>
            <label for="rental_mobil_galeri"><?php _e('Galeri Kendaraan', 'rental-mobil-wp'); ?></label>
            <textarea id="rental_mobil_galeri" name="rental_mobil_galeri" class="widefat" rows="3"><?php echo esc_textarea($galeri); ?></textarea>
            <span class="description"><?php _e('Masukkan URL gambar untuk galeri, pisahkan dengan koma (,). Contoh: https://example.com/image1.jpg, https://example.com/image2.jpg', 'rental-mobil-wp'); ?></span>
        </p>

        <p>
            <label for="rental_mobil_youtube_url"><?php _e('URL Video YouTube', 'rental-mobil-wp'); ?></label>
            <input type="url" id="rental_mobil_youtube_url" name="rental_mobil_youtube_url" value="<?php echo esc_url($youtube_url); ?>" class="widefat">
            <span class="description"><?php _e('Masukkan URL video YouTube. Contoh: https://www.youtube.com/watch?v=XXXXXXXXXXX', 'rental-mobil-wp'); ?></span>
        </p>
    </div>
    <?php
}

/**
 * Save Meta Box Data
 */
add_action('save_post', 'rental_mobil_save_meta_box_data');
function rental_mobil_save_meta_box_data($post_id) {
    // Verifikasi nonce
    if (!isset($_POST['rental_mobil_nonce']) || !wp_verify_nonce($_POST['rental_mobil_nonce'], basename(__FILE__))) {
        return;
    }

    // Jika autosave, jangan simpan
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Periksa permissions
    if ('kendaraan' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Simpan harga sewa
    if (isset($_POST['rental_mobil_harga_sewa'])) {
        update_post_meta(
            $post_id,
            '_rental_mobil_harga_sewa',
            sanitize_text_field($_POST['rental_mobil_harga_sewa'])
        );
    }

    // Simpan galeri
    if (isset($_POST['rental_mobil_galeri'])) {
        update_post_meta(
            $post_id,
            '_rental_mobil_galeri',
            sanitize_textarea_field($_POST['rental_mobil_galeri'])
        );
    }

    // Simpan URL YouTube
    if (isset($_POST['rental_mobil_youtube_url'])) {
        update_post_meta(
            $post_id,
            '_rental_mobil_youtube_url',
            esc_url_raw($_POST['rental_mobil_youtube_url'])
        );
    }
}

/**
 * Format harga ke format Rupiah
 */
function rental_mobil_format_rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Dapatkan harga sewa kendaraan
 */
function rental_mobil_get_harga_sewa($post_id) {
    $harga = get_post_meta($post_id, '_rental_mobil_harga_sewa', true);
    return $harga ? $harga : 0;
}

/**
 * Dapatkan galeri kendaraan
 */
function rental_mobil_get_galeri($post_id) {
    $galeri = get_post_meta($post_id, '_rental_mobil_galeri', true);
    if (empty($galeri)) {
        return array();
    }

    $images = explode(',', $galeri);
    $images = array_map('trim', $images);
    $images = array_filter($images);

    return $images;
}

/**
 * Dapatkan URL video YouTube
 */
function rental_mobil_get_youtube_url($post_id) {
    return get_post_meta($post_id, '_rental_mobil_youtube_url', true);
}

/**
 * Dapatkan ID video YouTube dari URL
 */
function rental_mobil_get_youtube_id($url) {
    if (empty($url)) {
        return '';
    }

    $video_id = '';

    // Format: https://www.youtube.com/watch?v=VIDEO_ID
    if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
        $video_id = $matches[1];
    }
    // Format: https://youtu.be/VIDEO_ID
    elseif (preg_match('/youtu\.be\/([^&]+)/', $url, $matches)) {
        $video_id = $matches[1];
    }
    // Format: https://www.youtube.com/embed/VIDEO_ID
    elseif (preg_match('/youtube\.com\/embed\/([^&]+)/', $url, $matches)) {
        $video_id = $matches[1];
    }

    return $video_id;
}
