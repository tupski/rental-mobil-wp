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
        __('Pengaturan Urutan Kendaraan', 'rental-mobil-wp'),
        'rental_mobil_homepage_vehicle_order_callback',
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
    $license_status = rental_mobil_get_license_status();
    $status_text = '';
    $status_class = '';
    $domain = parse_url(home_url(), PHP_URL_HOST);

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
    echo '<div class="rental-mobil-version"><strong>' . __('Versi Plugin:', 'rental-mobil-wp') . '</strong> ' . esc_html($version) . ' <span class="rental-mobil-license-badge-doc rental-mobil-license-' . esc_attr($status_class) . '-doc">' . esc_html($status_text) . '</span></div>';

    // Tampilkan tombol beli lisensi jika status tidak aktif
    if ($license_status !== 'valid') {
        $whatsapp_message = 'Halo, saya ingin membeli lisensi plugin Rental Mobil WP dengan detail berikut:' . "\n\n";
        $whatsapp_message .= 'Nama: ' . "\n";
        $whatsapp_message .= 'No HP/WhatsApp: ' . "\n";
        $whatsapp_message .= 'Email Aktif: ' . "\n";
        $whatsapp_message .= 'Nama Domain: ' . $domain . "\n";
        $whatsapp_message .= 'Durasi: ' . "\n\n";
        $whatsapp_message .= 'Harga paket: 65ribu per bulan, atau 500rb per tahun.';

        $whatsapp_url = 'https://wa.me/6282211219993?text=' . urlencode($whatsapp_message);

        echo '<div class="rental-mobil-buy-license">';
        echo '<a href="' . esc_url($whatsapp_url) . '" class="button button-primary" target="_blank">' . __('Beli Lisensi', 'rental-mobil-wp') . '</a>';
        echo '</div>';
    }

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

    // Log untuk debugging
    error_log('License Key Callback - Key: ' . $license_key . ', Status: ' . $license_status);

    if ($license_status === 'valid') {
        // Jika lisensi valid, tampilkan hanya 4 karakter terakhir
        $masked_key = '';
        if (strlen($license_key) > 4) {
            $masked_key = str_repeat('*', strlen($license_key) - 4) . substr($license_key, -4);
        } else {
            $masked_key = $license_key;
        }
        ?>
        <input type="text" id="license_key" value="<?php echo esc_attr($masked_key); ?>" class="regular-text" disabled>
        <input type="hidden" id="license_key_hidden" name="rental_mobil_options[license_key]" value="<?php echo esc_attr($license_key); ?>">
        <div class="rental-mobil-license-buttons">
            <?php
            echo '<button type="button" id="rental-mobil-deactivate-license" class="button button-secondary">' . __('Nonaktifkan Lisensi', 'rental-mobil-wp') . '</button>';
            ?>
        </div>
        <?php
    } else {
        // Jika lisensi tidak valid, tampilkan field untuk input lisensi
        ?>
        <input type="text" id="license_key" value="<?php echo esc_attr($license_key); ?>" class="regular-text" placeholder="RM-WP-XXXX-XXXX-XXXX-XXXX">
        <input type="hidden" id="license_key_hidden" name="rental_mobil_options[license_key]" value="<?php echo esc_attr($license_key); ?>">
        <p class="description"><?php _e('Masukkan kunci lisensi yang Anda dapatkan saat membeli plugin.', 'rental-mobil-wp'); ?></p>
        <div class="rental-mobil-license-buttons">
            <?php
            echo '<button type="button" id="rental-mobil-activate-license" class="button button-primary">' . __('Aktivasi Lisensi', 'rental-mobil-wp') . '</button>';
            ?>
        </div>
        <script>
            // Update hidden input saat nilai input berubah
            jQuery(document).ready(function($) {
                $('#license_key').on('input', function() {
                    $('#license_key_hidden').val($(this).val());
                });
            });
        </script>
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
        // Tambahkan style untuk border
        echo '<style>
            .rental-mobil-license-details {
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 15px;
                margin-top: 15px;
                background-color: #f9f9f9;
            }
            .rental-mobil-license-details h3 {
                margin-top: 0;
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
            }
            .rental-mobil-license-badge {
                background-color: #46b450;
                color: white;
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 12px;
                font-weight: normal;
            }
            .rental-mobil-license-expired {
                color: #dc3232;
                font-weight: bold;
            }
            .rental-mobil-extend-button {
                margin-top: 15px;
            }
            .rental-mobil-extend-button .button {
                background-color: #46b450;
                border-color: #46b450;
                color: white;
            }
            .rental-mobil-extend-button .button:hover {
                background-color: #389e42;
                border-color: #389e42;
            }
        </style>';

        // Tampilkan detail lisensi jika aktif
        echo '<div class="rental-mobil-license-details">';
        echo '<h3>' . __('Detail Lisensi', 'rental-mobil-wp') . '</h3>';

        // Domain terdaftar
        $domain = parse_url(home_url(), PHP_URL_HOST);
        echo '<p><strong>' . __('Domain Terdaftar:', 'rental-mobil-wp') . '</strong> ' . esc_html($domain) . '</p>';

        // Jumlah domain
        $domain_count = isset($options['license_domain_count']) ? intval($options['license_domain_count']) : 0;
        $max_domains = isset($options['license_max_domains']) ? intval($options['license_max_domains']) : 1;
        echo '<p><strong>' . __('Lisensi untuk:', 'rental-mobil-wp') . '</strong> ' . esc_html($max_domains) . ' ' . __('Domain', 'rental-mobil-wp') . ' (' . __('digunakan', 'rental-mobil-wp') . ' ' . esc_html($domain_count) . '/' . esc_html($max_domains) . ' ' . __('Domain', 'rental-mobil-wp') . ')</p>';

        // Status dengan badge
        echo '<p><strong>' . __('Status:', 'rental-mobil-wp') . '</strong> <span class="rental-mobil-license-badge">' . __('Aktif', 'rental-mobil-wp') . '</span></p>';

        // Nama pelanggan
        $customer_name = isset($options['license_customer']) ? $options['license_customer'] : '';
        if (!empty($customer_name)) {
            echo '<p><strong>' . __('Lisensi untuk:', 'rental-mobil-wp') . '</strong> ' . esc_html($customer_name) . '</p>';
        }

        // Tanggal kedaluwarsa
        $license_expires = rental_mobil_get_license_expires();
        if (!empty($license_expires)) {
            // Format tanggal dalam bahasa Indonesia
            $expires_date = new DateTime($license_expires);
            $months_id = array(
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            );
            $formatted_date = $expires_date->format('j') . ' ' . $months_id[$expires_date->format('n') - 1] . ' ' . $expires_date->format('Y');

            echo '<p><strong>' . __('Tanggal Kedaluwarsa:', 'rental-mobil-wp') . '</strong> ' . esc_html($formatted_date) . '</p>';

            // Hitung sisa waktu
            $today = new DateTime();
            $expires = new DateTime($license_expires);
            $interval = $today->diff($expires);

            if ($expires > $today) {
                $remaining_text = '';

                if ($interval->y > 0) {
                    $remaining_text .= $interval->y . ' ' . __('tahun', 'rental-mobil-wp') . ' ';
                }

                if ($interval->m > 0) {
                    $remaining_text .= $interval->m . ' ' . __('bulan', 'rental-mobil-wp') . ' ';
                }

                if ($interval->d > 0 || ($interval->y == 0 && $interval->m == 0)) {
                    $remaining_text .= $interval->d . ' ' . __('hari', 'rental-mobil-wp');
                }

                echo '<p><strong>' . __('Berakhir dalam:', 'rental-mobil-wp') . '</strong> ' . trim($remaining_text) . '</p>';
            } else {
                echo '<p><strong>' . __('Berakhir dalam:', 'rental-mobil-wp') . '</strong> <span class="rental-mobil-license-expired">' . __('Lisensi telah kedaluwarsa', 'rental-mobil-wp') . '</span></p>';
            }
        }

        // Tombol perpanjang lisensi
        $whatsapp_message = 'Halo, saya ingin memperpanjang lisensi plugin Rental Mobil WP dengan detail berikut:' . "\n\n";
        $whatsapp_message .= 'Domain: ' . $domain . "\n";
        $whatsapp_message .= 'Nama Pelanggan: ' . $customer_name . "\n";
        $whatsapp_message .= 'Tanggal Kedaluwarsa: ' . $license_expires . "\n\n";
        $whatsapp_message .= 'Mohon informasi untuk perpanjangan lisensi. Terima kasih.';

        $whatsapp_url = 'https://wa.me/6282211219993?text=' . urlencode($whatsapp_message);

        echo '<div class="rental-mobil-extend-button">';
        echo '<a href="' . esc_url($whatsapp_url) . '" id="rental-mobil-extend-license" class="button button-primary">' . __('Perpanjang Lisensi', 'rental-mobil-wp') . '</a>';
        echo '</div>';

        echo '</div>';
    } else {
        // Tampilkan pesan jika lisensi tidak aktif
        echo '<div class="rental-mobil-license-message">';
        echo '<p>' . __('Butuh lisensi untuk plugin Rental Mobil WP?', 'rental-mobil-wp') . '</p>';
        echo '<p>' . sprintf(
            __('Hubungi WhatsApp <a href="%s" target="_blank">0822-1121-9993</a>', 'rental-mobil-wp'),
            'https://wa.me/6282211219993?text=Halo,%20saya%20ingin%20membeli%20lisensi%20plugin%20Rental%20Mobil%20WP%20untuk%20domain%20' . urlencode($domain) . '%0A%0AHarga%20paket:%2065ribu%20per%20bulan,%20atau%20500rb%20per%20tahun.'
        ) . '</p>';
        echo '<p>' . __('Harga paket: 65ribu per bulan, atau 500rb per tahun.', 'rental-mobil-wp') . '</p>';
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
 * Get license key
 */
function rental_mobil_get_license_key() {
    // Pembersihan cache yang agresif
    wp_cache_delete('rental_mobil_options', 'options');
    wp_cache_delete('alloptions', 'options');
    wp_cache_flush();

    // Dapatkan opsi langsung dari database dengan force refresh
    global $wpdb;
    $option_name = 'rental_mobil_options';

    // Dapatkan nilai opsi langsung dari database
    $option_value = $wpdb->get_var($wpdb->prepare(
        "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
        $option_name
    ));

    if (empty($option_value)) {
        if (defined('RENTAL_MOBIL_LICENSE_DEBUG') && RENTAL_MOBIL_LICENSE_DEBUG) {
            error_log('Rental Mobil - Get License Key: Opsi rental_mobil_options tidak ditemukan di database');
        }
        return '';
    }

    // Unserialize nilai opsi
    $options = maybe_unserialize($option_value);

    if (!is_array($options)) {
        if (defined('RENTAL_MOBIL_LICENSE_DEBUG') && RENTAL_MOBIL_LICENSE_DEBUG) {
            error_log('Rental Mobil - Get License Key: Opsi rental_mobil_options bukan array yang valid');
        }
        return '';
    }

    // Log untuk debugging
    if (defined('RENTAL_MOBIL_LICENSE_DEBUG') && RENTAL_MOBIL_LICENSE_DEBUG) {
        error_log('Rental Mobil - Get License Key: ' . (isset($options['license_key']) ? substr($options['license_key'], 0, 4) . '...' . substr($options['license_key'], -4) : 'empty'));
    }

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
 * Get form fields
 */
function rental_mobil_get_form_fields() {
    $options = rental_mobil_get_options();
    return isset($options['form_fields']) ? $options['form_fields'] : array();
}

/**
 * Form Builder section callback
 */
function rental_mobil_form_builder_section_callback() {
    echo '<p>' . __('Gunakan Form Builder untuk membuat dan mengelola field pada form booking kendaraan.', 'rental-mobil-wp') . '</p>';

    // Tampilkan UI Form Builder
    rental_mobil_form_builder_ui();
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
