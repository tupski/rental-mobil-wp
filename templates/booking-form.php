<?php
/**
 * Template untuk Form Booking
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

// Dapatkan form fields dari pengaturan
$form_fields = rental_mobil_get_form_fields();

// Jika form fields kosong, gunakan default fields
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
?>

<form id="rental-mobil-booking-form" class="rental-mobil-booking-form">
    <input type="hidden" id="rental-mobil-booking-kendaraan-id" name="kendaraan_id" value="<?php echo esc_attr($kendaraan_id); ?>">
    <input type="hidden" id="rental-mobil-booking-kendaraan-title" name="kendaraan_title" value="<?php echo esc_attr(get_the_title($kendaraan_id)); ?>">

    <?php foreach ($form_fields as $field) :
        // Tentukan ukuran field, default 100%
        $field_width = isset($field['width']) ? $field['width'] : '100';

        // Tentukan atribut conditional
        $conditional_attrs = '';
        $conditional_class = '';
        $conditional_style = '';

        if (isset($field['conditional']) && !empty($field['conditional'])) {
            $conditional_attrs = 'data-conditional-field="' . esc_attr($field['conditional']['field']) . '" ';
            $conditional_attrs .= 'data-conditional-operator="' . esc_attr($field['conditional']['operator']) . '" ';
            $conditional_attrs .= 'data-conditional-value="' . esc_attr($field['conditional']['value']) . '"';
            $conditional_class = 'rental-mobil-conditional-field';
            $conditional_style = 'style="display:none;"';
        }
    ?>
        <div class="rental-mobil-form-group rental-mobil-field-width-<?php echo esc_attr($field_width); ?> <?php echo esc_attr($conditional_class); ?>" <?php echo $conditional_attrs; ?> <?php echo $conditional_style; ?>>
            <label for="rental-mobil-booking-<?php echo esc_attr($field['id']); ?>">
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
                        id="rental-mobil-booking-<?php echo esc_attr($field['id']); ?>"
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
                        id="rental-mobil-booking-<?php echo esc_attr($field['id']); ?>"
                        name="<?php echo esc_attr($field['id']); ?>"
                        <?php echo $field['required'] ? 'required' : ''; ?>
                    >
                    <?php
                    break;
                case 'time':
                    ?>
                    <input
                        type="text"
                        id="rental-mobil-booking-<?php echo esc_attr($field['id']); ?>"
                        name="<?php echo esc_attr($field['id']); ?>"
                        class="rental-mobil-time-picker"
                        placeholder="HH:MM"
                        <?php echo $field['required'] ? 'required' : ''; ?>
                    >
                    <?php
                    break;
                case 'select':
                    ?>
                    <select
                        id="rental-mobil-booking-<?php echo esc_attr($field['id']); ?>"
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
                        id="rental-mobil-booking-<?php echo esc_attr($field['id']); ?>"
                        name="<?php echo esc_attr($field['id']); ?>"
                        placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                        <?php echo $field['required'] ? 'required' : ''; ?>
                    ></textarea>
                    <?php
                    break;
            } ?>
        </div>
    <?php endforeach; ?>

    <div class="rental-mobil-form-actions">
        <button type="submit" class="rental-mobil-button rental-mobil-button-submit">
            <?php _e('Booking via WhatsApp', 'rental-mobil-wp'); ?>
        </button>
    </div>
</form>
