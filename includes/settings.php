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

    // Pengaturan Umum
    add_settings_section(
        'rental_mobil_general',
        __('Pengaturan Umum', 'rental-mobil-wp'),
        'rental_mobil_general_section_callback',
        'rental_mobil'
    );

    add_settings_field(
        'whatsapp_number',
        __('Nomor WhatsApp', 'rental-mobil-wp'),
        'rental_mobil_whatsapp_number_callback',
        'rental_mobil',
        'rental_mobil_general'
    );

    add_settings_field(
        'whatsapp_message',
        __('Template Pesan WhatsApp', 'rental-mobil-wp'),
        'rental_mobil_whatsapp_message_callback',
        'rental_mobil',
        'rental_mobil_general'
    );

    // Pengaturan Tampilan
    add_settings_section(
        'rental_mobil_style',
        __('Pengaturan Tampilan', 'rental-mobil-wp'),
        'rental_mobil_style_section_callback',
        'rental_mobil'
    );

    // Pengaturan Button
    add_settings_field(
        'button_color',
        __('Warna Button Utama', 'rental-mobil-wp'),
        'rental_mobil_button_color_callback',
        'rental_mobil',
        'rental_mobil_style'
    );

    add_settings_field(
        'button_hover_color',
        __('Warna Hover Button Utama', 'rental-mobil-wp'),
        'rental_mobil_button_hover_color_callback',
        'rental_mobil',
        'rental_mobil_style'
    );

    add_settings_field(
        'button_text_color',
        __('Warna Teks Button', 'rental-mobil-wp'),
        'rental_mobil_button_text_color_callback',
        'rental_mobil',
        'rental_mobil_style'
    );

    add_settings_field(
        'button_border_radius',
        __('Bentuk Button (Border Radius)', 'rental-mobil-wp'),
        'rental_mobil_button_border_radius_callback',
        'rental_mobil',
        'rental_mobil_style'
    );

    // Pengaturan Card
    add_settings_field(
        'card_border_radius',
        __('Bentuk Card (Border Radius)', 'rental-mobil-wp'),
        'rental_mobil_card_border_radius_callback',
        'rental_mobil',
        'rental_mobil_style'
    );

    add_settings_field(
        'card_shadow',
        __('Bayangan Card', 'rental-mobil-wp'),
        'rental_mobil_card_shadow_callback',
        'rental_mobil',
        'rental_mobil_style'
    );

    // Pengaturan Filter
    add_settings_field(
        'filter_options',
        __('Opsi Filter yang Ditampilkan', 'rental-mobil-wp'),
        'rental_mobil_filter_options_callback',
        'rental_mobil',
        'rental_mobil_style'
    );
}

/**
 * Section callback
 */
function rental_mobil_general_section_callback() {
    echo '<p>' . __('Pengaturan umum untuk plugin Rental Mobil.', 'rental-mobil-wp') . '</p>';
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

    return $output;
}

/**
 * Settings page
 */
function rental_mobil_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('rental_mobil_options');
            do_settings_sections('rental_mobil');
            submit_button(__('Simpan Pengaturan', 'rental-mobil-wp'));
            ?>
        </form>
    </div>
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
