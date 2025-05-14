<?php
/**
 * Admin Columns untuk Kendaraan
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Tambahkan kolom kustom ke daftar kendaraan di admin
 */
add_filter('manage_kendaraan_posts_columns', 'rental_mobil_add_admin_columns');
function rental_mobil_add_admin_columns($columns) {
    $new_columns = array();

    // Tambahkan kolom setelah kolom judul
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        if ($key === 'title') {
            $new_columns['featured'] = __('Unggulan', 'rental-mobil-wp');
            $new_columns['popular'] = __('Paling Banyak Disewa', 'rental-mobil-wp');
        }
    }

    return $new_columns;
}

/**
 * Tampilkan konten kolom kustom
 */
add_action('manage_kendaraan_posts_custom_column', 'rental_mobil_populate_admin_columns', 10, 2);
function rental_mobil_populate_admin_columns($column, $post_id) {
    switch ($column) {
        case 'featured':
            $is_featured = rental_mobil_is_featured($post_id);
            echo '<a href="#" class="rental-mobil-toggle-featured" data-post-id="' . esc_attr($post_id) . '" data-nonce="' . wp_create_nonce('rental_mobil_toggle_featured_' . $post_id) . '">';
            echo $is_featured ? '<span class="dashicons dashicons-star-filled" style="color: #ffb900;"></span>' : '<span class="dashicons dashicons-star-empty"></span>';
            echo '</a>';
            break;

        case 'popular':
            $is_popular = rental_mobil_is_popular($post_id);
            echo '<a href="#" class="rental-mobil-toggle-popular" data-post-id="' . esc_attr($post_id) . '" data-nonce="' . wp_create_nonce('rental_mobil_toggle_popular_' . $post_id) . '">';
            echo $is_popular ? '<span class="dashicons dashicons-thumbs-up" style="color: #0073aa;"></span>' : '<span class="dashicons dashicons-thumbs-down"></span>';
            echo '</a>';
            break;
    }
}

/**
 * Header donasi dihapus
 */

/**
 * Tambahkan JavaScript untuk toggle status
 */
add_action('admin_footer', 'rental_mobil_admin_footer_js');
function rental_mobil_admin_footer_js() {
    $screen = get_current_screen();

    // Hanya tampilkan di halaman daftar kendaraan
    if ($screen->post_type !== 'kendaraan' || $screen->base !== 'edit') {
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Toggle status kendaraan unggulan
            $('.rental-mobil-toggle-featured').on('click', function(e) {
                e.preventDefault();

                var button = $(this);
                var post_id = button.data('post-id');
                var nonce = button.data('nonce');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'rental_mobil_toggle_featured',
                        post_id: post_id,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update icon
                            if (response.data.is_featured) {
                                button.find('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled').css('color', '#ffb900');
                            } else {
                                button.find('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty').css('color', '');
                            }
                        }
                    }
                });
            });

            // Toggle status kendaraan paling banyak disewa
            $('.rental-mobil-toggle-popular').on('click', function(e) {
                e.preventDefault();

                var button = $(this);
                var post_id = button.data('post-id');
                var nonce = button.data('nonce');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'rental_mobil_toggle_popular',
                        post_id: post_id,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update icon
                            if (response.data.is_popular) {
                                button.find('span').removeClass('dashicons-thumbs-down').addClass('dashicons-thumbs-up').css('color', '#0073aa');
                            } else {
                                button.find('span').removeClass('dashicons-thumbs-up').addClass('dashicons-thumbs-down').css('color', '');
                            }
                        }
                    }
                });
            });
        });
    </script>
    <?php
}

/**
 * AJAX handler untuk toggle status kendaraan unggulan
 */
add_action('wp_ajax_rental_mobil_toggle_featured', 'rental_mobil_toggle_featured_ajax');
function rental_mobil_toggle_featured_ajax() {
    // Verifikasi nonce
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_toggle_featured_' . $post_id)) {
        wp_send_json_error('Invalid nonce');
    }

    // Verifikasi permissions
    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error('Permission denied');
    }

    // Toggle status
    $is_featured = rental_mobil_is_featured($post_id);
    $new_status = $is_featured ? '0' : '1';

    update_post_meta($post_id, '_rental_mobil_is_featured', $new_status);

    wp_send_json_success(array(
        'is_featured' => $new_status === '1'
    ));
}

/**
 * AJAX handler untuk toggle status kendaraan paling banyak disewa
 */
add_action('wp_ajax_rental_mobil_toggle_popular', 'rental_mobil_toggle_popular_ajax');
function rental_mobil_toggle_popular_ajax() {
    // Verifikasi nonce
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_toggle_popular_' . $post_id)) {
        wp_send_json_error('Invalid nonce');
    }

    // Verifikasi permissions
    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error('Permission denied');
    }

    // Toggle status
    $is_popular = rental_mobil_is_popular($post_id);
    $new_status = $is_popular ? '0' : '1';

    update_post_meta($post_id, '_rental_mobil_is_popular', $new_status);

    wp_send_json_success(array(
        'is_popular' => $new_status === '1'
    ));
}
