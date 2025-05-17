<?php
/**
 * Fungsi-fungsi pengganti untuk lisensi (mode gratis)
 * File ini menggantikan license.php untuk menghindari error dan membuka semua fitur
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Cek apakah lisensi valid - selalu mengembalikan true
 */
function rental_mobil_is_license_valid() {
    return true;
}

/**
 * Cek apakah fitur tertentu diaktifkan - selalu mengembalikan true
 */
function rental_mobil_is_feature_enabled($feature) {
    return true;
}

/**
 * Dapatkan kunci lisensi - mengembalikan string kosong
 */
function rental_mobil_get_license_key() {
    return '';
}

/**
 * Dapatkan status lisensi - mengembalikan 'valid'
 */
function rental_mobil_get_license_status() {
    return 'valid';
}

/**
 * Dapatkan opsi plugin
 */
function rental_mobil_get_options() {
    $default_options = array(
        'whatsapp_number' => '',
        'whatsapp_message' => 'Halo, saya ingin menyewa kendaraan {nama_kendaraan}. Mohon informasi lebih lanjut. Terima kasih.',
        'filter_options' => array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun'),
        'admin_filter_options' => array('merk', 'transmisi', 'bahan_bakar', 'tipe', 'tahun'),
        'homepage_vehicles' => array(),
        'featured_slider_settings' => array(
            'auto_slide' => true,
            'loop' => true,
            'speed' => 300,
            'interval' => 5000
        ),
        'border_radius' => array(
            'button' => '4',
            'card' => '8'
        ),
        'filter_icon_position' => 'left',
        'share_buttons' => array('whatsapp', 'facebook', 'twitter', 'telegram', 'email')
    );

    $options = get_option('rental_mobil_options', array());
    
    // Gabungkan dengan default options
    return wp_parse_args($options, $default_options);
}

/**
 * Dapatkan nomor WhatsApp dari pengaturan
 */
function rental_mobil_get_whatsapp_number() {
    $options = rental_mobil_get_options();
    $whatsapp_number = isset($options['whatsapp_number']) ? $options['whatsapp_number'] : '';
    
    // Bersihkan nomor WhatsApp
    $whatsapp_number = preg_replace('/[^0-9]/', '', $whatsapp_number);
    
    // Tambahkan kode negara jika diperlukan
    if (substr($whatsapp_number, 0, 1) === '0') {
        $whatsapp_number = '62' . substr($whatsapp_number, 1);
    }
    
    return $whatsapp_number;
}

/**
 * Dapatkan template pesan WhatsApp dari pengaturan
 */
function rental_mobil_get_whatsapp_message() {
    $options = rental_mobil_get_options();
    return isset($options['whatsapp_message']) ? $options['whatsapp_message'] : 'Halo, saya ingin menyewa kendaraan {nama_kendaraan}. Mohon informasi lebih lanjut. Terima kasih.';
}

/**
 * Dapatkan field form dari pengaturan
 */
function rental_mobil_get_form_fields() {
    $options = rental_mobil_get_options();
    $form_fields = isset($options['form_fields']) ? $options['form_fields'] : array();
    
    // Default fields jika belum ada
    if (empty($form_fields)) {
        $form_fields = array(
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
    }
    
    // Urutkan fields berdasarkan order
    usort($form_fields, function($a, $b) {
        return $a['order'] - $b['order'];
    });
    
    return $form_fields;
}
