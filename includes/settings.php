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

    // Tab Dokumentasi
    add_settings_section(
        'rental_mobil_documentation_section',
        __('Dokumentasi Plugin', 'rental-mobil-wp'),
        'rental_mobil_documentation_section_callback',
        'rental_mobil_documentation'
    );

    // Tab Lisensi dihapus

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

    // Tab Filter
    add_settings_section(
        'rental_mobil_filter_section',
        __('Pengaturan Filter', 'rental-mobil-wp'),
        'rental_mobil_filter_section_callback',
        'rental_mobil_filter'
    );

    // Pengaturan Filter Frontend
    add_settings_field(
        'frontend_filter_options',
        __('Filter untuk Frontend', 'rental-mobil-wp'),
        'rental_mobil_frontend_filter_options_callback',
        'rental_mobil_filter',
        'rental_mobil_filter_section'
    );

    // Pengaturan Filter Admin
    add_settings_field(
        'admin_filter_options',
        __('Filter untuk Admin', 'rental-mobil-wp'),
        'rental_mobil_admin_filter_options_callback',
        'rental_mobil_filter',
        'rental_mobil_filter_section'
    );

    // Tab Share
    add_settings_section(
        'rental_mobil_share_section',
        __('Pengaturan Share', 'rental-mobil-wp'),
        'rental_mobil_share_section_callback',
        'rental_mobil_share'
    );

    // Pengaturan Platform Share
    add_settings_field(
        'share_platforms',
        __('Platform Share', 'rental-mobil-wp'),
        'rental_mobil_share_platforms_callback',
        'rental_mobil_share',
        'rental_mobil_share_section'
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

    // Pengaturan Posisi Ikon Filter di Mobile
    add_settings_field(
        'filter_icon_position',
        __('Posisi Ikon Filter di Mobile', 'rental-mobil-wp'),
        'rental_mobil_filter_icon_position_callback',
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

    add_settings_field(
        'slider_settings',
        __('Pengaturan Slider Kendaraan Unggulan', 'rental-mobil-wp'),
        'rental_mobil_slider_settings_callback',
        'rental_mobil_homepage',
        'rental_mobil_homepage_section'
    );

    add_settings_field(
        'homepage_vehicle_order',
        __('Pengaturan Urutan Kendaraan Homepage', 'rental-mobil-wp'),
        'rental_mobil_homepage_vehicle_order_callback',
        'rental_mobil_homepage',
        'rental_mobil_homepage_section'
    );

    add_settings_field(
        'shortcode_vehicle_order',
        __('Pengaturan Urutan Kendaraan Shortcode', 'rental-mobil-wp'),
        'rental_mobil_shortcode_vehicle_order_callback',
        'rental_mobil_homepage',
        'rental_mobil_homepage_section'
    );
}

/**
 * Documentation section callback
 */
function rental_mobil_documentation_section_callback() {
    $plugin_data = get_plugin_data(RENTAL_MOBIL_PLUGIN_FILE);
    $version = $plugin_data['Version'];

    echo '<style>
        .rental-mobil-version {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .rental-mobil-license-badge-doc {
            display: inline-block;
            margin-left: 10px;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: normal;
        }
        .rental-mobil-license-active-doc {
            background-color: #46b450;
            color: white;
        }
        .rental-mobil-license-inactive-doc {
            background-color: #dc3232;
            color: white;
        }
        .rental-mobil-buy-license {
            margin-top: 10px;
        }
        .rental-mobil-buy-license .button {
            background-color: #0073aa;
            border-color: #0073aa;
            color: white;
        }
        .rental-mobil-buy-license .button:hover {
            background-color: #005f8a;
            border-color: #005f8a;
        }
    </style>';

    echo '<div class="rental-mobil-documentation">';
    echo '<div class="rental-mobil-version"><strong>' . __('Versi Plugin:', 'rental-mobil-wp') . '</strong> ' . esc_html($version) . ' <span class="rental-mobil-license-badge-doc rental-mobil-license-active-doc">' . __('Gratis', 'rental-mobil-wp') . '</span></div>';

    echo '<h3>' . __('Penggunaan Shortcode', 'rental-mobil-wp') . '</h3>';
    echo '<div class="rental-mobil-shortcode-docs">';

    echo '<div class="rental-mobil-shortcode-item">';
    echo '<h4>[daftar_kendaraan]</h4>';
    echo '<p>' . __('Menampilkan daftar kendaraan dengan filter di sidebar.', 'rental-mobil-wp') . '</p>';
    echo '<pre>[daftar_kendaraan jumlah="10" orderby="date" order="DESC" merk="" transmisi="" bahan_bakar="" tipe="" tahun=""]</pre>';
    echo '<p><strong>' . __('Parameter:', 'rental-mobil-wp') . '</strong></p>';
    echo '<ul>';
    echo '<li><code>jumlah</code> - ' . __('Jumlah kendaraan yang ditampilkan (default: 10)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>orderby</code> - ' . __('Mengurutkan berdasarkan (date, title, meta_value_num)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>order</code> - ' . __('Urutan (ASC, DESC)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>merk</code> - ' . __('Filter berdasarkan merk (slug)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>transmisi</code> - ' . __('Filter berdasarkan transmisi (slug)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>bahan_bakar</code> - ' . __('Filter berdasarkan bahan bakar (slug)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>tipe</code> - ' . __('Filter berdasarkan tipe kendaraan (slug)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>tahun</code> - ' . __('Filter berdasarkan tahun kendaraan (slug)', 'rental-mobil-wp') . '</li>';
    echo '</ul>';
    echo '</div>';

    echo '<div class="rental-mobil-shortcode-item">';
    echo '<h4>[detail_kendaraan]</h4>';
    echo '<p>' . __('Menampilkan detail kendaraan berdasarkan ID atau slug.', 'rental-mobil-wp') . '</p>';
    echo '<pre>[detail_kendaraan id="123" slug="nama-kendaraan"]</pre>';
    echo '<p><strong>' . __('Parameter:', 'rental-mobil-wp') . '</strong></p>';
    echo '<ul>';
    echo '<li><code>id</code> - ' . __('ID kendaraan', 'rental-mobil-wp') . '</li>';
    echo '<li><code>slug</code> - ' . __('Slug kendaraan', 'rental-mobil-wp') . '</li>';
    echo '</ul>';
    echo '</div>';

    echo '<div class="rental-mobil-shortcode-item">';
    echo '<h4>[kendaraan_unggulan]</h4>';
    echo '<p>' . __('Menampilkan slider kendaraan unggulan.', 'rental-mobil-wp') . '</p>';
    echo '<pre>[kendaraan_unggulan jumlah="5" judul="Kendaraan Unggulan" auto_slide="true" loop="true" speed="300" interval="5000"]</pre>';
    echo '<p><strong>' . __('Parameter:', 'rental-mobil-wp') . '</strong></p>';
    echo '<ul>';
    echo '<li><code>jumlah</code> - ' . __('Jumlah kendaraan yang ditampilkan (default: 5)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>judul</code> - ' . __('Judul slider (default: Kendaraan Unggulan)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>auto_slide</code> - ' . __('Aktifkan auto slide (true/false, default: sesuai pengaturan)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>loop</code> - ' . __('Aktifkan loop slider (true/false, default: sesuai pengaturan)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>speed</code> - ' . __('Kecepatan transisi dalam milidetik (default: sesuai pengaturan)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>interval</code> - ' . __('Interval waktu antar slide dalam milidetik (default: sesuai pengaturan)', 'rental-mobil-wp') . '</li>';
    echo '</ul>';
    echo '</div>';

    echo '</div>';

    echo '<h3>' . __('Kontribusi', 'rental-mobil-wp') . '</h3>';
    echo '<p>' . sprintf(__('Plugin ini open source dan Anda dapat berkontribusi di %s', 'rental-mobil-wp'), '<a href="https://github.com/tupski/rental-mobil-wp" target="_blank">GitHub</a>') . '</p>';

    // Tambahkan tombol Trakteer
    echo '<div class="rental-mobil-trakteer-button" style="margin-top: 20px; margin-bottom: 20px;">';
    echo '<p>' . __('Jika Anda menyukai plugin ini, Anda dapat mendukung pengembang dengan mentraktir kopi:', 'rental-mobil-wp') . '</p>';
    echo '<a href="https://trakteer.id/username-anda" target="_blank" style="display: inline-block;">';
    echo '<img src="https://cdn.trakteer.id/images/embed/trbtn-red-1.png" alt="Trakteer Saya" height="40" style="border:0px;height:40px;">';
    echo '</a>';
    echo '</div>';

    echo '</div>';
}

/**
 * Fungsi-fungsi terkait lisensi dihapus
 */

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

    echo '<div class="rental-mobil-homepage-shortcodes">';
    echo '<h3>' . __('Cara Penggunaan Shortcode untuk Homepage', 'rental-mobil-wp') . '</h3>';

    echo '<div class="rental-mobil-shortcode-item">';
    echo '<h4>[kendaraan_pilihan]</h4>';
    echo '<p>' . __('Menampilkan kendaraan pilihan yang telah Anda pilih di pengaturan ini.', 'rental-mobil-wp') . '</p>';
    echo '<pre>[kendaraan_pilihan judul="Kendaraan Pilihan" jumlah="6"]</pre>';
    echo '<p><strong>' . __('Parameter:', 'rental-mobil-wp') . '</strong></p>';
    echo '<ul>';
    echo '<li><code>judul</code> - ' . __('Judul section (default: "Kendaraan Pilihan")', 'rental-mobil-wp') . '</li>';
    echo '<li><code>jumlah</code> - ' . __('Jumlah kendaraan yang ditampilkan (default: semua kendaraan pilihan)', 'rental-mobil-wp') . '</li>';
    echo '</ul>';
    echo '</div>';

    echo '<div class="rental-mobil-shortcode-item">';
    echo '<h4>[kendaraan_unggulan]</h4>';
    echo '<p>' . __('Menampilkan slider kendaraan unggulan.', 'rental-mobil-wp') . '</p>';
    echo '<pre>[kendaraan_unggulan jumlah="5" judul="Kendaraan Unggulan" auto_slide="true" loop="true" speed="300" interval="5000"]</pre>';
    echo '<p><strong>' . __('Parameter:', 'rental-mobil-wp') . '</strong></p>';
    echo '<ul>';
    echo '<li><code>jumlah</code> - ' . __('Jumlah kendaraan yang ditampilkan (default: 5)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>judul</code> - ' . __('Judul slider (default: Kendaraan Unggulan)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>auto_slide</code> - ' . __('Aktifkan auto slide (true/false, default: sesuai pengaturan)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>loop</code> - ' . __('Aktifkan loop slider (true/false, default: sesuai pengaturan)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>speed</code> - ' . __('Kecepatan transisi dalam milidetik (default: sesuai pengaturan)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>interval</code> - ' . __('Interval waktu antar slide dalam milidetik (default: sesuai pengaturan)', 'rental-mobil-wp') . '</li>';
    echo '</ul>';
    echo '</div>';

    echo '</div>';

    echo '<style>
    .rental-mobil-homepage-shortcodes {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 4px;
        border-left: 4px solid #0073aa;
        margin-bottom: 20px;
    }
    .rental-mobil-homepage-shortcodes h3 {
        margin-top: 0;
    }
    .rental-mobil-shortcode-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .rental-mobil-shortcode-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .rental-mobil-shortcode-item h4 {
        margin-bottom: 5px;
    }
    .rental-mobil-shortcode-item pre {
        background: #f0f0f0;
        padding: 10px;
        border-radius: 3px;
        overflow: auto;
    }
    </style>';
}

/**
 * WhatsApp number field callback
 */
function rental_mobil_whatsapp_number_callback() {
    $options = rental_mobil_get_options();
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
    $options = rental_mobil_get_options();
    $default_message = "Halo, saya ingin menyewa kendaraan *{nama_kendaraan}* dengan detail berikut:\n\nNama: {nama}\nDomisili: {domisili}\nTanggal Sewa: {tanggal_sewa}\nJam Sewa: {jam_sewa}\nDurasi Sewa: {durasi_sewa} {satuan_durasi}\n\nMohon informasi lebih lanjut. Terima kasih.";
    $whatsapp_message = isset($options['whatsapp_message']) ? $options['whatsapp_message'] : $default_message;
    ?>
    <textarea id="whatsapp_message" name="rental_mobil_options[whatsapp_message]" rows="10" class="large-text"><?php echo esc_textarea($whatsapp_message); ?></textarea>
    <p class="description">
        <?php _e('Template pesan WhatsApp. Gunakan placeholder berikut:', 'rental-mobil-wp'); ?>
        <br>
        <code>{nama_kendaraan}</code> - <?php _e('Nama kendaraan', 'rental-mobil-wp'); ?>
        <br>
        <?php
        // Dapatkan form fields dari pengaturan
        $form_fields = rental_mobil_get_form_fields();

        // Tampilkan placeholder untuk setiap field
        if (!empty($form_fields)) {
            foreach ($form_fields as $field) {
                echo '<code>{' . esc_html($field['id']) . '}</code> - ' . esc_html($field['label']) . '<br>';
            }
        } else {
            // Tampilkan placeholder default jika form fields belum diatur
            ?>
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
            <?php
        }
        ?>
        <br>
        <strong><?php _e('Catatan:', 'rental-mobil-wp'); ?></strong> <?php _e('Placeholder akan otomatis dibuat untuk setiap field form yang Anda tambahkan dengan format {id_field}.', 'rental-mobil-wp'); ?>
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
    $options = rental_mobil_get_options();
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
    $options = rental_mobil_get_options();
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
    $options = rental_mobil_get_options();
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
    $options = rental_mobil_get_options();
    $button_border_radius = isset($options['button_border_radius']) ? $options['button_border_radius'] : '4';
    ?>
    <input type="number" id="button_border_radius" name="rental_mobil_options[button_border_radius]" min="0" max="50" value="<?php echo esc_attr($button_border_radius); ?>" style="width: 70px;">
    <output><?php echo esc_html($button_border_radius); ?>px</output>
    <p class="description"><?php _e('Atur bentuk sudut button (0px = kotak, 50px = bulat).', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Card border radius field callback
 */
function rental_mobil_card_border_radius_callback() {
    $options = rental_mobil_get_options();
    $card_border_radius = isset($options['card_border_radius']) ? $options['card_border_radius'] : '8';
    ?>
    <input type="number" id="card_border_radius" name="rental_mobil_options[card_border_radius]" min="0" max="50" value="<?php echo esc_attr($card_border_radius); ?>" style="width: 70px;">
    <output><?php echo esc_html($card_border_radius); ?>px</output>
    <p class="description"><?php _e('Atur bentuk sudut card (0px = kotak, 50px = bulat).', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Card shadow field callback
 */
function rental_mobil_card_shadow_callback() {
    $options = rental_mobil_get_options();
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
    $options = rental_mobil_get_options();
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
 * Filter icon position field callback
 */
function rental_mobil_filter_icon_position_callback() {
    $options = rental_mobil_get_options();
    $filter_icon_position = isset($options['filter_icon_position']) ? $options['filter_icon_position'] : 'bottom-right';
    ?>
    <select id="filter_icon_position" name="rental_mobil_options[filter_icon_position]">
        <option value="bottom-right" <?php selected($filter_icon_position, 'bottom-right'); ?>><?php _e('Kanan Bawah', 'rental-mobil-wp'); ?></option>
        <option value="bottom-left" <?php selected($filter_icon_position, 'bottom-left'); ?>><?php _e('Kiri Bawah', 'rental-mobil-wp'); ?></option>
        <option value="middle-right" <?php selected($filter_icon_position, 'middle-right'); ?>><?php _e('Tengah Kanan', 'rental-mobil-wp'); ?></option>
        <option value="middle-left" <?php selected($filter_icon_position, 'middle-left'); ?>><?php _e('Tengah Kiri', 'rental-mobil-wp'); ?></option>
    </select>
    <p class="description"><?php _e('Pilih posisi ikon filter pada tampilan mobile.', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Homepage vehicles field callback
 */
function rental_mobil_homepage_vehicles_callback() {
    $options = rental_mobil_get_options();
    $homepage_vehicles  = isset($options['homepage_vehicles']) ? $options['homepage_vehicles'] : array();

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

    // Sanitize filter icon position
    if (isset($input['filter_icon_position'])) {
        $valid_positions = array('bottom-right', 'bottom-left', 'middle-right', 'middle-left');
        $output['filter_icon_position'] = in_array($input['filter_icon_position'], $valid_positions) ? $input['filter_icon_position'] : 'bottom-right';
    }

    // Sanitize homepage vehicles
    if (isset($input['homepage_vehicles']) && is_array($input['homepage_vehicles'])) {
        $output['homepage_vehicles'] = array_map('absint', $input['homepage_vehicles']);
    } else {
        $output['homepage_vehicles'] = array();
    }

    // Sanitize slider settings
    if (isset($input['slider_auto_slide'])) {
        $output['slider_auto_slide'] = (bool) $input['slider_auto_slide'];
    }

    if (isset($input['slider_loop'])) {
        $output['slider_loop'] = (bool) $input['slider_loop'];
    }

    if (isset($input['slider_speed'])) {
        $output['slider_speed'] = absint($input['slider_speed']);
        if ($output['slider_speed'] < 100) {
            $output['slider_speed'] = 300;
        }
    }

    if (isset($input['slider_interval'])) {
        $output['slider_interval'] = absint($input['slider_interval']);
        if ($output['slider_interval'] < 1000) {
            $output['slider_interval'] = 5000;
        }
    }

    // Sanitize homepage order settings
    if (isset($input['homepage_orderby'])) {
        $valid_orderby = array('date', 'title', 'meta_value_num', 'harga_harian', 'rand');
        $output['homepage_orderby'] = in_array($input['homepage_orderby'], $valid_orderby) ? $input['homepage_orderby'] : 'date';
    }

    if (isset($input['homepage_order'])) {
        $valid_order = array('ASC', 'DESC');
        $output['homepage_order'] = in_array($input['homepage_order'], $valid_order) ? $input['homepage_order'] : 'DESC';
    }

    // Sanitize shortcode order settings
    if (isset($input['shortcode_orderby'])) {
        $valid_orderby = array('date', 'title', 'meta_value_num', 'price_high', 'price_low', 'rand');
        $output['shortcode_orderby'] = in_array($input['shortcode_orderby'], $valid_orderby) ? $input['shortcode_orderby'] : 'date';
    }

    if (isset($input['shortcode_order'])) {
        $valid_order = array('ASC', 'DESC');
        $output['shortcode_order'] = in_array($input['shortcode_order'], $valid_order) ? $input['shortcode_order'] : 'DESC';
    }

    // Sanitize frontend filter options
    if (isset($input['frontend_filter_options']) && is_array($input['frontend_filter_options'])) {
        $valid_filters = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'orderby', 'order');
        $output['frontend_filter_options'] = array_intersect($input['frontend_filter_options'], $valid_filters);
    } else {
        $output['frontend_filter_options'] = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'orderby', 'order');
    }

    // Sanitize admin filter options
    if (isset($input['admin_filter_options']) && is_array($input['admin_filter_options'])) {
        $valid_filters = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'featured', 'popular');
        $output['admin_filter_options'] = array_intersect($input['admin_filter_options'], $valid_filters);
    } else {
        $output['admin_filter_options'] = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'featured', 'popular');
    }

    // Sanitize share platforms
    if (isset($input['share_platforms']) && is_array($input['share_platforms'])) {
        $valid_platforms = array('whatsapp', 'facebook', 'twitter', 'telegram', 'email');
        $output['share_platforms'] = array_intersect($input['share_platforms'], $valid_platforms);
    } else {
        $output['share_platforms'] = array('whatsapp', 'facebook', 'twitter', 'telegram', 'email');
    }

    // Sanitize license key
    if (isset($input['license_key'])) {
        $output['license_key'] = sanitize_text_field($input['license_key']);
    }

    // Preserve license status and expires
    $options = rental_mobil_get_options();
    if (isset($options['license_status'])) {
        $output['license_status'] = $options['license_status'];
    }
    if (isset($options['license_expires'])) {
        $output['license_expires'] = $options['license_expires'];
    }

    // Sanitize form fields
    if (isset($input['form_fields'])) {
        // Jika form_fields adalah string JSON, decode terlebih dahulu
        if (is_string($input['form_fields'])) {
            $form_fields = json_decode($input['form_fields'], true);
        } else {
            $form_fields = $input['form_fields'];
        }

        // Pastikan form_fields adalah array
        if (is_array($form_fields)) {
            // Sanitize setiap field
            foreach ($form_fields as $key => $field) {
                if (isset($field['id'])) {
                    $form_fields[$key]['id'] = sanitize_text_field($field['id']);
                }
                if (isset($field['label'])) {
                    $form_fields[$key]['label'] = sanitize_text_field($field['label']);
                }
                if (isset($field['type'])) {
                    $form_fields[$key]['type'] = sanitize_text_field($field['type']);
                }
                if (isset($field['placeholder'])) {
                    $form_fields[$key]['placeholder'] = sanitize_text_field($field['placeholder']);
                }
                if (isset($field['required'])) {
                    $form_fields[$key]['required'] = (bool) $field['required'];
                }
                if (isset($field['order'])) {
                    $form_fields[$key]['order'] = absint($field['order']);
                }
                if (isset($field['options']) && is_array($field['options'])) {
                    $sanitized_options = array();
                    foreach ($field['options'] as $option_key => $option_value) {
                        $sanitized_options[sanitize_text_field($option_key)] = sanitize_text_field($option_value);
                    }
                    $form_fields[$key]['options'] = $sanitized_options;
                }
            }

            $output['form_fields'] = $form_fields;
        } else {
            // Jika bukan array, gunakan form_fields yang sudah ada
            $output['form_fields'] = isset($options['form_fields']) ? $options['form_fields'] : array();
        }
    } else {
        // Jika tidak ada form_fields di input, gunakan yang sudah ada
        $output['form_fields'] = isset($options['form_fields']) ? $options['form_fields'] : array();
    }

    return $output;
}

/**
 * Settings page
 */
function rental_mobil_settings_page() {
    // Cek tab aktif
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'documentation';
    ?>
    <div class="wrap rental-mobil-settings">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <!-- Header removed -->

        <h2 class="nav-tab-wrapper">
            <a href="?page=rental-mobil-settings&tab=documentation" class="nav-tab <?php echo $active_tab == 'documentation' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-book"></span> <?php _e('Dokumentasi', 'rental-mobil-wp'); ?>
            </a>
            <a href="?page=rental-mobil-settings&tab=whatsapp" class="nav-tab <?php echo $active_tab == 'whatsapp' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-whatsapp"></span> <?php _e('WhatsApp', 'rental-mobil-wp'); ?>
            </a>
            <a href="?page=rental-mobil-settings&tab=style" class="nav-tab <?php echo $active_tab == 'style' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-admin-appearance"></span> <?php _e('Tampilan', 'rental-mobil-wp'); ?>
            </a>
            <a href="?page=rental-mobil-settings&tab=filter" class="nav-tab <?php echo $active_tab == 'filter' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-filter"></span> <?php _e('Filter', 'rental-mobil-wp'); ?>
            </a>
            <a href="?page=rental-mobil-settings&tab=share" class="nav-tab <?php echo $active_tab == 'share' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-share"></span> <?php _e('Share', 'rental-mobil-wp'); ?>
            </a>
            <a href="?page=rental-mobil-settings&tab=homepage" class="nav-tab <?php echo $active_tab == 'homepage' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-admin-home"></span> <?php _e('Homepage', 'rental-mobil-wp'); ?>
            </a>
            <?php do_action('rental_mobil_settings_tabs', $active_tab); ?>
        </h2>

        <div class="rental-mobil-settings-content">
            <form action="options.php" method="post" id="rental-mobil-settings-form">
                <?php
                settings_fields('rental_mobil_options');

                // Tampilkan section berdasarkan tab aktif
                if ($active_tab == 'documentation') {
                    echo '<div id="rental-mobil-documentation-settings" class="rental-mobil-settings-tab">';
                    do_settings_sections('rental_mobil_documentation');
                    echo '</div>';
                // Tab lisensi dihapus
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
                } elseif ($active_tab == 'filter') {
                    echo '<div id="rental-mobil-filter-settings" class="rental-mobil-settings-tab">';
                    do_settings_sections('rental_mobil_filter');
                    echo '</div>';
                } elseif ($active_tab == 'share') {
                    echo '<div id="rental-mobil-share-settings" class="rental-mobil-settings-tab">';
                    do_settings_sections('rental_mobil_share');
                    echo '</div>';
                } elseif ($active_tab == 'form_builder') {
                    echo '<div id="rental-mobil-form-builder-settings" class="rental-mobil-settings-tab">';
                    do_settings_sections('rental_mobil_form_builder');
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

                        // Update form fields with saved values
                        if (response.data.options) {
                            // Update text and textarea fields
                            $.each(response.data.options, function(key, value) {
                                var field = form.find('[name="rental_mobil_options[' + key + ']"]');
                                if (field.length > 0) {
                                    if (field.is('input[type="text"]') || field.is('input[type="color"]') || field.is('textarea') || field.is('input[type="range"]') || field.is('select')) {
                                        field.val(value);
                                    } else if (field.is('input[type="checkbox"]')) {
                                        field.prop('checked', value === '1');
                                    }
                                }
                            });

                            // Update checkbox arrays
                            if (response.data.options.filter_options) {
                                form.find('input[name="rental_mobil_options[filter_options][]"]').each(function() {
                                    var checkbox = $(this);
                                    checkbox.prop('checked', response.data.options.filter_options.indexOf(checkbox.val()) !== -1);
                                });
                            }

                            // Update homepage vehicles
                            if (response.data.options.homepage_vehicles) {
                                form.find('input[name="rental_mobil_options[homepage_vehicles][]"]').each(function() {
                                    var checkbox = $(this);
                                    checkbox.prop('checked', response.data.options.homepage_vehicles.indexOf(parseInt(checkbox.val())) !== -1);
                                });
                            }
                        }

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
 * Get plugin options with fresh data
 */
function rental_mobil_get_options() {
    // Hapus cache opsi untuk memastikan data terbaru
    wp_cache_delete('rental_mobil_options', 'options');
    wp_cache_delete('alloptions', 'options');

    // Dapatkan opsi dari database dengan force refresh
    $options = get_option('rental_mobil_options', array(), false);

    // Jika opsi kosong, coba lagi dengan default
    if (empty($options) || !is_array($options)) {
        $options = array(
            'license_key' => '',
            'license_status' => '',
            'license_expires' => '',
            'license_customer' => '',
            'license_created_at' => '',
            'license_domain_count' => '',
            'license_max_domains' => '',
            'form_fields' => array()
        );

        // Simpan opsi default ke database
        update_option('rental_mobil_options', $options, 'yes');
    }

    // Pastikan form_fields selalu ada
    if (!isset($options['form_fields'])) {
        $options['form_fields'] = array();
    }

    return $options;
}

/**
 * Get WhatsApp number
 */
function rental_mobil_get_whatsapp_number() {
    $options = rental_mobil_get_options();
    return isset($options['whatsapp_number']) ? $options['whatsapp_number'] : '';
}

/**
 * Get WhatsApp message template
 */
function rental_mobil_get_whatsapp_message() {
    $options = rental_mobil_get_options();
    $default_message = "Halo, saya ingin menyewa kendaraan *{nama_kendaraan}* dengan detail berikut:\n\nNama: {nama}\nDomisili: {domisili}\nTanggal Sewa: {tanggal_sewa}\nJam Sewa: {jam_sewa}\nDurasi Sewa: {durasi_sewa} {satuan_durasi}\n\nMohon informasi lebih lanjut. Terima kasih.";
    return isset($options['whatsapp_message']) ? $options['whatsapp_message'] : $default_message;
}

/**
 * Get filter options (deprecated, use rental_mobil_get_frontend_filter_options() instead)
 * @deprecated
 */
// function rental_mobil_get_filter_options() {
//     $options = rental_mobil_get_options();
//     $filter_options = isset($options['filter_options']) ? $options['filter_options'] : array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'orderby', 'order');

//     if (!is_array($filter_options)) {
//         $filter_options = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'orderby', 'order');
//     }

//     return $filter_options;
// }

/**
 * Get filter icon position
 */
function rental_mobil_get_filter_icon_position() {
    $options = rental_mobil_get_options();
    return isset($options['filter_icon_position']) ? $options['filter_icon_position'] : 'bottom-right';
}

/**
 * Get style settings
 */
function rental_mobil_get_style_settings() {
    $options = rental_mobil_get_options();

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
 * Slider settings callback
 */
function rental_mobil_slider_settings_callback() {
    $options = rental_mobil_get_options();
    $auto_slide = isset($options['slider_auto_slide']) ? $options['slider_auto_slide'] : true;
    $loop = isset($options['slider_loop']) ? $options['slider_loop'] : true;
    $speed = isset($options['slider_speed']) ? $options['slider_speed'] : 300;
    $interval = isset($options['slider_interval']) ? $options['slider_interval'] : 5000;
    ?>
    <div class="rental-mobil-slider-settings">
        <p>
            <label for="slider_auto_slide">
                <input type="checkbox" id="slider_auto_slide" name="rental_mobil_options[slider_auto_slide]" value="1" <?php checked($auto_slide, true); ?>>
                <?php _e('Auto Slide', 'rental-mobil-wp'); ?>
            </label>
            <span class="description"><?php _e('Slider akan bergerak otomatis.', 'rental-mobil-wp'); ?></span>
        </p>

        <p>
            <label for="slider_loop">
                <input type="checkbox" id="slider_loop" name="rental_mobil_options[slider_loop]" value="1" <?php checked($loop, true); ?>>
                <?php _e('Loop', 'rental-mobil-wp'); ?>
            </label>
            <span class="description"><?php _e('Slider akan berputar kembali ke awal setelah mencapai slide terakhir.', 'rental-mobil-wp'); ?></span>
        </p>

        <p>
            <label for="slider_speed"><?php _e('Kecepatan Transisi (ms)', 'rental-mobil-wp'); ?></label>
            <input type="number" id="slider_speed" name="rental_mobil_options[slider_speed]" value="<?php echo esc_attr($speed); ?>" min="100" step="100" class="small-text">
            <span class="description"><?php _e('Kecepatan transisi antar slide dalam milidetik.', 'rental-mobil-wp'); ?></span>
        </p>

        <p>
            <label for="slider_interval"><?php _e('Interval (ms)', 'rental-mobil-wp'); ?></label>
            <input type="number" id="slider_interval" name="rental_mobil_options[slider_interval]" value="<?php echo esc_attr($interval); ?>" min="1000" step="500" class="small-text">
            <span class="description"><?php _e('Interval waktu antar slide dalam milidetik.', 'rental-mobil-wp'); ?></span>
        </p>
    </div>
    <?php
}

/**
 * Get homepage vehicles
 */
function rental_mobil_get_homepage_vehicles() {
    $options = rental_mobil_get_options();
    $homepage_vehicles = isset($options['homepage_vehicles']) ? $options['homepage_vehicles'] : array();

    return $homepage_vehicles;
}

/**
 * Get homepage vehicle order settings
 */
function rental_mobil_get_homepage_order_settings() {
    $options = rental_mobil_get_options();
    return array(
        'orderby' => isset($options['homepage_orderby']) ? $options['homepage_orderby'] : 'date',
        'order' => isset($options['homepage_order']) ? $options['homepage_order'] : 'DESC',
    );
}

/**
 * Get shortcode vehicle order settings
 */
function rental_mobil_get_shortcode_order_settings() {
    $options = rental_mobil_get_options();
    return array(
        'orderby' => isset($options['shortcode_orderby']) ? $options['shortcode_orderby'] : 'date',
        'order' => isset($options['shortcode_order']) ? $options['shortcode_order'] : 'DESC',
    );
}

/**
 * Get frontend filter options
 */
function rental_mobil_get_frontend_filter_options() {
    $options = rental_mobil_get_options();
    $frontend_filter_options = isset($options['frontend_filter_options']) ? $options['frontend_filter_options'] : array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'orderby', 'order');

    if (!is_array($frontend_filter_options)) {
        $frontend_filter_options = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'orderby', 'order');
    }

    return $frontend_filter_options;
}

/**
 * Get filter options (alias for frontend filter options for backward compatibility)
 */
function rental_mobil_get_filter_options() {
    return rental_mobil_get_frontend_filter_options();
}

/**
 * Get admin filter options
 */
function rental_mobil_get_admin_filter_options() {
    $options = rental_mobil_get_options();
    $admin_filter_options = isset($options['admin_filter_options']) ? $options['admin_filter_options'] : array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'featured', 'popular');

    if (!is_array($admin_filter_options)) {
        $admin_filter_options = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'featured', 'popular');
    }

    return $admin_filter_options;
}

/**
 * Get share platforms
 */
function rental_mobil_get_share_platforms() {
    $options = rental_mobil_get_options();
    $share_platforms = isset($options['share_platforms']) ? $options['share_platforms'] : array('whatsapp', 'facebook', 'twitter', 'telegram', 'email');

    if (!is_array($share_platforms)) {
        $share_platforms = array('whatsapp', 'facebook', 'twitter', 'telegram', 'email');
    }

    return $share_platforms;
}

/**
 * Get slider settings
 */
function rental_mobil_get_slider_settings() {
    $options = rental_mobil_get_options();
    return array(
        'auto_slide' => isset($options['slider_auto_slide']) ? (bool) $options['slider_auto_slide'] : true,
        'loop' => isset($options['slider_loop']) ? (bool) $options['slider_loop'] : true,
        'speed' => isset($options['slider_speed']) ? absint($options['slider_speed']) : 300,
        'interval' => isset($options['slider_interval']) ? absint($options['slider_interval']) : 5000,
    );
}

/**
 * Filter section callback
 */
function rental_mobil_filter_section_callback() {
    echo '<p>' . __('Atur pengaturan filter untuk frontend dan admin.', 'rental-mobil-wp') . '</p>';
}

/**
 * Frontend filter options callback
 */
function rental_mobil_frontend_filter_options_callback() {
    $options = rental_mobil_get_options();
    $frontend_filter_options = isset($options['frontend_filter_options']) ? $options['frontend_filter_options'] : array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'orderby', 'order');

    if (!is_array($frontend_filter_options)) {
        $frontend_filter_options = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'orderby', 'order');
    }
    ?>
    <fieldset>
        <legend class="screen-reader-text"><?php _e('Filter untuk Frontend', 'rental-mobil-wp'); ?></legend>

        <label for="frontend_filter_merk">
            <input type="checkbox" id="frontend_filter_merk" name="rental_mobil_options[frontend_filter_options][]" value="merk" <?php checked(in_array('merk', $frontend_filter_options)); ?>>
            <?php _e('Merk Kendaraan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="frontend_filter_transmisi">
            <input type="checkbox" id="frontend_filter_transmisi" name="rental_mobil_options[frontend_filter_options][]" value="transmisi" <?php checked(in_array('transmisi', $frontend_filter_options)); ?>>
            <?php _e('Transmisi', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="frontend_filter_bahan_bakar">
            <input type="checkbox" id="frontend_filter_bahan_bakar" name="rental_mobil_options[frontend_filter_options][]" value="bahan_bakar" <?php checked(in_array('bahan_bakar', $frontend_filter_options)); ?>>
            <?php _e('Bahan Bakar', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="frontend_filter_tipe">
            <input type="checkbox" id="frontend_filter_tipe" name="rental_mobil_options[frontend_filter_options][]" value="tipe" <?php checked(in_array('tipe', $frontend_filter_options)); ?>>
            <?php _e('Tipe Kendaraan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="frontend_filter_tahun">
            <input type="checkbox" id="frontend_filter_tahun" name="rental_mobil_options[frontend_filter_options][]" value="tahun" <?php checked(in_array('tahun', $frontend_filter_options)); ?>>
            <?php _e('Tahun Kendaraan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="frontend_filter_orderby">
            <input type="checkbox" id="frontend_filter_orderby" name="rental_mobil_options[frontend_filter_options][]" value="orderby" <?php checked(in_array('orderby', $frontend_filter_options)); ?>>
            <?php _e('Urutan Berdasarkan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="frontend_filter_order">
            <input type="checkbox" id="frontend_filter_order" name="rental_mobil_options[frontend_filter_options][]" value="order" <?php checked(in_array('order', $frontend_filter_options)); ?>>
            <?php _e('Arah Urutan', 'rental-mobil-wp'); ?>
        </label>
    </fieldset>
    <p class="description"><?php _e('Pilih opsi filter yang ingin ditampilkan pada halaman daftar kendaraan untuk pengunjung.', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Admin filter options callback
 */
function rental_mobil_admin_filter_options_callback() {
    $options = rental_mobil_get_options();
    $admin_filter_options = isset($options['admin_filter_options']) ? $options['admin_filter_options'] : array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'featured', 'popular');

    if (!is_array($admin_filter_options)) {
        $admin_filter_options = array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun', 'featured', 'popular');
    }
    ?>
    <fieldset>
        <legend class="screen-reader-text"><?php _e('Filter untuk Admin', 'rental-mobil-wp'); ?></legend>

        <label for="admin_filter_merk">
            <input type="checkbox" id="admin_filter_merk" name="rental_mobil_options[admin_filter_options][]" value="merk" <?php checked(in_array('merk', $admin_filter_options)); ?>>
            <?php _e('Merk Kendaraan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="admin_filter_transmisi">
            <input type="checkbox" id="admin_filter_transmisi" name="rental_mobil_options[admin_filter_options][]" value="transmisi" <?php checked(in_array('transmisi', $admin_filter_options)); ?>>
            <?php _e('Transmisi', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="admin_filter_bahan_bakar">
            <input type="checkbox" id="admin_filter_bahan_bakar" name="rental_mobil_options[admin_filter_options][]" value="bahan_bakar" <?php checked(in_array('bahan_bakar', $admin_filter_options)); ?>>
            <?php _e('Bahan Bakar', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="admin_filter_tipe">
            <input type="checkbox" id="admin_filter_tipe" name="rental_mobil_options[admin_filter_options][]" value="tipe" <?php checked(in_array('tipe', $admin_filter_options)); ?>>
            <?php _e('Tipe Kendaraan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="admin_filter_tahun">
            <input type="checkbox" id="admin_filter_tahun" name="rental_mobil_options[admin_filter_options][]" value="tahun" <?php checked(in_array('tahun', $admin_filter_options)); ?>>
            <?php _e('Tahun Kendaraan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="admin_filter_featured">
            <input type="checkbox" id="admin_filter_featured" name="rental_mobil_options[admin_filter_options][]" value="featured" <?php checked(in_array('featured', $admin_filter_options)); ?>>
            <?php _e('Kendaraan Unggulan', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="admin_filter_popular">
            <input type="checkbox" id="admin_filter_popular" name="rental_mobil_options[admin_filter_options][]" value="popular" <?php checked(in_array('popular', $admin_filter_options)); ?>>
            <?php _e('Kendaraan Populer', 'rental-mobil-wp'); ?>
        </label>
    </fieldset>
    <p class="description"><?php _e('Pilih opsi filter yang ingin ditampilkan pada halaman admin kendaraan.', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Share section callback
 */
function rental_mobil_share_section_callback() {
    echo '<p>' . __('Atur pengaturan share untuk detail kendaraan.', 'rental-mobil-wp') . '</p>';
}

/**
 * Share platforms callback
 */
function rental_mobil_share_platforms_callback() {
    $options = rental_mobil_get_options();
    $share_platforms = isset($options['share_platforms']) ? $options['share_platforms'] : array('whatsapp', 'facebook', 'twitter', 'telegram', 'email');

    if (!is_array($share_platforms)) {
        $share_platforms = array('whatsapp', 'facebook', 'twitter', 'telegram', 'email');
    }
    ?>
    <fieldset>
        <legend class="screen-reader-text"><?php _e('Platform Share', 'rental-mobil-wp'); ?></legend>

        <label for="share_whatsapp">
            <input type="checkbox" id="share_whatsapp" name="rental_mobil_options[share_platforms][]" value="whatsapp" <?php checked(in_array('whatsapp', $share_platforms)); ?>>
            <?php _e('WhatsApp', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="share_facebook">
            <input type="checkbox" id="share_facebook" name="rental_mobil_options[share_platforms][]" value="facebook" <?php checked(in_array('facebook', $share_platforms)); ?>>
            <?php _e('Facebook', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="share_twitter">
            <input type="checkbox" id="share_twitter" name="rental_mobil_options[share_platforms][]" value="twitter" <?php checked(in_array('twitter', $share_platforms)); ?>>
            <?php _e('Twitter', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="share_telegram">
            <input type="checkbox" id="share_telegram" name="rental_mobil_options[share_platforms][]" value="telegram" <?php checked(in_array('telegram', $share_platforms)); ?>>
            <?php _e('Telegram', 'rental-mobil-wp'); ?>
        </label>
        <br>

        <label for="share_email">
            <input type="checkbox" id="share_email" name="rental_mobil_options[share_platforms][]" value="email" <?php checked(in_array('email', $share_platforms)); ?>>
            <?php _e('Email', 'rental-mobil-wp'); ?>
        </label>
    </fieldset>
    <p class="description"><?php _e('Pilih platform share yang ingin ditampilkan pada detail kendaraan.', 'rental-mobil-wp'); ?></p>
    <p class="description"><?php _e('URL yang dishare akan menggunakan slug halaman saat ini dan parameter kata_kunci untuk mengarahkan ke kendaraan yang spesifik.', 'rental-mobil-wp'); ?></p>
    <?php
}

/**
 * Homepage vehicle order callback
 */
function rental_mobil_homepage_vehicle_order_callback() {
    $options = rental_mobil_get_options();
    $default_orderby = isset($options['homepage_orderby']) ? $options['homepage_orderby'] : 'date';
    $default_order = isset($options['homepage_order']) ? $options['homepage_order'] : 'DESC';
    ?>
    <div class="rental-mobil-homepage-order-settings">
        <p>
            <label for="homepage_orderby"><?php _e('Urutkan Berdasarkan', 'rental-mobil-wp'); ?></label>
            <select id="homepage_orderby" name="rental_mobil_options[homepage_orderby]">
                <option value="date" <?php selected($default_orderby, 'date'); ?>><?php _e('Tanggal', 'rental-mobil-wp'); ?></option>
                <option value="title" <?php selected($default_orderby, 'title'); ?>><?php _e('Judul', 'rental-mobil-wp'); ?></option>
                <option value="meta_value_num" <?php selected($default_orderby, 'meta_value_num'); ?>><?php _e('Harga', 'rental-mobil-wp'); ?></option>
                <option value="harga_harian" <?php selected($default_orderby, 'harga_harian'); ?>><?php _e('Harga Harian', 'rental-mobil-wp'); ?></option>
                <option value="rand" <?php selected($default_orderby, 'rand'); ?>><?php _e('Acak', 'rental-mobil-wp'); ?></option>
            </select>
            <span class="description"><?php _e('Pilih cara mengurutkan kendaraan di homepage.', 'rental-mobil-wp'); ?></span>
        </p>

        <p>
            <label for="homepage_order"><?php _e('Urutan', 'rental-mobil-wp'); ?></label>
            <select id="homepage_order" name="rental_mobil_options[homepage_order]">
                <option value="ASC" <?php selected($default_order, 'ASC'); ?>><?php _e('Naik (A-Z, Lama-Baru, Murah-Mahal)', 'rental-mobil-wp'); ?></option>
                <option value="DESC" <?php selected($default_order, 'DESC'); ?>><?php _e('Turun (Z-A, Baru-Lama, Mahal-Murah)', 'rental-mobil-wp'); ?></option>
            </select>
            <span class="description"><?php _e('Pilih arah pengurutan kendaraan.', 'rental-mobil-wp'); ?></span>
        </p>

        <p class="description"><?php _e('Pengaturan ini akan diterapkan pada shortcode [kendaraan_pilihan] dan [kendaraan_unggulan] di homepage.', 'rental-mobil-wp'); ?></p>
    </div>
    <?php
}

/**
 * Shortcode vehicle order callback
 */
function rental_mobil_shortcode_vehicle_order_callback() {
    $options = rental_mobil_get_options();
    $default_orderby = isset($options['shortcode_orderby']) ? $options['shortcode_orderby'] : 'date';
    $default_order = isset($options['shortcode_order']) ? $options['shortcode_order'] : 'DESC';
    ?>
    <div class="rental-mobil-shortcode-order-settings">
        <p>
            <label for="shortcode_orderby"><?php _e('Urutkan Berdasarkan', 'rental-mobil-wp'); ?></label>
            <select id="shortcode_orderby" name="rental_mobil_options[shortcode_orderby]">
                <option value="date" <?php selected($default_orderby, 'date'); ?>><?php _e('Tanggal', 'rental-mobil-wp'); ?></option>
                <option value="title" <?php selected($default_orderby, 'title'); ?>><?php _e('Judul', 'rental-mobil-wp'); ?></option>
                <option value="meta_value_num" <?php selected($default_orderby, 'meta_value_num'); ?>><?php _e('Harga', 'rental-mobil-wp'); ?></option>
                <option value="price_high" <?php selected($default_orderby, 'price_high'); ?>><?php _e('Harga Tertinggi', 'rental-mobil-wp'); ?></option>
                <option value="price_low" <?php selected($default_orderby, 'price_low'); ?>><?php _e('Harga Terendah', 'rental-mobil-wp'); ?></option>
                <option value="rand" <?php selected($default_orderby, 'rand'); ?>><?php _e('Acak', 'rental-mobil-wp'); ?></option>
            </select>
            <span class="description"><?php _e('Pilih cara mengurutkan kendaraan di shortcode [daftar_kendaraan].', 'rental-mobil-wp'); ?></span>
        </p>

        <p>
            <label for="shortcode_order"><?php _e('Urutan', 'rental-mobil-wp'); ?></label>
            <select id="shortcode_order" name="rental_mobil_options[shortcode_order]">
                <option value="ASC" <?php selected($default_order, 'ASC'); ?>><?php _e('Naik (A-Z, Lama-Baru, Murah-Mahal)', 'rental-mobil-wp'); ?></option>
                <option value="DESC" <?php selected($default_order, 'DESC'); ?>><?php _e('Turun (Z-A, Baru-Lama, Mahal-Murah)', 'rental-mobil-wp'); ?></option>
            </select>
            <span class="description"><?php _e('Pilih arah pengurutan kendaraan.', 'rental-mobil-wp'); ?></span>
        </p>

        <p class="description"><?php _e('Pengaturan ini akan diterapkan pada shortcode [daftar_kendaraan] sebagai nilai default.', 'rental-mobil-wp'); ?></p>
        <p class="description"><?php _e('Contoh penggunaan: [daftar_kendaraan orderby="price_high" order="DESC"]', 'rental-mobil-wp'); ?></p>
    </div>
    <?php
}

/**
 * Get license key (selalu kosong)
 */
function rental_mobil_get_license_key() {
    return '';
}

/**
 * Get license status (selalu valid)
 */
function rental_mobil_get_license_status() {
    return 'valid';
}

/**
 * Get license expires (selalu 1 tahun dari sekarang)
 */
function rental_mobil_get_license_expires() {
    return date('Y-m-d', strtotime('+1 year'));
}

/**
 * Get license customer name (selalu kosong)
 */
function rental_mobil_get_license_customer() {
    return 'Admin';
}

/**
 * Get license created date (selalu hari ini)
 */
function rental_mobil_get_license_created_at() {
    return date('Y-m-d');
}

/**
 * Get license domain count (selalu 1)
 */
function rental_mobil_get_license_domain_count() {
    return 1;
}

/**
 * Get license max domains (selalu 1)
 */
function rental_mobil_get_license_max_domains() {
    return 1;
}

/**
 * Get form fields
 */
function rental_mobil_get_form_fields() {
    $options = rental_mobil_get_options();

    // Jika form_fields tidak ada atau kosong, gunakan default fields
    if (!isset($options['form_fields']) || empty($options['form_fields'])) {
        $default_fields = array(
            array(
                'id' => 'nama',
                'label' => 'Nama',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Masukkan nama Anda',
                'order' => 1
            ),
            array(
                'id' => 'domisili',
                'label' => 'Domisili',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Masukkan domisili Anda',
                'order' => 2
            ),
            array(
                'id' => 'tanggal_sewa',
                'label' => 'Tanggal Sewa',
                'type' => 'date',
                'required' => true,
                'placeholder' => '',
                'order' => 3
            ),
            array(
                'id' => 'jam_sewa',
                'label' => 'Jam Sewa',
                'type' => 'time',
                'required' => true,
                'placeholder' => '',
                'order' => 4
            ),
            array(
                'id' => 'durasi_sewa',
                'label' => 'Durasi Sewa',
                'type' => 'number',
                'required' => true,
                'placeholder' => 'Masukkan durasi sewa',
                'order' => 5
            ),
            array(
                'id' => 'satuan_durasi',
                'label' => 'Satuan Durasi',
                'type' => 'select',
                'required' => true,
                'placeholder' => '',
                'options' => array(
                    'hari' => 'Hari',
                    'minggu' => 'Minggu',
                    'bulan' => 'Bulan'
                ),
                'order' => 6
            )
        );

        // Simpan default fields ke database
        $options['form_fields'] = $default_fields;
        update_option('rental_mobil_options', $options, 'yes');

        return $default_fields;
    }

    return $options['form_fields'];
}

/**
 * Form Builder section callback - dipindahkan ke includes/form-builder.php
 * untuk menghindari deklarasi fungsi ganda
 */

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
        // Dapatkan opsi yang sudah ada
        $existing_options = get_option('rental_mobil_options', array());

        // Validasi dan sanitasi data
        $validated_options = rental_mobil_validate_options($form_data['rental_mobil_options']);

        // Gabungkan dengan opsi yang sudah ada untuk memastikan tidak ada yang hilang
        $merged_options = array_merge($existing_options, $validated_options);

        // Simpan opsi dengan autoload=yes untuk memastikan selalu tersedia
        update_option('rental_mobil_options', $merged_options, 'yes');

        // Refresh opsi dari database untuk memastikan konsistensi
        wp_cache_delete('rental_mobil_options', 'options');

        wp_send_json_success(array(
            'message' => __('Pengaturan berhasil disimpan.', 'rental-mobil-wp'),
            'options' => $merged_options
        ));
    } else {
        wp_send_json_error(array('message' => __('Tidak ada data yang disimpan.', 'rental-mobil-wp')));
    }
}

/**
 * Get custom CSS based on settings
 */
function rental_mobil_get_custom_css() {
    $style_settings = rental_mobil_get_style_settings();
    $filter_icon_position = rental_mobil_get_filter_icon_position();

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

    // Set posisi ikon filter
    $filter_position = 'bottom: 20px; right: 20px;';
    if ($filter_icon_position === 'bottom-left') {
        $filter_position = 'bottom: 20px; left: 20px;';
    } elseif ($filter_icon_position === 'middle-right') {
        $filter_position = 'top: 50%; right: 20px; transform: translateY(-50%);';
    } elseif ($filter_icon_position === 'middle-left') {
        $filter_position = 'top: 50%; left: 20px; transform: translateY(-50%);';
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
        position: fixed;
        {$filter_position}
        z-index: 999;
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

    /* Perbaikan untuk modal agar tidak tertutup menu sticky */
    .rental-mobil-modal-content {
        margin-top: 100px;
    }

    @media (max-width: 768px) {
        .rental-mobil-modal-content {
            margin-top: 70px;
        }
    }
    ";

    return $css;
}

/**
 * Add custom CSS to frontend
 */
add_action('wp_enqueue_scripts', 'rental_mobil_wp_add_custom_css', 20);
function rental_mobil_wp_add_custom_css() {
    $custom_css = rental_mobil_get_custom_css();
    wp_add_inline_style('rental-mobil-style', $custom_css);
}

/**
 * Fungsi untuk menampilkan tombol donasi (dinonaktifkan)
 *
 * @param string $type Tipe tombol (tidak digunakan)
 * @return string String kosong
 */
function rental_mobil_trakteer_button($type = 'overlay') {
    // Parameter $type tidak digunakan, tetapi dipertahankan untuk kompatibilitas
    return '';
}


