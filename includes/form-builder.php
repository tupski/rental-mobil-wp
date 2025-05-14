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
    <a href="?page=rental-mobil&tab=form_builder" class="nav-tab <?php echo $active_tab == 'form_builder' ? 'nav-tab-active' : ''; ?>">
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
    ?>
    <div class="rental-mobil-form-builder-container">
        <input type="hidden" id="rental-mobil-form-fields" name="rental_mobil_options[form_fields]" value="<?php echo esc_attr(json_encode($form_fields)); ?>">
        
        <div class="rental-mobil-form-builder-header">
            <button type="button" class="button button-primary" id="rental-mobil-add-field"><?php _e('Tambah Field', 'rental-mobil-wp'); ?></button>
        </div>
        
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
                    <label>
                        <input type="checkbox" id="rental-mobil-field-required">
                        <?php _e('Wajib diisi', 'rental-mobil-wp'); ?>
                    </label>
                </div>
                
                <div class="rental-mobil-field-form-actions">
                    <button type="button" class="button button-primary" id="rental-mobil-save-field"><?php _e('Simpan', 'rental-mobil-wp'); ?></button>
                    <button type="button" class="button" id="rental-mobil-cancel-field"><?php _e('Batal', 'rental-mobil-wp'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .rental-mobil-form-builder-container {
            margin-top: 20px;
        }
        .rental-mobil-form-builder-header {
            margin-bottom: 20px;
        }
        .rental-mobil-form-builder-fields {
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        .rental-mobil-form-builder-fields-header {
            display: flex;
            background-color: #f9f9f9;
            padding: 10px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }
        .rental-mobil-form-builder-field {
            display: flex;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            background-color: #fff;
        }
        .rental-mobil-form-builder-field:last-child {
            border-bottom: none;
        }
        .rental-mobil-form-builder-field-drag {
            width: 30px;
            cursor: move;
        }
        .rental-mobil-form-builder-field-label {
            flex: 2;
        }
        .rental-mobil-form-builder-field-type {
            flex: 1;
        }
        .rental-mobil-form-builder-field-required {
            width: 50px;
            text-align: center;
        }
        .rental-mobil-form-builder-field-actions {
            width: 100px;
            text-align: right;
        }
        .rental-mobil-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .rental-mobil-modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .rental-mobil-modal-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .rental-mobil-field-form-group {
            margin-bottom: 15px;
        }
        .rental-mobil-field-form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .rental-mobil-field-form-actions {
            margin-top: 20px;
            text-align: right;
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Variables
        const formFieldsInput = $('#rental-mobil-form-fields');
        const fieldsList = $('#rental-mobil-form-builder-fields-list');
        const modal = $('#rental-mobil-field-modal');
        const modalTitle = $('#rental-mobil-field-modal-title');
        const modalClose = $('.rental-mobil-modal-close');
        const addFieldBtn = $('#rental-mobil-add-field');
        const saveFieldBtn = $('#rental-mobil-save-field');
        const cancelFieldBtn = $('#rental-mobil-cancel-field');
        const fieldType = $('#rental-mobil-field-type');
        const fieldOptionsContainer = $('.rental-mobil-field-options');
        
        let formFields = JSON.parse(formFieldsInput.val());
        let editingFieldId = null;
        
        // Initialize sortable
        if ($.fn.sortable) {
            fieldsList.sortable({
                handle: '.rental-mobil-form-builder-field-drag',
                update: function(event, ui) {
                    updateFieldsOrder();
                }
            });
        }
        
        // Show/hide options based on field type
        fieldType.on('change', function() {
            if ($(this).val() === 'select') {
                fieldOptionsContainer.show();
            } else {
                fieldOptionsContainer.hide();
            }
        });
        
        // Add field button click
        addFieldBtn.on('click', function() {
            modalTitle.text('Tambah Field');
            editingFieldId = null;
            
            // Reset form
            $('#rental-mobil-field-id').val('');
            $('#rental-mobil-field-order').val(formFields.length + 1);
            $('#rental-mobil-field-label').val('');
            $('#rental-mobil-field-type').val('text');
            $('#rental-mobil-field-placeholder').val('');
            $('#rental-mobil-field-options-text').val('');
            $('#rental-mobil-field-required').prop('checked', true);
            
            fieldOptionsContainer.hide();
            
            // Show modal
            modal.css('display', 'block');
        });
        
        // Edit field button click
        $(document).on('click', '.rental-mobil-edit-field', function() {
            modalTitle.text('Edit Field');
            editingFieldId = $(this).data('id');
            
            // Find field data
            const field = formFields.find(f => f.id === editingFieldId);
            
            if (field) {
                $('#rental-mobil-field-id').val(field.id);
                $('#rental-mobil-field-order').val(field.order);
                $('#rental-mobil-field-label').val(field.label);
                $('#rental-mobil-field-type').val(field.type);
                $('#rental-mobil-field-placeholder').val(field.placeholder || '');
                $('#rental-mobil-field-required').prop('checked', field.required);
                
                // Handle options for select fields
                if (field.type === 'select' && field.options) {
                    let optionsText = '';
                    $.each(field.options, function(value, label) {
                        optionsText += value + '|' + label + '\n';
                    });
                    $('#rental-mobil-field-options-text').val(optionsText.trim());
                    fieldOptionsContainer.show();
                } else {
                    $('#rental-mobil-field-options-text').val('');
                    fieldOptionsContainer.hide();
                }
                
                // Show modal
                modal.css('display', 'block');
            }
        });
        
        // Delete field button click
        $(document).on('click', '.rental-mobil-delete-field', function() {
            if (confirm('Apakah Anda yakin ingin menghapus field ini?')) {
                const fieldId = $(this).data('id');
                
                // Remove field from array
                formFields = formFields.filter(f => f.id !== fieldId);
                
                // Update order
                updateFieldsOrder();
                
                // Update input value
                formFieldsInput.val(JSON.stringify(formFields));
                
                // Remove field from DOM
                $(this).closest('.rental-mobil-form-builder-field').remove();
            }
        });
        
        // Save field button click
        saveFieldBtn.on('click', function() {
            const fieldId = $('#rental-mobil-field-id').val() || generateFieldId();
            const fieldOrder = parseInt($('#rental-mobil-field-order').val()) || formFields.length + 1;
            const fieldLabel = $('#rental-mobil-field-label').val();
            const fieldType = $('#rental-mobil-field-type').val();
            const fieldPlaceholder = $('#rental-mobil-field-placeholder').val();
            const fieldRequired = $('#rental-mobil-field-required').is(':checked');
            
            // Validate
            if (!fieldLabel) {
                alert('Label field tidak boleh kosong');
                return;
            }
            
            // Create field object
            const field = {
                id: fieldId,
                label: fieldLabel,
                type: fieldType,
                required: fieldRequired,
                placeholder: fieldPlaceholder,
                order: fieldOrder
            };
            
            // Add options for select fields
            if (fieldType === 'select') {
                const optionsText = $('#rental-mobil-field-options-text').val();
                const options = {};
                
                if (optionsText) {
                    const optionLines = optionsText.split('\n');
                    optionLines.forEach(line => {
                        if (line.trim()) {
                            const parts = line.split('|');
                            if (parts.length === 2) {
                                options[parts[0].trim()] = parts[1].trim();
                            } else {
                                options[line.trim()] = line.trim();
                            }
                        }
                    });
                }
                
                field.options = options;
            }
            
            // Update or add field
            if (editingFieldId) {
                // Update existing field
                const index = formFields.findIndex(f => f.id === editingFieldId);
                if (index !== -1) {
                    formFields[index] = field;
                }
            } else {
                // Add new field
                formFields.push(field);
            }
            
            // Sort fields by order
            formFields.sort((a, b) => a.order - b.order);
            
            // Update input value
            formFieldsInput.val(JSON.stringify(formFields));
            
            // Refresh fields list
            refreshFieldsList();
            
            // Close modal
            modal.css('display', 'none');
        });
        
        // Cancel button click
        cancelFieldBtn.on('click', function() {
            modal.css('display', 'none');
        });
        
        // Close modal when clicking on X or outside
        modalClose.on('click', function() {
            modal.css('display', 'none');
        });
        
        $(window).on('click', function(event) {
            if (event.target === modal[0]) {
                modal.css('display', 'none');
            }
        });
        
        // Generate unique field ID
        function generateFieldId() {
            const label = $('#rental-mobil-field-label').val();
            let id = label.toLowerCase().replace(/[^a-z0-9]/g, '_');
            
            // Make sure ID is unique
            let counter = 1;
            let uniqueId = id;
            while (formFields.some(f => f.id === uniqueId)) {
                uniqueId = id + '_' + counter;
                counter++;
            }
            
            return uniqueId;
        }
        
        // Update fields order
        function updateFieldsOrder() {
            fieldsList.find('.rental-mobil-form-builder-field').each(function(index) {
                const fieldId = $(this).data('id');
                const field = formFields.find(f => f.id === fieldId);
                if (field) {
                    field.order = index + 1;
                }
            });
            
            // Sort fields by order
            formFields.sort((a, b) => a.order - b.order);
            
            // Update input value
            formFieldsInput.val(JSON.stringify(formFields));
        }
        
        // Refresh fields list
        function refreshFieldsList() {
            fieldsList.empty();
            
            formFields.forEach(field => {
                fieldsList.append(`
                    <div class="rental-mobil-form-builder-field" data-id="${field.id}">
                        <div class="rental-mobil-form-builder-field-drag">
                            <span class="dashicons dashicons-menu"></span>
                        </div>
                        <div class="rental-mobil-form-builder-field-label">
                            ${field.label}
                        </div>
                        <div class="rental-mobil-form-builder-field-type">
                            ${field.type.charAt(0).toUpperCase() + field.type.slice(1)}
                        </div>
                        <div class="rental-mobil-form-builder-field-required">
                            ${field.required ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>'}
                        </div>
                        <div class="rental-mobil-form-builder-field-actions">
                            <button type="button" class="button rental-mobil-edit-field" data-id="${field.id}">
                                <span class="dashicons dashicons-edit"></span>
                            </button>
                            <button type="button" class="button rental-mobil-delete-field" data-id="${field.id}">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>
                `);
            });
            
            // Reinitialize sortable
            if ($.fn.sortable) {
                fieldsList.sortable('refresh');
            }
        }
    });
    </script>
    <?php
}
