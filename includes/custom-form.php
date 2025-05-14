<?php
/**
 * Custom Form Booking
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Render custom booking form
 */
function rental_mobil_render_custom_booking_form($kendaraan_id, $kendaraan_title) {
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

    ob_start();
    ?>
    <form class="rental-mobil-custom-booking-form" id="rental-mobil-custom-booking-form">
        <input type="hidden" id="rental-mobil-custom-booking-kendaraan-id" name="kendaraan_id" value="<?php echo esc_attr($kendaraan_id); ?>">
        <input type="hidden" id="rental-mobil-custom-booking-kendaraan-title" name="kendaraan_title" value="<?php echo esc_attr($kendaraan_title); ?>">
        
        <?php foreach ($form_fields as $field) : ?>
            <div class="rental-mobil-custom-booking-field">
                <label for="rental-mobil-custom-booking-<?php echo esc_attr($field['id']); ?>">
                    <?php echo esc_html($field['label']); ?>
                    <?php if ($field['required']) : ?>
                        <span class="required">*</span>
                    <?php endif; ?>
                </label>
                
                <?php switch ($field['type']) {
                    case 'text':
                    case 'email':
                    case 'tel':
                    case 'number':
                        ?>
                        <input 
                            type="<?php echo esc_attr($field['type']); ?>" 
                            id="rental-mobil-custom-booking-<?php echo esc_attr($field['id']); ?>" 
                            name="<?php echo esc_attr($field['id']); ?>" 
                            placeholder="<?php echo esc_attr($field['placeholder']); ?>" 
                            <?php echo $field['required'] ? 'required' : ''; ?>
                        >
                        <?php
                        break;
                    case 'date':
                        ?>
                        <input 
                            type="date" 
                            id="rental-mobil-custom-booking-<?php echo esc_attr($field['id']); ?>" 
                            name="<?php echo esc_attr($field['id']); ?>" 
                            <?php echo $field['required'] ? 'required' : ''; ?>
                        >
                        <?php
                        break;
                    case 'time':
                        ?>
                        <input 
                            type="text" 
                            id="rental-mobil-custom-booking-<?php echo esc_attr($field['id']); ?>" 
                            name="<?php echo esc_attr($field['id']); ?>" 
                            class="rental-mobil-time-picker" 
                            placeholder="HH:MM" 
                            <?php echo $field['required'] ? 'required' : ''; ?>
                            readonly
                        >
                        <?php
                        break;
                    case 'select':
                        ?>
                        <select 
                            id="rental-mobil-custom-booking-<?php echo esc_attr($field['id']); ?>" 
                            name="<?php echo esc_attr($field['id']); ?>" 
                            <?php echo $field['required'] ? 'required' : ''; ?>
                        >
                            <option value=""><?php _e('-- Pilih --', 'rental-mobil-wp'); ?></option>
                            <?php if (isset($field['options']) && is_array($field['options'])) : ?>
                                <?php foreach ($field['options'] as $value => $label) : ?>
                                    <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php
                        break;
                    case 'textarea':
                        ?>
                        <textarea 
                            id="rental-mobil-custom-booking-<?php echo esc_attr($field['id']); ?>" 
                            name="<?php echo esc_attr($field['id']); ?>" 
                            placeholder="<?php echo esc_attr($field['placeholder']); ?>" 
                            <?php echo $field['required'] ? 'required' : ''; ?>
                        ></textarea>
                        <?php
                        break;
                } ?>
            </div>
        <?php endforeach; ?>
        
        <div class="rental-mobil-custom-booking-submit">
            <button type="submit" class="rental-mobil-button"><?php _e('Booking Sekarang', 'rental-mobil-wp'); ?></button>
        </div>
    </form>

    <script>
    jQuery(document).ready(function($) {
        // Custom time picker
        $('.rental-mobil-time-picker').on('focus', function() {
            const input = $(this);
            let options = input.next('.rental-mobil-time-picker-options');
            
            if (options.length === 0) {
                // Create options container
                options = $('<div class="rental-mobil-time-picker-options"></div>');
                input.after(options);
                
                // Add time options (only 00 and 30 minutes)
                for (let hour = 0; hour < 24; hour++) {
                    const hourFormatted = hour.toString().padStart(2, '0');
                    
                    // Add option for XX:00
                    options.append(`<div class="rental-mobil-time-picker-option" data-value="${hourFormatted}:00">${hourFormatted}:00</div>`);
                    
                    // Add option for XX:30
                    options.append(`<div class="rental-mobil-time-picker-option" data-value="${hourFormatted}:30">${hourFormatted}:30</div>`);
                }
                
                // Handle option click
                options.on('click', '.rental-mobil-time-picker-option', function() {
                    const value = $(this).data('value');
                    input.val(value);
                    options.hide();
                });
            }
            
            // Position options
            const inputOffset = input.offset();
            const inputHeight = input.outerHeight();
            
            options.css({
                top: inputOffset.top + inputHeight + 'px',
                left: inputOffset.left + 'px',
                width: input.outerWidth() + 'px'
            }).show();
            
            // Hide options when clicking outside
            $(document).one('click', function(e) {
                if (!$(e.target).hasClass('rental-mobil-time-picker') && !$(e.target).hasClass('rental-mobil-time-picker-option')) {
                    options.hide();
                }
            });
        });

        // Form submission
        $('#rental-mobil-custom-booking-form').on('submit', function(e) {
            e.preventDefault();
            
            // Collect form data
            const formData = {};
            $(this).serializeArray().forEach(function(item) {
                formData[item.name] = item.value;
            });
            
            // Validate form
            let isValid = true;
            $(this).find('[required]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }
            });
            
            if (!isValid) {
                alert('Mohon lengkapi semua field yang wajib diisi.');
                return;
            }
            
            // Format tanggal
            if (formData.tanggal_sewa) {
                const tanggalObj = new Date(formData.tanggal_sewa);
                formData.tanggal_sewa_formatted = tanggalObj.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }
            
            // Kirim ke WhatsApp
            $.ajax({
                url: rental_mobil_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'rental_mobil_custom_booking',
                    nonce: rental_mobil_ajax.nonce,
                    form_data: formData
                },
                success: function(response) {
                    if (response.success) {
                        // Buka WhatsApp
                        const whatsappUrl = `https://wa.me/${response.data.whatsapp_number}?text=${encodeURIComponent(response.data.message)}`;
                        window.open(whatsappUrl, '_blank');
                        
                        // Reset form
                        $('#rental-mobil-custom-booking-form')[0].reset();
                        
                        // Tampilkan pesan sukses
                        alert('Booking berhasil dikirim. Anda akan diarahkan ke WhatsApp.');
                    } else {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
}

/**
 * AJAX handler for custom booking
 */
add_action('wp_ajax_rental_mobil_custom_booking', 'rental_mobil_custom_booking_ajax');
add_action('wp_ajax_nopriv_rental_mobil_custom_booking', 'rental_mobil_custom_booking_ajax');
function rental_mobil_custom_booking_ajax() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rental_mobil_ajax_nonce')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
        return;
    }
    
    // Get form data
    $form_data = isset($_POST['form_data']) ? $_POST['form_data'] : array();
    
    if (empty($form_data) || !isset($form_data['kendaraan_id'])) {
        wp_send_json_error(array('message' => 'Invalid form data'));
        return;
    }
    
    // Get kendaraan data
    $kendaraan_id = intval($form_data['kendaraan_id']);
    $kendaraan = get_post($kendaraan_id);
    
    if (!$kendaraan || $kendaraan->post_type !== 'kendaraan') {
        wp_send_json_error(array('message' => 'Invalid kendaraan'));
        return;
    }
    
    // Get WhatsApp number
    $options = rental_mobil_get_options();
    $whatsapp_number = isset($options['whatsapp_number']) ? $options['whatsapp_number'] : '';
    
    // Clean WhatsApp number
    $whatsapp_number = preg_replace('/[^0-9]/', '', $whatsapp_number);
    
    // Add country code if needed
    if (substr($whatsapp_number, 0, 1) === '0') {
        $whatsapp_number = '62' . substr($whatsapp_number, 1);
    }
    
    // Build message
    $message = "Halo, saya ingin booking kendaraan:\n\n";
    $message .= "Kendaraan: " . $kendaraan->post_title . "\n";
    
    // Add form fields to message
    $options = rental_mobil_get_options();
    $form_fields = isset($options['form_fields']) ? $options['form_fields'] : array();
    
    // Urutkan fields berdasarkan order
    usort($form_fields, function($a, $b) {
        return $a['order'] - $b['order'];
    });
    
    foreach ($form_fields as $field) {
        if (isset($form_data[$field['id']]) && $form_data[$field['id']] !== '') {
            // Format tanggal jika ada
            if ($field['id'] === 'tanggal_sewa' && isset($form_data['tanggal_sewa_formatted'])) {
                $message .= $field['label'] . ": " . $form_data['tanggal_sewa_formatted'] . "\n";
            } else {
                // Untuk select, tampilkan label bukan value
                if ($field['type'] === 'select' && isset($field['options'][$form_data[$field['id']]])) {
                    $message .= $field['label'] . ": " . $field['options'][$form_data[$field['id']]] . "\n";
                } else {
                    $message .= $field['label'] . ": " . $form_data[$field['id']] . "\n";
                }
            }
        }
    }
    
    $message .= "\nTerima kasih.";
    
    wp_send_json_success(array(
        'whatsapp_number' => $whatsapp_number,
        'message' => $message
    ));
}

/**
 * Replace default booking form with custom booking form
 */
add_filter('rental_mobil_booking_form', 'rental_mobil_use_custom_booking_form', 10, 2);
function rental_mobil_use_custom_booking_form($form, $kendaraan_id) {
    $kendaraan_title = get_the_title($kendaraan_id);
    return rental_mobil_render_custom_booking_form($kendaraan_id, $kendaraan_title);
}
