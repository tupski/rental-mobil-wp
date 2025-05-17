<?php
/**
 * Form Builder untuk Custom Booking Form
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Tambahkan tab Form Builder di halaman pengaturan
 */
add_action('rental_mobil_settings_tabs', 'rental_mobil_add_form_builder_tab');
function rental_mobil_add_form_builder_tab($active_tab) {
    ?>
    <a href="admin.php?page=rental-mobil-settings&tab=form_builder" class="nav-tab <?php echo $active_tab == 'form_builder' ? 'nav-tab-active' : ''; ?>">
        <span class="dashicons dashicons-feedback"></span> <?php _e('Form Builder', 'rental-mobil-wp'); ?>
    </a>
    <?php
}

/**
 * Tambahkan section Form Builder di halaman pengaturan
 */
add_action('rental_mobil_register_settings', 'rental_mobil_register_form_builder_settings');
function rental_mobil_register_form_builder_settings() {
    // Register section
    add_settings_section(
        'rental_mobil_form_builder_section',
        __('Form Builder', 'rental-mobil-wp'),
        'rental_mobil_form_builder_section_callback',
        'rental_mobil_form_builder'
    );

    // Register field untuk menyimpan form fields
    register_setting('rental_mobil_options', 'rental_mobil_options', 'rental_mobil_validate_options');
}

/**
 * Form Builder section callback
 */
function rental_mobil_form_builder_section_callback() {
    ?>
    <p><?php _e('Buat dan kustomisasi form booking sesuai kebutuhan Anda.', 'rental-mobil-wp'); ?></p>
    <?php
    rental_mobil_form_builder_ui();
}

/**
 * Form Builder UI
 */
