<?php
/**
 * Halaman Pengaturan Plugin
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Register settings
 */
add_action('admin_init', 'rental_mobil_register_settings');
function rental_mobil_register_settings() {
    register_setting('rental_mobil_options', 'rental_mobil_options', 'rental_mobil_validate_options');

    // Tab Umum
    add_settings_section(
        'rental_mobil_general_section',
        __('Pengaturan Umum', 'rental-mobil-wp'),
        'rental_mobil_general_section_callback',
        'rental_mobil_general'
    );

    // Tab WhatsApp
    add_settings_section(
        'rental_mobil_whatsapp_section',
        __('Pengaturan WhatsApp', 'rental-mobil-wp'),
        'rental_mobil_whatsapp_section_callback',
        'rental_mobil_whatsapp'
    );

    add_settings_field(
        'whatsapp_number',
        __('Nomor WhatsApp', 'rental-mobil-wp'),
        'rental_mobil_whatsapp_number_callback',
        'rental_mobil_whatsapp',
        'rental_mobil_whatsapp_section'
    );

    add_settings_field(
        'whatsapp_message',
        __('Template Pesan WhatsApp', 'rental-mobil-wp'),
        'rental_mobil_whatsapp_message_callback',
        'rental_mobil_whatsapp',
        'rental_mobil_whatsapp_section'
    );

    // Tab Tampilan
    add_settings_section(
        'rental_mobil_style_section',
        __('Pengaturan Tampilan', 'rental-mobil-wp'),
        'rental_mobil_style_section_callback',
        'rental_mobil_style'
    );

    // Pengaturan Button
    add_settings_field(
        'button_color',
        __('Warna Button Utama', 'rental-mobil-wp'),
        'rental_mobil_button_color_callback',
        'rental_mobil_style',
        'rental_mobil_style_section'
    );

    add_settings_field(
        'button_hover_color',
        __('Warna Hover Button Utama', 'rental-mobil-wp'),
        'rental_mobil_button_hover_color_callback',
        'rental_mobil_style',
        'rental_mobil_style_section'
    );

    add_settings_field(
        'button_text_color',
        __('Warna Teks Button', 'rental-mobil-wp'),
        'rental_mobil_button_text_color_callback',
        'rental_mobil_style',
        'rental_mobil_style_section'
    );

    add_settings_field(
        'button_border_radius',
        __('Bentuk Button (Border Radius)', 'rental-mobil-wp'),
        'rental_mobil_button_border_radius_callback',
        'rental_mobil_style',
        'rental_mobil_style_section'
    );

    // Pengaturan Card
    add_settings_field(
        'card_border_radius',
        __('Bentuk Card (Border Radius)', 'rental-mobil-wp'),
        'rental_mobil_card_border_radius_callback',
        'rental_mobil_style',
        'rental_mobil_style_section'
    );

    add_settings_field(
        'card_shadow',
        __('Bayangan Card', 'rental-mobil-wp'),
        'rental_mobil_card_shadow_callback',
        'rental_mobil_style',
        'rental_mobil_style_section'
    );

    // Pengaturan Filter
    add_settings_field(
        'filter_options',
        __('Opsi Filter yang Ditampilkan', 'rental-mobil-wp'),
        'rental_mobil_filter_options_callback',
        'rental_mobil_style',
        'rental_mobil_style_section'
    );

    // Tab Homepage
    add_settings_section(
        'rental_mobil_homepage_section',
        __('Pengaturan Homepage', 'rental-mobil-wp'),
        'rental_mobil_homepage_section_callback',
        'rental_mobil_homepage'
    );

    add_settings_field(
        'homepage_vehicles',
        __('Kendaraan Pilihan untuk Homepage', 'rental-mobil-wp'),
        'rental_mobil_homepage_vehicles_callback',
        'rental_mobil_homepage',
        'rental_mobil_homepage_section'
    );
}

