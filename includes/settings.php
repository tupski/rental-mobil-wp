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
