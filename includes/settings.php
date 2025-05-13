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

    // Tab Lisensi
    add_settings_section(
        'rental_mobil_license_section',
        __('Pengaturan Lisensi', 'rental-mobil-wp'),
        'rental_mobil_license_section_callback',
        'rental_mobil_license'
    );

    add_settings_field(
        'license_key',
        __('Kunci Lisensi', 'rental-mobil-wp'),
        'rental_mobil_license_key_callback',
        'rental_mobil_license',
        'rental_mobil_license_section'
    );

    add_settings_field(
        'license_status',
        __('Status Lisensi', 'rental-mobil-wp'),
        'rental_mobil_license_status_callback',
        'rental_mobil_license',
        'rental_mobil_license_section'
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
 * Documentation section callback
 */
function rental_mobil_documentation_section_callback() {
    $plugin_data = get_plugin_data(RENTAL_MOBIL_PLUGIN_FILE);
    $version = $plugin_data['Version'];

    echo '<div class="rental-mobil-documentation">';
    echo '<div class="rental-mobil-version"><strong>' . __('Versi Plugin:', 'rental-mobil-wp') . '</strong> ' . esc_html($version) . '</div>';

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
    echo '<pre>[kendaraan_unggulan jumlah="5" judul="Kendaraan Unggulan"]</pre>';
    echo '<p><strong>' . __('Parameter:', 'rental-mobil-wp') . '</strong></p>';
    echo '<ul>';
    echo '<li><code>jumlah</code> - ' . __('Jumlah kendaraan yang ditampilkan (default: 5)', 'rental-mobil-wp') . '</li>';
    echo '<li><code>judul</code> - ' . __('Judul slider (default: Kendaraan Unggulan)', 'rental-mobil-wp') . '</li>';
    echo '</ul>';
    echo '</div>';

    echo '</div>';

    echo '<h3>' . __('Kontribusi', 'rental-mobil-wp') . '</h3>';
    echo '<p>' . sprintf(__('Plugin ini open source dan Anda dapat berkontribusi di %s', 'rental-mobil-wp'), '<a href="https://github.com/tupski/rental-mobil-wp" target="_blank">GitHub</a>') . '</p>';

    echo '</div>';
}

/**
 * License section callback
 */
function rental_mobil_license_section_callback() {
    echo '<p>' . __('Masukkan kunci lisensi Anda untuk mengaktifkan semua fitur plugin. Lisensi hanya berlaku untuk domain yang terdaftar.', 'rental-mobil-wp') . '</p>';
}

/**
 * License key field callback
 */
function rental_mobil_license_key_callback() {
    $options = rental_mobil_get_options();
    $license_key = isset($options['license_key']) ? $options['license_key'] : '';
    $license_status = rental_mobil_get_license_status();

    if ($license_status === 'valid') {
        // Jika lisensi valid, tampilkan hanya 4 karakter terakhir
        $masked_key = '';
        if (strlen($license_key) > 4) {
            $masked_key = str_repeat('*', strlen($license_key) - 4) . substr($license_key, -4);
        } else {
            $masked_key = $license_key;
        }
        ?>
        <input type="text" id="license_key" name="rental_mobil_options[license_key]" value="<?php echo esc_attr($masked_key); ?>" class="regular-text" disabled>
        <input type="hidden" name="rental_mobil_options[license_key]" value="<?php echo esc_attr($license_key); ?>">
        <div class="rental-mobil-license-buttons">
            <?php
            echo '<button type="button" id="rental-mobil-deactivate-license" class="button button-secondary">' . __('Nonaktifkan Lisensi', 'rental-mobil-wp') . '</button>';
            echo '<button type="button" id="rental-mobil-check-license" class="button button-secondary">' . __('Periksa Status Lisensi', 'rental-mobil-wp') . '</button>';
            ?>
        </div>
        <?php
    } else {
        // Jika lisensi tidak valid, tampilkan field untuk input lisensi
        ?>
        <input type="text" id="license_key" name="rental_mobil_options[license_key]" value="<?php echo esc_attr($license_key); ?>" class="regular-text">
        <p class="description"><?php _e('Masukkan kunci lisensi yang Anda dapatkan saat membeli plugin.', 'rental-mobil-wp'); ?></p>
        <div class="rental-mobil-license-buttons">
            <?php
            echo '<button type="button" id="rental-mobil-activate-license" class="button button-secondary">' . __('Aktivasi Lisensi', 'rental-mobil-wp') . '</button>';
            if (!empty($license_key)) {
                echo '<button type="button" id="rental-mobil-check-license" class="button button-secondary">' . __('Periksa Status Lisensi', 'rental-mobil-wp') . '</button>';
            }
            ?>
        </div>
        <?php
    }
}

/**
 * License status field callback
 */
function rental_mobil_license_status_callback() {
    $license_status = rental_mobil_get_license_status();
    $status_text = '';
    $status_class = '';
    $options = rental_mobil_get_options();

    if (empty($license_status)) {
        $status_text = __('Tidak Aktif', 'rental-mobil-wp');
        $status_class = 'rental-mobil-license-inactive';
    } elseif ($license_status === 'valid') {
        $status_text = __('Aktif', 'rental-mobil-wp');
        $status_class = 'rental-mobil-license-active';
    } elseif ($license_status === 'invalid') {
        $status_text = __('Lisensi Salah', 'rental-mobil-wp');
        $status_class = 'rental-mobil-license-invalid';
    } elseif ($license_status === 'expired') {
        $status_text = __('Kadaluarsa', 'rental-mobil-wp');
        $status_class = 'rental-mobil-license-expired';
    }

    echo '<div class="rental-mobil-license-status ' . esc_attr($status_class) . '">' . esc_html($status_text) . '</div>';

    if ($license_status === 'valid') {
        // Tampilkan detail lisensi jika aktif
        echo '<div class="rental-mobil-license-details">';
        echo '<h3>' . __('Detail Lisensi', 'rental-mobil-wp') . '</h3>';

        // Nama pelanggan
        $customer_name = isset($options['license_customer']) ? $options['license_customer'] : '';
        if (!empty($customer_name)) {
            echo '<p><strong>' . __('Nama Pelanggan:', 'rental-mobil-wp') . '</strong> ' . esc_html($customer_name) . '</p>';
        }

        // Domain terdaftar
        $domain = parse_url(home_url(), PHP_URL_HOST);
        echo '<p><strong>' . __('Domain Terdaftar:', 'rental-mobil-wp') . '</strong> ' . esc_html($domain) . '</p>';

        // Status dengan badge
        echo '<p><strong>' . __('Status:', 'rental-mobil-wp') . '</strong> <span class="rental-mobil-license-badge">' . __('Aktif', 'rental-mobil-wp') . '</span></p>';

        // Tanggal lisensi dibuat (jika tersedia)
        if (isset($options['license_created_at']) && !empty($options['license_created_at'])) {
            echo '<p><strong>' . __('Tanggal Lisensi Dibuat:', 'rental-mobil-wp') . '</strong> ' . esc_html($options['license_created_at']) . '</p>';
        }

        // Tanggal kedaluwarsa
        $license_expires = rental_mobil_get_license_expires();
        if (!empty($license_expires)) {
            echo '<p><strong>' . __('Tanggal Kedaluwarsa:', 'rental-mobil-wp') . '</strong> ' . esc_html($license_expires) . '</p>';

            // Hitung sisa hari
            $today = new DateTime();
            $expires = new DateTime($license_expires);
            $interval = $today->diff($expires);
            $days_remaining = $interval->days;

            if ($expires > $today) {
                echo '<p><strong>' . __('Sisa Hari:', 'rental-mobil-wp') . '</strong> ' . $days_remaining . ' ' . __('hari', 'rental-mobil-wp') . '</p>';
            } else {
                echo '<p><strong>' . __('Sisa Hari:', 'rental-mobil-wp') . '</strong> <span class="rental-mobil-license-expired">0 ' . __('hari (kedaluwarsa)', 'rental-mobil-wp') . '</span></p>';
            }
        }

        echo '</div>';
    } else {
        // Tampilkan pesan jika lisensi tidak aktif
        echo '<div class="rental-mobil-license-message">';
        echo '<p>' . __('Butuh lisensi untuk plugin Rental Mobil WP?', 'rental-mobil-wp') . '</p>';
        echo '<p>' . sprintf(
            __('Hubungi WhatsApp <a href="%s" target="_blank">0822-1121-9993</a> atau <a href="%s" target="_blank">0819-1191-9993</a>', 'rental-mobil-wp'),
            'https://wa.me/6282211219993?text=Halo,%20saya%20ingin%20membeli%20lisensi%20plugin%20Rental%20Mobil%20WP%20untuk%20domain%20' . urlencode($domain),
            'https://wa.me/6281911919993?text=Halo,%20saya%20ingin%20membeli%20lisensi%20plugin%20Rental%20Mobil%20WP%20untuk%20domain%20' . urlencode($domain)
        ) . '</p>';
        echo '</div>';
    }
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
    <input type="range" id="button_border_radius" name="rental_mobil_options[button_border_radius]" min="0" max="50" value="<?php echo esc_attr($button_border_radius); ?>" oninput="this.nextElementSibling.value = this.value + 'px'">
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
    <input type="range" id="card_border_radius" name="rental_mobil_options[card_border_radius]" min="0" max="50" value="<?php echo esc_attr($card_border_radius); ?>" oninput="this.nextElementSibling.value = this.value + 'px'">
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
 * Homepage vehicles field callback
 */
function rental_mobil_homepage_vehicles_callback() {
    $options = rental_mobil_get_options();
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

        <div class="rental-mobil-admin-header">
            <div class="rental-mobil-admin-title">
                <h2><?php _e('Plugin oleh Angga Artupas', 'rental-mobil-wp'); ?></h2>
            </div>
        </div>

        <h2 class="nav-tab-wrapper">
            <a href="?page=rental-mobil&tab=documentation" class="nav-tab <?php echo $active_tab == 'documentation' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-book"></span> <?php _e('Dokumentasi', 'rental-mobil-wp'); ?>
            </a>
            <a href="?page=rental-mobil&tab=license" class="nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-lock"></span> <?php _e('Lisensi', 'rental-mobil-wp'); ?>
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
                if ($active_tab == 'documentation') {
                    echo '<div id="rental-mobil-documentation-settings" class="rental-mobil-settings-tab">';
                    do_settings_sections('rental_mobil_documentation');
                    echo '</div>';
                } elseif ($active_tab == 'license') {
                    echo '<div id="rental-mobil-license-settings" class="rental-mobil-settings-tab">';
                    do_settings_sections('rental_mobil_license');
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
            'license_max_domains' => ''
        );
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
 * Get filter options
 */
function rental_mobil_get_filter_options() {
    $options = rental_mobil_get_options();
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
 * Get homepage vehicles
 */
function rental_mobil_get_homepage_vehicles() {
    $options = rental_mobil_get_options();
    $homepage_vehicles = isset($options['homepage_vehicles']) ? $options['homepage_vehicles'] : array();

    return $homepage_vehicles;
}

/**
 * Get license key
 */
function rental_mobil_get_license_key() {
    $options = rental_mobil_get_options();
    return isset($options['license_key']) ? $options['license_key'] : '';
}

/**
 * Get license status
 */
function rental_mobil_get_license_status() {
    // Hapus cache opsi untuk memastikan data terbaru
    wp_cache_delete('rental_mobil_options', 'options');
    wp_cache_delete('alloptions', 'options');

    // Dapatkan opsi langsung dari database dengan force refresh
    $options = get_option('rental_mobil_options', array(), false);

    // Log status lisensi untuk debugging
    if (defined('RENTAL_MOBIL_LICENSE_DEBUG') && RENTAL_MOBIL_LICENSE_DEBUG) {
        error_log('Rental Mobil - Get License Status: ' . (isset($options['license_status']) ? $options['license_status'] : 'empty'));
    }

    return isset($options['license_status']) ? $options['license_status'] : '';
}

/**
 * Get license expires
 */
function rental_mobil_get_license_expires() {
    $options = rental_mobil_get_options();
    return isset($options['license_expires']) ? $options['license_expires'] : '';
}

/**
 * Get license customer name
 */
function rental_mobil_get_license_customer() {
    $options = rental_mobil_get_options();
    return isset($options['license_customer']) ? $options['license_customer'] : '';
}

/**
 * Get license created date
 */
function rental_mobil_get_license_created_at() {
    $options = rental_mobil_get_options();
    return isset($options['license_created_at']) ? $options['license_created_at'] : '';
}

/**
 * Get license domain count
 */
function rental_mobil_get_license_domain_count() {
    $options = rental_mobil_get_options();
    return isset($options['license_domain_count']) ? $options['license_domain_count'] : 0;
}

/**
 * Get license max domains
 */
function rental_mobil_get_license_max_domains() {
    $options = rental_mobil_get_options();
    return isset($options['license_max_domains']) ? $options['license_max_domains'] : 0;
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

/**
 * Fungsi untuk menampilkan tombol donasi Trakteer
 */
function rental_mobil_trakteer_button($type = 'overlay') {
    ob_start();
    if ($type === 'overlay') {
        ?>
        <div class="rental-mobil-trakteer-button">
            <script type='text/javascript' src='https://edge-cdn.trakteer.id/js/trbtn-overlay.min.js?v=24-01-2025'></script>
            <script type='text/javascript' class='troverlay'>
                (function() {
                    var trbtnId = trbtnOverlay.init('Dukung Saya di Trakteer','#000F9B','https://trakteer.id/tupski/tip/embed/modal','https://trakteer.id/images/mix/coffee.png','40','inline');
                    trbtnOverlay.draw(trbtnId);
                })();
            </script>
        </div>
        <?php
    } else {
        ?>
        <div class="rental-mobil-trakteer-button">
            <script type='text/javascript' src='https://edge-cdn.trakteer.id/js/embed/trbtn.min.js?v=24-01-2025'></script>
            <script type='text/javascript'>
                (function(){
                    var trbtnId=trbtn.init('Dukung Saya di Trakteer','#4075FF','https://trakteer.id/tupski','https://trakteer.id/images/mix/coffee.png','40');
                    trbtn.draw(trbtnId);
                })();
            </script>
        </div>
        <?php
    }
    return ob_get_clean();
}