/**
 * General section callback
 */
function rental_mobil_general_section_callback() {
    echo '<p>' . __('Pengaturan umum untuk plugin Rental Mobil.', 'rental-mobil-wp') . '</p>';
}

/**
 * WhatsApp section callback
 */
function rental_mobil_whatsapp_section_callback() {
    echo '<p>' . __('Pengaturan WhatsApp untuk mengirim pesan booking.', 'rental-mobil-wp') . '</p>';
}

/**
 * Homepage section callback
 */
function rental_mobil_homepage_section_callback() {
    echo '<p>' . __('Pengaturan untuk tampilan kendaraan di homepage.', 'rental-mobil-wp') . '</p>';
}

/**
 * WhatsApp number field callback
 */
function rental_mobil_whatsapp_number_callback() {
    $options = get_option('rental_mobil_options');
    $whatsapp_number = isset($options['whatsapp_number']) ? $options['whatsapp_number'] : '';
    ?>
    <input type="text" id="whatsapp_number" name="rental_mobil_options[whatsapp_number]" value="<?php echo esc_attr($whatsapp_number); ?>" class="regular-text">
    <p class="description"><?php _e('Masukkan nomor WhatsApp dengan format internasional (contoh: 628123456789)', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * WhatsApp message template field callback
 */
function rental_mobil_whatsapp_message_callback() {
    $options = get_option('rental_mobil_options');
    $default_message = "Halo, saya ingin menyewa kendaraan *{nama_kendaraan}* dengan detail berikut:\n\nNama: {nama}\nDomisili: {domisili}\nTanggal Sewa: {tanggal_sewa}\nJam Sewa: {jam_sewa}\nDurasi Sewa: {durasi_sewa} {satuan_durasi}\n\nMohon informasi lebih lanjut. Terima kasih.";
    $whatsapp_message = isset($options['whatsapp_message']) ? $options['whatsapp_message'] : $default_message;
    ?>
    <textarea id="whatsapp_message" name="rental_mobil_options[whatsapp_message]" rows="10" class="large-text"><?php echo esc_textarea($whatsapp_message); ?></textarea>
    <p class="description">
        <?php _e('Template pesan WhatsApp. Gunakan placeholder berikut:', 'rental-mobil-wp'); ?>
        <br>
        <code>{nama_kendaraan}</code> - <?php _e('Nama kendaraan', 'rental-mobil-wp'); ?>
        <br>
        <code>{nama}</code> - <?php _e('Nama pemesan', 'rental-mobil-wp'); ?>
        <br>
        <code>{domisili}</code> - <?php _e('Domisili pemesan', 'rental-mobil-wp'); ?>
        <br>
        <code>{tanggal_sewa}</code> - <?php _e('Tanggal sewa', 'rental-mobil-wp'); ?>
        <br>
        <code>{jam_sewa}</code> - <?php _e('Jam sewa', 'rental-mobil-wp'); ?>
        <br>
        <code>{durasi_sewa}</code> - <?php _e('Durasi sewa', 'rental-mobil-wp'); ?>
        <br>
        <code>{satuan_durasi}</code> - <?php _e('Satuan durasi (hari/minggu/bulan/tahun)', 'rental-mobil-wp'); ?>
    </p>
    <?php
}

/**
 * Style section callback
 */
function rental_mobil_style_section_callback() {
    echo '<p>' . __('Kustomisasi tampilan untuk button, card, dan filter pada plugin Rental Mobil.', 'rental-mobil-wp') . '</p>';
}

/**
 * Button color field callback
 */
function rental_mobil_button_color_callback() {
    $options = get_option('rental_mobil_options');
    $button_color = isset($options['button_color']) ? $options['button_color'] : '#0073aa';
    ?>
    <input type="color" id="button_color" name="rental_mobil_options[button_color]" value="<?php echo esc_attr($button_color); ?>">
    <p class="description"><?php _e('Pilih warna untuk button utama (booking).', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Button hover color field callback
 */
function rental_mobil_button_hover_color_callback() {
    $options = get_option('rental_mobil_options');
    $button_hover_color = isset($options['button_hover_color']) ? $options['button_hover_color'] : '#005177';
    ?>
    <input type="color" id="button_hover_color" name="rental_mobil_options[button_hover_color]" value="<?php echo esc_attr($button_hover_color); ?>">
    <p class="description"><?php _e('Pilih warna hover untuk button utama.', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Button text color field callback
 */
function rental_mobil_button_text_color_callback() {
    $options = get_option('rental_mobil_options');
    $button_text_color = isset($options['button_text_color']) ? $options['button_text_color'] : '#ffffff';
    ?>
    <input type="color" id="button_text_color" name="rental_mobil_options[button_text_color]" value="<?php echo esc_attr($button_text_color); ?>">
    <p class="description"><?php _e('Pilih warna teks untuk button.', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Button border radius field callback
 */
function rental_mobil_button_border_radius_callback() {
    $options = get_option('rental_mobil_options');
    $button_border_radius = isset($options['button_border_radius']) ? $options['button_border_radius'] : '4';
    ?>
    <input type="range" id="button_border_radius" name="rental_mobil_options[button_border_radius]" min="0" max="50" value="<?php echo esc_attr($button_border_radius); ?>" oninput="this.nextElementSibling.value = this.value + 'px'">
    <output><?php echo esc_html($button_border_radius); ?>px</output>
    <p class="description"><?php _e('Atur bentuk sudut button (0px = kotak, 50px = bulat).', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Card border radius field callback
 */
function rental_mobil_card_border_radius_callback() {
    $options = get_option('rental_mobil_options');
    $card_border_radius = isset($options['card_border_radius']) ? $options['card_border_radius'] : '8';
    ?>
    <input type="range" id="card_border_radius" name="rental_mobil_options[card_border_radius]" min="0" max="50" value="<?php echo esc_attr($card_border_radius); ?>" oninput="this.nextElementSibling.value = this.value + 'px'">
    <output><?php echo esc_html($card_border_radius); ?>px</output>
    <p class="description"><?php _e('Atur bentuk sudut card (0px = kotak, 50px = bulat).', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Card shadow field callback
 */
function rental_mobil_card_shadow_callback() {
    $options = get_option('rental_mobil_options');
    $card_shadow = isset($options['card_shadow']) ? $options['card_shadow'] : 'medium';
    ?>
    <select id="card_shadow" name="rental_mobil_options[card_shadow]">
        <option value="none" <?php selected($card_shadow, 'none'); ?>><?php _e('Tidak Ada', 'rental-mobil-wp'); ?></option>
        <option value="light" <?php selected($card_shadow, 'light'); ?>><?php _e('Ringan', 'rental-mobil-wp'); ?></option>
        <option value="medium" <?php selected($card_shadow, 'medium'); ?>><?php _e('Sedang', 'rental-mobil-wp'); ?></option>
        <option value="heavy" <?php selected($card_shadow, 'heavy'); ?>><?php _e('Tebal', 'rental-mobil-wp'); ?></option>
    </select>
    <p class="description"><?php _e('Pilih tingkat bayangan untuk card kendaraan.', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Filter options field callback
 */
function rental_mobil_filter_options_callback() {
    $options = get_option('rental_mobil_options');
    $filter_options = isset($options['filter_options']) ? $options['filter_options'] : array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun');

    if (!is_array($filter_options)) {
        $filter_options = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun');
    }
    ?>
    <fieldset>
        <legend class="screen-reader-text"><?php _e('Opsi Filter yang Ditampilkan', 'rental-mobil-wp'); ?></legend>

        <label for="filter_merk">
            <input type="checkbox" id="filter_merk" name="rental_mobil_options[filter_options][]" value="merk" <?php checked(in_array('merk', $filter_options)); ?>>
            <?php _e('Merk Kendaraan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="filter_transmisi">
            <input type="checkbox" id="filter_transmisi" name="rental_mobil_options[filter_options][]" value="transmisi" <?php checked(in_array('transmisi', $filter_options)); ?>>
            <?php _e('Transmisi', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="filter_bahan_bakar">
            <input type="checkbox" id="filter_bahan_bakar" name="rental_mobil_options[filter_options][]" value="bahan_bakar" <?php checked(in_array('bahan_bakar', $filter_options)); ?>>
            <?php _e('Bahan Bakar', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="filter_tipe">
            <input type="checkbox" id="filter_tipe" name="rental_mobil_options[filter_options][]" value="tipe" <?php checked(in_array('tipe', $filter_options)); ?>>
            <?php _e('Tipe Kendaraan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="filter_tahun">
            <input type="checkbox" id="filter_tahun" name="rental_mobil_options[filter_options][]" value="tahun" <?php checked(in_array('tahun', $filter_options)); ?>>
            <?php _e('Tahun Kendaraan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="filter_orderby">
            <input type="checkbox" id="filter_orderby" name="rental_mobil_options[filter_options][]" value="orderby" <?php checked(in_array('orderby', $filter_options)); ?>>
            <?php _e('Urutan Berdasarkan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="filter_order">
            <input type="checkbox" id="filter_order" name="rental_mobil_options[filter_options][]" value="order" <?php checked(in_array('order', $filter_options)); ?>>
            <?php _e('Arah Urutan', 'rental-mobil-wp'); ?>
        </label>
    </fieldset>
    <p class="description"><?php _e('Pilih opsi filter yang ingin ditampilkan pada halaman daftar kendaraan.', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Homepage vehicles field callback
 */
function rental_mobil_homepage_vehicles_callback() {
    $options = get_option('rental_mobil_options');
    $homepage_vehicles = isset($options['homepage_vehicles']) ? $options['homepage_vehicles'] : array();

    // Dapatkan semua kendaraan
    $args = array(
        'post_type' => 'kendaraan',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $kendaraan_query = new WP_Query($args);

    if ($kendaraan_query->have_posts()) :
    ?>
    <div class="rental-mobil-homepage-vehicles">
        <p class="description"><?php _e('Pilih kendaraan yang akan ditampilkan di homepage.', 'rental-mobil-wp'); ?></p>

        <div class="rental-mobil-homepage-vehicles-list">
            <?php while ($kendaraan_query->have_posts()) : $kendaraan_query->the_post();
                $post_id = get_the_ID();
                $checked = in_array($post_id, $homepage_vehicles) ? 'checked' : '';
            ?>
            <div class="rental-mobil-homepage-vehicle-item">
                <label>
                    <input type="checkbox" name="rental_mobil_options[homepage_vehicles][]" value="<?php echo esc_attr($post_id); ?>" <?php echo $checked; ?>>
                    <?php the_title(); ?>
                    <?php if (has_post_thumbnail()) : ?>
                        <span class="rental-mobil-homepage-vehicle-thumbnail">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </span>
                    <?php endif; ?>
                </label>
            </div>
            <?php endwhile; ?>
        </div>

        <p class="description"><?php _e('Gunakan shortcode [kendaraan_pilihan] untuk menampilkan kendaraan pilihan di homepage.', 'rental-mobil-wp'); ?></p>
    </div>
    <?php
    wp_reset_postdata();
    else :
    ?>
    <p><?php _e('Tidak ada kendaraan yang tersedia.', 'rental-mobil-wp'); ?></p>
    <?php
    endif;
}

/**
 * Validate options
 */
function rental_mobil_validate_options($input) {
    $output = array();

    // Sanitize WhatsApp number
    if (isset($input['whatsapp_number'])) {
        $output['whatsapp_number'] = sanitize_text_field($input['whatsapp_number']);
    }

    // Sanitize WhatsApp message template
    if (isset($input['whatsapp_message'])) {
        $output['whatsapp_message'] = sanitize_textarea_field($input['whatsapp_message']);
    }

    // Sanitize button color
    if (isset($input['button_color'])) {
        $output['button_color'] = sanitize_hex_color($input['button_color']);
    }

    // Sanitize button hover color
    if (isset($input['button_hover_color'])) {
        $output['button_hover_color'] = sanitize_hex_color($input['button_hover_color']);
    }

    // Sanitize button text color
    if (isset($input['button_text_color'])) {
        $output['button_text_color'] = sanitize_hex_color($input['button_text_color']);
    }

    // Sanitize button border radius
    if (isset($input['button_border_radius'])) {
        $output['button_border_radius'] = absint($input['button_border_radius']);
    }

    // Sanitize card border radius
    if (isset($input['card_border_radius'])) {
        $output['card_border_radius'] = absint($input['card_border_radius']);
    }

    // Sanitize card shadow
    if (isset($input['card_shadow'])) {
        $output['card_shadow'] = sanitize_text_field($input['card_shadow']);
    }

    // Sanitize filter options
    if (isset($input['filter_options']) && is_array($input['filter_options'])) {
        $output['filter_options'] = array_map('sanitize_text_field', $input['filter_options']);
    } else {
        $output['filter_options'] = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun');
    }

    // Sanitize homepage vehicles
    if (isset($input['homepage_vehicles']) && is_array($input['homepage_vehicles'])) {
        $output['homepage_vehicles'] = array_map('absint', $input['homepage_vehicles']);
    } else {
        $output['homepage_vehicles'] = array();
    }

    return $output;
}

/**
 * Settings page
 */
function rental_mobil_settings_page() {
    // Cek tab aktif
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
    ?>
    <div class="wrap rental-mobil-settings">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <h2 class="nav-tab-wrapper">
            <a href="?page=rental-mobil&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-admin-generic"></span> <?php _e('Umum', 'rental-mobil-wp'); ?>
            </a>
            <a href="?page=rental-mobil&tab=whatsapp" class="nav-tab <?php echo $active_tab == 'whatsapp' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-whatsapp"></span> <?php _e('WhatsApp', 'rental-mobil-wp'); ?>
            </a>
            <a href="?page=rental-mobil&tab=style" class="nav-tab <?php echo $active_tab == 'style' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-admin-appearance"></span> <?php _e('Tampilan', 'rental-mobil-wp'); ?>
            </a>
            <a href="?page=rental-mobil&tab=homepage" class="nav-tab <?php echo $active_tab == 'homepage' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-admin-home"></span> <?php _e('Homepage', 'rental-mobil-wp'); ?>
            </a>
        </h2>

        <div class="rental-mobil-settings-content">
            <form action="options.php" method="post" id="rental-mobil-settings-form">
                <?php
                settings_fields('rental_mobil_options');

                // Tampilkan section berdasarkan tab aktif
                if ($active_tab == 'general') {
                    echo '<div id="rental-mobil-general-settings" class="rental-mobil-settings-tab">';
                    do_settings_sections('rental_mobil_general');
                    echo '</div>';
                } elseif ($active_tab == 'whatsapp') {
                    echo '<div id="rental-mobil-whatsapp-settings" class="rental-mobil-settings-tab">';
                    do_settings_sections('rental_mobil_whatsapp');
                    echo '</div>';
                } elseif ($active_tab == 'style') {
                    echo '<div id="rental-mobil-style-settings" class="rental-mobil-settings-tab">';
                    do_settings_sections('rental_mobil_style');
                    echo '</div>';
                } elseif ($active_tab == 'homepage') {
                    echo '<div id="rental-mobil-homepage-settings" class="rental-mobil-settings-tab">';
                    do_settings_sections('rental_mobil_homepage');
                    echo '</div>';
                }

                submit_button(__('Simpan Pengaturan', 'rental-mobil-wp'));
                ?>
                <input type="hidden" name="rental_mobil_active_tab" value="<?php echo esc_attr($active_tab); ?>">
            </form>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // AJAX save settings
        $('#rental-mobil-settings-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var formData = form.serialize();
            var submitButton = form.find(':submit');
            var originalText = submitButton.val();

            // Disable button and show loading
            submitButton.prop('disabled', true).val('<?php _e('Menyimpan...', 'rental-mobil-wp'); ?>');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'rental_mobil_save_settings',
                    nonce: '<?php echo wp_create_nonce('rental_mobil_save_settings'); ?>',
                    form_data: formData
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        var message = $('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
                        form.before(message);

                        // Auto dismiss after 3 seconds
                        setTimeout(function() {
                            message.fadeOut(function() {
                                $(this).remove();
                            });
                        }, 3000);
                    } else {
                        // Show error message
                        var message = $('<div class="notice notice-error is-dismissible"><p>' + response.data.message + '</p></div>');
                        form.before(message);
                    }

                    // Re-enable button
                    submitButton.prop('disabled', false).val(originalText);
                },
                error: function() {
                    // Show error message
                    var message = $('<div class="notice notice-error is-dismissible"><p><?php _e('Terjadi kesalahan. Silakan coba lagi.', 'rental-mobil-wp'); ?></p></div>');
                    form.before(message);

                    // Re-enable button
                    submitButton.prop('disabled', false).val(originalText);
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * Get WhatsApp number
 */
function rental_mobil_get_whatsapp_number() {
    $options = get_option('rental_mobil_options');
    return isset($options['whatsapp_number']) ? $options['whatsapp_number'] : '';
}

/**
 * Get WhatsApp message template
 */
function rental_mobil_get_whatsapp_message() {
    $options = get_option('rental_mobil_options');
    $default_message = "Halo, saya ingin menyewa kendaraan *{nama_kendaraan}* dengan detail berikut:\n\nNama: {nama}\nDomisili: {domisili}\nTanggal Sewa: {tanggal_sewa}\nJam Sewa: {jam_sewa}\nDurasi Sewa: {durasi_sewa} {satuan_durasi}\n\nMohon informasi lebih lanjut. Terima kasih.";
    return isset($options['whatsapp_message']) ? $options['whatsapp_message'] : $default_message;
}

/**
 * Get filter options
 */
function rental_mobil_get_filter_options() {
    $options = get_option('rental_mobil_options');
    $filter_options = isset($options['filter_options']) ? $options['filter_options'] : array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'orderby', 'order');

    if (!is_array($filter_options)) {
        $filter_options = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'orderby', 'order');
    }

    return $filter_options;
}

/**
 * Get style settings
 */
function rental_mobil_get_style_settings() {
    $options = get_option('rental_mobil_options');

    $style_settings = array(
        'button_color' => isset($options['button_color']) ? $options['button_color'] : '#0073aa',
        'button_hover_color' => isset($options['button_hover_color']) ? $options['button_hover_color'] : '#005177',
        'button_text_color' => isset($options['button_text_color']) ? $options['button_text_color'] : '#ffffff',
        'button_border_radius' => isset($options['button_border_radius']) ? $options['button_border_radius'] : '4',
        'card_border_radius' => isset($options['card_border_radius']) ? $options['card_border_radius'] : '8',
        'card_shadow' => isset($options['card_shadow']) ? $options['card_shadow'] : 'medium',
    );

    return $style_settings;
}

/**
 * Get homepage vehicles
 */
function rental_mobil_get_homepage_vehicles() {
    $options = get_option('rental_mobil_options');
    $homepage_vehicles = isset($options['homepage_vehicles']) ? $options['homepage_vehicles'] : array();

    return $homepage_vehicles;
}

/**
 * AJAX handler untuk menyimpan pengaturan
 */
add_action('wp_ajax_rental_mobil_save_settings', 'rental_mobil_save_settings_ajax');
function rental_mobil_save_settings_ajax() {
    // Verifikasi nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_save_settings')) {
        wp_send_json_error(array('message' => __('Verifikasi keamanan gagal.', 'rental-mobil-wp')));
    }

    // Verifikasi permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Anda tidak memiliki izin untuk melakukan tindakan ini.', 'rental-mobil-wp')));
    }

    // Parse form data
    parse_str($_POST['form_data'], $form_data);

    // Update options
    if (isset($form_data['rental_mobil_options'])) {
        update_option('rental_mobil_options', $form_data['rental_mobil_options']);
        wp_send_json_success(array('message' => __('Pengaturan berhasil disimpan.', 'rental-mobil-wp')));
    } else {
        wp_send_json_error(array('message' => __('Tidak ada data yang disimpan.', 'rental-mobil-wp')));
    }
}

/**
 * Get custom CSS based on settings
 */
function rental_mobil_get_custom_css() {
    $style_settings = rental_mobil_get_style_settings();

    // Get values from settings
    $button_color = $style_settings['button_color'];
    $button_hover_color = $style_settings['button_hover_color'];
    $button_text_color = $style_settings['button_text_color'];
    $button_border_radius = $style_settings['button_border_radius'];
    $card_border_radius = $style_settings['card_border_radius'];
    $card_shadow = $style_settings['card_shadow'];

    // Set shadow based on option
    $shadow_value = '0 2px 4px rgba(0, 0, 0, 0.1)';
    if ($card_shadow === 'light') {
        $shadow_value = '0 1px 3px rgba(0, 0, 0, 0.08)';
    } elseif ($card_shadow === 'medium') {
        $shadow_value = '0 2px 4px rgba(0, 0, 0, 0.1)';
    } elseif ($card_shadow === 'heavy') {
        $shadow_value = '0 4px 8px rgba(0, 0, 0, 0.15)';
    } elseif ($card_shadow === 'none') {
        $shadow_value = 'none';
    }

    // Generate CSS
    $css = "
    /* Custom Rental Mobil Styles */
    .rental-mobil-button {
        background-color: {$button_color};
        color: {$button_text_color};
        border-radius: {$button_border_radius}px;
    }

    .rental-mobil-button:hover {
        background-color: {$button_hover_color};
        color: {$button_text_color};
    }

    .rental-mobil-card {
        border-radius: {$card_border_radius}px;
        box-shadow: {$shadow_value};
    }

    .rental-mobil-card:hover {
        box-shadow: " . ($card_shadow === 'none' ? 'none' : '0 5px 15px rgba(0, 0, 0, 0.1)') . ";
    }

    .rental-mobil-filter-toggle {
        background-color: {$button_color};
        border-radius: 50%;
    }

    .rental-mobil-filter-toggle:hover {
        background-color: {$button_hover_color};
    }

    .rental-mobil-gallery-prev,
    .rental-mobil-gallery-next {
        background-color: {$button_color};
        color: {$button_text_color};
    }

    .rental-mobil-gallery-prev:hover,
    .rental-mobil-gallery-next:hover {
        background-color: {$button_hover_color};
    }

    .rental-mobil-floating-booking {
        background-color: {$button_color};
        color: {$button_text_color};
        border-radius: {$button_border_radius}px;
    }

    .rental-mobil-floating-booking:hover {
        background-color: {$button_hover_color};
    }
    ";

    return $css;
}

/**
 * Add custom CSS to frontend
 */
add_action('wp_enqueue_scripts', 'rental_mobil_add_custom_css', 20);
function rental_mobil_add_custom_css() {
    $custom_css = rental_mobil_get_custom_css();
    wp_add_inline_style('rental-mobil-style', $custom_css);
}