function rental_mobil_form_builder_ui() {
    // Gunakan fungsi rental_mobil_get_form_fields() untuk mendapatkan form fields
    $form_fields = rental_mobil_get_form_fields();

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
    ?>
    <div class="rental-mobil-form-builder-container">
        <input type="hidden" id="rental-mobil-form-fields" name="rental_mobil_options[form_fields]" value="<?php echo esc_attr(json_encode($form_fields)); ?>">

        <div class="rental-mobil-form-builder-header">
            <button type="button" class="button button-primary" id="rental-mobil-add-field"><?php _e('Tambah Field', 'rental-mobil-wp'); ?></button>
            <button type="button" class="button" id="rental-mobil-preview-form"><?php _e('Preview Form', 'rental-mobil-wp'); ?></button>
            <button type="button" class="button button-primary" id="rental-mobil-save-form-fields" style="float: right;"><?php _e('Simpan Form Fields', 'rental-mobil-wp'); ?></button>
        </div>

        <div id="rental-mobil-form-preview" style="display: none;"></div>

        <div class="rental-mobil-form-builder-fields">
            <div class="rental-mobil-form-builder-fields-header">
                <div class="rental-mobil-form-builder-field-drag"></div>
                <div class="rental-mobil-form-builder-field-label"><?php _e('Label', 'rental-mobil-wp'); ?></div>
                <div class="rental-mobil-form-builder-field-type"><?php _e('Tipe', 'rental-mobil-wp'); ?></div>
                <div class="rental-mobil-form-builder-field-required"><?php _e('Wajib', 'rental-mobil-wp'); ?></div>
                <div class="rental-mobil-form-builder-field-actions"><?php _e('Aksi', 'rental-mobil-wp'); ?></div>
            </div>

            <div id="rental-mobil-form-builder-fields-list">
                <?php foreach ($form_fields as $field) : ?>
                <div class="rental-mobil-form-builder-field" data-id="<?php echo esc_attr($field['id']); ?>">
                    <div class="rental-mobil-form-builder-field-drag">
                        <span class="dashicons dashicons-menu"></span>
                    </div>
                    <div class="rental-mobil-form-builder-field-label">
                        <?php echo esc_html($field['label']); ?>
                    </div>
                    <div class="rental-mobil-form-builder-field-type">
                        <?php echo esc_html(ucfirst($field['type'])); ?>
                    </div>
                    <div class="rental-mobil-form-builder-field-required">
                        <?php echo $field['required'] ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>'; ?>
                    </div>
                    <div class="rental-mobil-form-builder-field-actions">
                        <button type="button" class="button rental-mobil-edit-field" data-id="<?php echo esc_attr($field['id']); ?>">
                            <span class="dashicons dashicons-edit"></span>
                        </button>
                        <button type="button" class="button rental-mobil-delete-field" data-id="<?php echo esc_attr($field['id']); ?>">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Modal untuk tambah/edit field -->
    <div id="rental-mobil-field-modal" class="rental-mobil-modal">
        <div class="rental-mobil-modal-content">
            <span class="rental-mobil-modal-close">&times;</span>
            <h2 id="rental-mobil-field-modal-title"><?php _e('Tambah Field', 'rental-mobil-wp'); ?></h2>

            <div class="rental-mobil-field-form">
                <input type="hidden" id="rental-mobil-field-id">
                <input type="hidden" id="rental-mobil-field-order">

                <div class="rental-mobil-field-form-group">
                    <label for="rental-mobil-field-label"><?php _e('Label', 'rental-mobil-wp'); ?></label>
                    <input type="text" id="rental-mobil-field-label" class="regular-text">
                </div>

                <div class="rental-mobil-field-form-group">
                    <label for="rental-mobil-field-type"><?php _e('Tipe', 'rental-mobil-wp'); ?></label>
                    <select id="rental-mobil-field-type">
                        <option value="text"><?php _e('Text', 'rental-mobil-wp'); ?></option>
                        <option value="email"><?php _e('Email', 'rental-mobil-wp'); ?></option>
                        <option value="number"><?php _e('Number', 'rental-mobil-wp'); ?></option>
                        <option value="tel"><?php _e('Phone', 'rental-mobil-wp'); ?></option>
                        <option value="date"><?php _e('Date', 'rental-mobil-wp'); ?></option>
                        <option value="time"><?php _e('Time', 'rental-mobil-wp'); ?></option>
                        <option value="select"><?php _e('Select', 'rental-mobil-wp'); ?></option>
                        <option value="textarea"><?php _e('Textarea', 'rental-mobil-wp'); ?></option>
                    </select>
                </div>

                <div class="rental-mobil-field-form-group">
                    <label for="rental-mobil-field-placeholder"><?php _e('Placeholder', 'rental-mobil-wp'); ?></label>
                    <input type="text" id="rental-mobil-field-placeholder" class="regular-text">
                </div>

                <div class="rental-mobil-field-form-group rental-mobil-field-options" style="display: none;">
                    <label for="rental-mobil-field-options"><?php _e('Options (satu per baris)', 'rental-mobil-wp'); ?></label>
                    <textarea id="rental-mobil-field-options-text" rows="5" class="regular-text"></textarea>
                    <p class="description"><?php _e('Format: value|label (contoh: hari|Hari)', 'rental-mobil-wp'); ?></p>
                </div>

                <div class="rental-mobil-field-form-group">
                    <label for="rental-mobil-field-width"><?php _e('Ukuran Field', 'rental-mobil-wp'); ?></label>
                    <select id="rental-mobil-field-width">
                        <option value="100"><?php _e('100% (Full Width)', 'rental-mobil-wp'); ?></option>
                        <option value="75"><?php _e('75% (3/4 Width)', 'rental-mobil-wp'); ?></option>
                        <option value="50"><?php _e('50% (Half Width)', 'rental-mobil-wp'); ?></option>
                        <option value="25"><?php _e('25% (Quarter Width)', 'rental-mobil-wp'); ?></option>
                    </select>
                    <p class="description"><?php _e('Ukuran field pada form booking', 'rental-mobil-wp'); ?></p>
                </div>

                <div class="rental-mobil-field-form-group rental-mobil-field-conditional-container" style="display: none;">
                    <label><?php _e('Kondisi Tampilan', 'rental-mobil-wp'); ?></label>
                    <div class="rental-mobil-field-conditional-settings">
                        <select id="rental-mobil-field-conditional-field">
                            <option value=""><?php _e('-- Pilih Field --', 'rental-mobil-wp'); ?></option>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                        <select id="rental-mobil-field-conditional-operator">
                            <option value="equal"><?php _e('Sama dengan', 'rental-mobil-wp'); ?></option>
                            <option value="not_equal"><?php _e('Tidak sama dengan', 'rental-mobil-wp'); ?></option>
                        </select>
                        <input type="text" id="rental-mobil-field-conditional-value" placeholder="<?php _e('Nilai', 'rental-mobil-wp'); ?>">
                    </div>
                    <p class="description"><?php _e('Field ini hanya akan ditampilkan jika kondisi terpenuhi', 'rental-mobil-wp'); ?></p>
                </div>

                <div class="rental-mobil-field-form-group">
                    <label>
                        <input type="checkbox" id="rental-mobil-field-required">
                        <?php _e('Wajib diisi', 'rental-mobil-wp'); ?>
                    </label>
                </div>

                <div class="rental-mobil-field-form-group">
                    <label>
                        <input type="checkbox" id="rental-mobil-field-conditional-enabled">
                        <?php _e('Aktifkan kondisi tampilan', 'rental-mobil-wp'); ?>
                    </label>
                </div>

                <div class="rental-mobil-field-form-actions">
                    <button type="button" class="button button-primary" id="rental-mobil-save-field"><?php _e('Simpan', 'rental-mobil-wp'); ?></button>
                    <button type="button" class="button" id="rental-mobil-cancel-field"><?php _e('Batal', 'rental-mobil-wp'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS untuk Form Builder dimuat dari assets/css/form-builder.css -->

    <!-- Form Builder UI dikelola oleh JavaScript di assets/js/form-builder.js -->
    <script>
    jQuery(document).ready(function($) {
        // Save Form Fields button click
        $('#rental-mobil-save-form-fields').on('click', function() {
            const formFields = $('#rental-mobil-form-fields').val();
            const saveButton = $(this);
            const originalText = saveButton.text();

            // Disable button and show loading
            saveButton.prop('disabled', true).text('<?php _e('Menyimpan...', 'rental-mobil-wp'); ?>');

            // Kirim data ke server
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'rental_mobil_save_form_fields',
                    nonce: '<?php echo wp_create_nonce('rental_mobil_form_builder_nonce'); ?>',
                    form_fields: formFields
                },
                success: function(response) {
                    if (response.success) {
                        // Tampilkan pesan sukses
                        const message = $('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
                        $('.rental-mobil-form-builder-container').before(message);

                        // Auto dismiss setelah 3 detik
                        setTimeout(function() {
                            message.fadeOut(function() {
                                $(this).remove();
                            });
                        }, 3000);
                    } else {
                        // Tampilkan pesan error
                        const message = $('<div class="notice notice-error is-dismissible"><p>' + response.data.message + '</p></div>');
                        $('.rental-mobil-form-builder-container').before(message);
                    }

                    // Re-enable button
                    saveButton.prop('disabled', false).text(originalText);
                },
                error: function() {
                    // Tampilkan pesan error
                    const message = $('<div class="notice notice-error is-dismissible"><p><?php _e('Terjadi kesalahan. Silakan coba lagi.', 'rental-mobil-wp'); ?></p></div>');
                    $('.rental-mobil-form-builder-container').before(message);

                    // Re-enable button
                    saveButton.prop('disabled', false).text(originalText);
                }
            });
        });
    });
    </script>
    <?php
}
