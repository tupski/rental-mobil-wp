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
    $harga_mingguan = get_post_meta($post->ID, '_rental_mobil_harga_mingguan', true);
    $harga_bulanan = get_post_meta($post->ID, '_rental_mobil_harga_bulanan', true);
    $galeri = get_post_meta($post->ID, '_rental_mobil_galeri', true);
    $youtube_url = get_post_meta($post->ID, '_rental_mobil_youtube_url', true);
    $is_featured = get_post_meta($post->ID, '_rental_mobil_is_featured', true);
    $is_popular = get_post_meta($post->ID, '_rental_mobil_is_popular', true);
    ?>
    <div class="rental-mobil-meta-box">
        <h4><?php _e('Harga Sewa', 'rental-mobil-wp'); ?></h4>
        <p>
            <label for="rental_mobil_harga_sewa"><?php _e('Harga Sewa (per hari)', 'rental-mobil-wp'); ?></label>
            <input type="number" id="rental_mobil_harga_sewa" name="rental_mobil_harga_sewa" value="<?php echo esc_attr($harga_sewa); ?>" class="widefat">
            <span class="description"><?php _e('Masukkan harga sewa kendaraan per hari dalam Rupiah (tanpa titik atau koma)', 'rental-mobil-wp'); ?></span>
        </p>

        <p>
            <label for="rental_mobil_harga_mingguan"><?php _e('Harga Sewa (per minggu)', 'rental-mobil-wp'); ?></label>
            <input type="number" id="rental_mobil_harga_mingguan" name="rental_mobil_harga_mingguan" value="<?php echo esc_attr($harga_mingguan); ?>" class="widefat">
            <span class="description"><?php _e('Masukkan harga sewa kendaraan per minggu dalam Rupiah (tanpa titik atau koma)', 'rental-mobil-wp'); ?></span>
        </p>

        <p>
            <label for="rental_mobil_harga_bulanan"><?php _e('Harga Sewa (per bulan)', 'rental-mobil-wp'); ?></label>
            <input type="number" id="rental_mobil_harga_bulanan" name="rental_mobil_harga_bulanan" value="<?php echo esc_attr($harga_bulanan); ?>" class="widefat">
            <span class="description"><?php _e('Masukkan harga sewa kendaraan per bulan dalam Rupiah (tanpa titik atau koma)', 'rental-mobil-wp'); ?></span>
        </p>

        <p>
            <label for="rental_mobil_galeri"><?php _e('Galeri Kendaraan', 'rental-mobil-wp'); ?></label>
            <div class="rental-mobil-media-gallery">
                <input type="hidden" id="rental_mobil_galeri" name="rental_mobil_galeri" value="<?php echo esc_attr($galeri); ?>" />
                <div id="rental_mobil_galeri_container" class="rental-mobil-media-gallery-container">
                    <?php
                    if (!empty($galeri)) {
                        $gallery_ids = explode(',', $galeri);
                        foreach ($gallery_ids as $attachment_id) {
                            if (!empty($attachment_id)) {
                                $image = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                                if ($image) {
                                    echo '<div class="rental-mobil-media-gallery-image" data-id="' . esc_attr($attachment_id) . '">';
                                    echo '<img src="' . esc_url($image[0]) . '" alt="" />';
                                    echo '<a href="#" class="rental-mobil-media-gallery-remove">×</a>';
                                    echo '</div>';
                                }
                            }
                        }
                    }
                    ?>
                </div>
                <button type="button" class="button" id="rental_mobil_galeri_button"><?php _e('Tambah Gambar', 'rental-mobil-wp'); ?></button>
            </div>
            <span class="description"><?php _e('Tambahkan gambar untuk galeri kendaraan. Klik "Tambah Gambar" untuk memilih dari media library.', 'rental-mobil-wp'); ?></span>
        </p>

        <style>
            .rental-mobil-media-gallery-container {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-bottom: 10px;
            }
            .rental-mobil-media-gallery-image {
                position: relative;
                width: 80px;
                height: 80px;
                border: 1px solid #ddd;
                border-radius: 4px;
                overflow: hidden;
            }
            .rental-mobil-media-gallery-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .rental-mobil-media-gallery-remove {
                position: absolute;
                top: 0;
                right: 0;
                background: rgba(0,0,0,0.5);
                color: #fff;
                width: 20px;
                height: 20px;
                text-align: center;
                line-height: 18px;
                text-decoration: none;
                font-weight: bold;
            }
            .rental-mobil-media-gallery-remove:hover {
                background: rgba(0,0,0,0.8);
                color: #fff;
            }
        </style>

        <script>
            jQuery(document).ready(function($) {
                // Media Uploader
                var mediaUploader;

                $('#rental_mobil_galeri_button').on('click', function(e) {
                    e.preventDefault();

                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }

                    mediaUploader = wp.media({
                        title: '<?php _e('Pilih Gambar untuk Galeri', 'rental-mobil-wp'); ?>',
                        button: {
                            text: '<?php _e('Tambahkan ke Galeri', 'rental-mobil-wp'); ?>'
                        },
                        multiple: true
                    });

                    mediaUploader.on('select', function() {
                        var attachments = mediaUploader.state().get('selection').toJSON();
                        var galleryIds = $('#rental_mobil_galeri').val() ? $('#rental_mobil_galeri').val().split(',') : [];

                        $.each(attachments, function(i, attachment) {
                            if (galleryIds.indexOf(attachment.id.toString()) === -1) {
                                galleryIds.push(attachment.id);

                                $('#rental_mobil_galeri_container').append(
                                    '<div class="rental-mobil-media-gallery-image" data-id="' + attachment.id + '">' +
                                    '<img src="' + (attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" alt="" />' +
                                    '<a href="#" class="rental-mobil-media-gallery-remove">×</a>' +
                                    '</div>'
                                );
                            }
                        });

                        $('#rental_mobil_galeri').val(galleryIds.join(','));
                    });

                    mediaUploader.open();
                });

                // Remove image
                $(document).on('click', '.rental-mobil-media-gallery-remove', function(e) {
                    e.preventDefault();

                    var container = $(this).parent();
                    var imageId = container.data('id');
                    var galleryIds = $('#rental_mobil_galeri').val().split(',');

                    galleryIds = galleryIds.filter(function(id) {
                        return id != imageId;
                    });

                    $('#rental_mobil_galeri').val(galleryIds.join(','));
                    container.remove();
                });
            });
        </script>

        <p>
            <label for="rental_mobil_youtube_url"><?php _e('URL Video YouTube', 'rental-mobil-wp'); ?></label>
            <input type="url" id="rental_mobil_youtube_url" name="rental_mobil_youtube_url" value="<?php echo esc_url($youtube_url); ?>" class="widefat">
            <span class="description"><?php _e('Masukkan URL video YouTube. Contoh: https://www.youtube.com/watch?v=XXXXXXXXXXX', 'rental-mobil-wp'); ?></span>
        </p>

        <h4><?php _e('Status Kendaraan', 'rental-mobil-wp'); ?></h4>
        <p>
            <label for="rental_mobil_is_featured">
                <input type="checkbox" id="rental_mobil_is_featured" name="rental_mobil_is_featured" value="1" <?php checked($is_featured, '1'); ?>>
                <?php _e('Kendaraan Unggulan', 'rental-mobil-wp'); ?>
            </label>
            <span class="description"><?php _e('Centang jika kendaraan ini adalah kendaraan unggulan yang akan ditampilkan di slider.', 'rental-mobil-wp'); ?></span>
        </p>
        <p>
            <label for="rental_mobil_is_popular">
                <input type="checkbox" id="rental_mobil_is_popular" name="rental_mobil_is_popular" value="1" <?php checked($is_popular, '1'); ?>>
                <?php _e('Paling Banyak Disewa', 'rental-mobil-wp'); ?>
            </label>
            <span class="description"><?php _e('Centang jika kendaraan ini termasuk yang paling banyak disewa.', 'rental-mobil-wp'); ?></span>
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

    // Simpan harga mingguan
    if (isset($_POST['rental_mobil_harga_mingguan'])) {
        update_post_meta(
            $post_id,
            '_rental_mobil_harga_mingguan',
            sanitize_text_field($_POST['rental_mobil_harga_mingguan'])
        );
    }

    // Simpan harga bulanan
    if (isset($_POST['rental_mobil_harga_bulanan'])) {
        update_post_meta(
            $post_id,
            '_rental_mobil_harga_bulanan',
            sanitize_text_field($_POST['rental_mobil_harga_bulanan'])
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

    // Simpan status kendaraan unggulan
    $is_featured = isset($_POST['rental_mobil_is_featured']) ? '1' : '0';
    update_post_meta(
        $post_id,
        '_rental_mobil_is_featured',
        $is_featured
    );

    // Simpan status kendaraan paling banyak disewa
    $is_popular = isset($_POST['rental_mobil_is_popular']) ? '1' : '0';
    update_post_meta(
        $post_id,
        '_rental_mobil_is_popular',
        $is_popular
    );
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
 * Dapatkan harga sewa mingguan kendaraan
 */
function rental_mobil_get_harga_mingguan($post_id) {
    $harga = get_post_meta($post_id, '_rental_mobil_harga_mingguan', true);
    return $harga ? $harga : 0;
}

/**
 * Dapatkan harga sewa bulanan kendaraan
 */
function rental_mobil_get_harga_bulanan($post_id) {
    $harga = get_post_meta($post_id, '_rental_mobil_harga_bulanan', true);
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

    $image_ids = explode(',', $galeri);
    $image_ids = array_map('trim', $image_ids);
    $image_ids = array_filter($image_ids);

    return $image_ids;
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

/**
 * Cek apakah kendaraan adalah kendaraan unggulan
 */
function rental_mobil_is_featured($post_id) {
    return get_post_meta($post_id, '_rental_mobil_is_featured', true) === '1';
}

/**
 * Cek apakah kendaraan adalah kendaraan paling banyak disewa
 */
function rental_mobil_is_popular($post_id) {
    return get_post_meta($post_id, '_rental_mobil_is_popular', true) === '1';
}

/**
 * Dapatkan semua kendaraan unggulan
 */
function rental_mobil_get_featured_vehicles($limit = -1, $orderby = 'date', $order = 'DESC') {
    $args = array(
        'post_type' => 'kendaraan',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_rental_mobil_is_featured',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => $orderby,
        'order' => $order
    );

    // Jika orderby adalah harga atau harga harian, gunakan meta_value_num
    if ($orderby === 'meta_value_num') {
        $args['meta_key'] = '_rental_mobil_harga_sewa';
    } elseif ($orderby === 'harga_harian') {
        $args['meta_key'] = '_rental_mobil_harga_sewa'; // Menggunakan harga sewa per hari
        $args['orderby'] = 'meta_value_num';
    }

    return new WP_Query($args);
}

/**
 * Dapatkan semua kendaraan paling banyak disewa
 */
function rental_mobil_get_popular_vehicles($limit = -1, $orderby = 'date', $order = 'DESC') {
    $args = array(
        'post_type' => 'kendaraan',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_rental_mobil_is_popular',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => $orderby,
        'order' => $order
    );

    // Jika orderby adalah harga atau harga harian, gunakan meta_value_num
    if ($orderby === 'meta_value_num') {
        $args['meta_key'] = '_rental_mobil_harga_sewa';
    } elseif ($orderby === 'harga_harian') {
        $args['meta_key'] = '_rental_mobil_harga_sewa'; // Menggunakan harga sewa per hari
        $args['orderby'] = 'meta_value_num';
    }

    return new WP_Query($args);
}
