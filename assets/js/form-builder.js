/**
 * Form Builder JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
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
        const previewBtn = $('#rental-mobil-preview-form');
        const previewContainer = $('#rental-mobil-form-preview');

        let formFields = [];
        try {
            formFields = JSON.parse(formFieldsInput.val() || '[]');
        } catch (e) {
            formFields = [];
        }

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

            // Populate conditional field dropdown (exclude current field)
            updateConditionalFieldOptions();
        });

        // Show/hide conditional settings
        $('#rental-mobil-field-conditional-enabled').on('change', function() {
            if ($(this).is(':checked')) {
                $('.rental-mobil-field-conditional-container').show();
            } else {
                $('.rental-mobil-field-conditional-container').hide();
            }
        });

        // Function to update conditional field options
        function updateConditionalFieldOptions() {
            const currentFieldId = $('#rental-mobil-field-id').val();
            const conditionalFieldSelect = $('#rental-mobil-field-conditional-field');
            const conditionalValueContainer = $('#rental-mobil-conditional-value-container');

            // Clear current options
            conditionalFieldSelect.find('option:not(:first)').remove();

            // Add options from existing fields
            formFields.forEach(field => {
                // Skip current field and only include select fields
                if (field.id !== currentFieldId) {
                    conditionalFieldSelect.append(`<option value="${field.id}" data-type="${field.type}">${field.label}</option>`);
                }
            });

            // Handle conditional field change
            conditionalFieldSelect.off('change').on('change', function() {
                const selectedFieldId = $(this).val();
                const selectedFieldType = $(this).find('option:selected').data('type');

                // Reset conditional value container
                conditionalValueContainer.empty();

                if (selectedFieldId) {
                    const selectedField = formFields.find(f => f.id === selectedFieldId);

                    // If selected field is a select field, show dropdown with its options
                    if (selectedField && selectedField.type === 'select' && selectedField.options) {
                        const select = $('<select id="rental-mobil-field-conditional-value"></select>');

                        // Add empty option
                        select.append('<option value="">-- Pilih Nilai --</option>');

                        // Add options from the selected field
                        $.each(selectedField.options, function(value, label) {
                            select.append(`<option value="${value}">${label}</option>`);
                        });

                        conditionalValueContainer.append(select);
                    } else {
                        // For other field types, show text input
                        conditionalValueContainer.append('<input type="text" id="rental-mobil-field-conditional-value" placeholder="Nilai">');
                    }
                } else {
                    // If no field selected, show default text input
                    conditionalValueContainer.append('<input type="text" id="rental-mobil-field-conditional-value" placeholder="Nilai">');
                }
            });
        }

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
                $('#rental-mobil-field-width').val(field.width || '100');

                // Set conditional fields
                if (field.conditional) {
                    $('#rental-mobil-field-conditional-enabled').prop('checked', true);
                    $('.rental-mobil-field-conditional-container').show();

                    // Set conditional field
                    $('#rental-mobil-field-conditional-field').val(field.conditional.field).trigger('change');

                    // Set conditional operator
                    $('#rental-mobil-field-conditional-operator').val(field.conditional.operator);

                    // Wait for conditional value container to be updated
                    setTimeout(function() {
                        // Set conditional value
                        const conditionalValueInput = $('#rental-mobil-field-conditional-value');
                        if (conditionalValueInput.is('select')) {
                            conditionalValueInput.val(field.conditional.value);
                        } else {
                            conditionalValueInput.val(field.conditional.value);
                        }
                    }, 100);
                } else {
                    $('#rental-mobil-field-conditional-enabled').prop('checked', false);
                    $('.rental-mobil-field-conditional-container').hide();
                    $('#rental-mobil-field-conditional-field').val('');
                    $('#rental-mobil-field-conditional-operator').val('equal');

                    // Reset conditional value container
                    $('#rental-mobil-conditional-value-container').empty().append('<input type="text" id="rental-mobil-field-conditional-value" placeholder="Nilai">');
                }

                // Update conditional field options
                updateConditionalFieldOptions();

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
            const fieldWidth = $('#rental-mobil-field-width').val() || '100';
            const conditionalEnabled = $('#rental-mobil-field-conditional-enabled').is(':checked');

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
                width: fieldWidth,
                order: fieldOrder
            };

            // Add conditional logic if enabled
            if (conditionalEnabled) {
                const conditionalField = $('#rental-mobil-field-conditional-field').val();
                const conditionalOperator = $('#rental-mobil-field-conditional-operator').val();
                const conditionalValue = $('#rental-mobil-field-conditional-value').val();

                if (conditionalField && conditionalValue) {
                    field.conditional = {
                        field: conditionalField,
                        operator: conditionalOperator,
                        value: conditionalValue
                    };
                }
            }

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

        // Preview button click
        if (previewBtn.length) {
            previewBtn.on('click', function() {
                renderFormPreview();
                previewContainer.toggle();
            });
        }

        // Initialize custom time picker
        $(document).on('focus', '.rental-mobil-time-picker', function() {
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

        // Render form preview
        function renderFormPreview() {
            previewContainer.empty();

            previewContainer.append('<h3 class="rental-mobil-form-preview-title">Preview Form</h3>');

            const form = $('<form class="rental-mobil-form-preview-form"></form>');

            formFields.forEach(field => {
                // Tambahkan class untuk ukuran field
                const fieldWidth = field.width || '100';
                const fieldContainer = $(`<div class="rental-mobil-form-preview-field rental-mobil-field-width-${fieldWidth}" id="preview-field-${field.id}"></div>`);

                // Tambahkan atribut data untuk conditional logic
                if (field.conditional) {
                    fieldContainer.attr('data-conditional-field', field.conditional.field);
                    fieldContainer.attr('data-conditional-operator', field.conditional.operator);
                    fieldContainer.attr('data-conditional-value', field.conditional.value);
                    fieldContainer.addClass('rental-mobil-conditional-field');

                    // Sembunyikan field conditional secara default
                    fieldContainer.hide();
                }

                const label = $(`<label for="preview_${field.id}">${field.label}${field.required ? ' <span class="required">*</span>' : ''}</label>`);

                fieldContainer.append(label);

                switch (field.type) {
                    case 'text':
                    case 'email':
                    case 'tel':
                    case 'number':
                        fieldContainer.append(`<input type="${field.type}" id="preview_${field.id}" name="${field.id}" placeholder="${field.placeholder || ''}" ${field.required ? 'required' : ''}>`);
                        break;
                    case 'date':
                        fieldContainer.append(`<input type="date" id="preview_${field.id}" name="${field.id}" ${field.required ? 'required' : ''}>`);
                        break;
                    case 'time':
                        fieldContainer.append(`<input type="text" id="preview_${field.id}" name="${field.id}" class="rental-mobil-time-picker" placeholder="HH:MM" ${field.required ? 'required' : ''}>`);
                        break;
                    case 'select':
                        const select = $(`<select id="preview_${field.id}" name="${field.id}" ${field.required ? 'required' : ''}></select>`);

                        select.append('<option value="">-- Pilih --</option>');

                        if (field.options) {
                            $.each(field.options, function(value, label) {
                                select.append(`<option value="${value}">${label}</option>`);
                            });
                        }

                        fieldContainer.append(select);
                        break;
                    case 'textarea':
                        fieldContainer.append(`<textarea id="preview_${field.id}" name="${field.id}" placeholder="${field.placeholder || ''}" ${field.required ? 'required' : ''}></textarea>`);
                        break;
                }

                form.append(fieldContainer);
            });

            const submitContainer = $('<div class="rental-mobil-form-preview-submit"></div>');
            submitContainer.append('<button type="button" class="button button-primary">Submit</button>');

            form.append(submitContainer);
            previewContainer.append(form);

            // Inisialisasi conditional logic
            initConditionalLogic();

            // Initialize time picker for preview
            previewContainer.find('.rental-mobil-time-picker').on('focus', function() {
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
        }

        // Fungsi untuk menginisialisasi conditional logic
        function initConditionalLogic() {
            // Tambahkan event listener untuk semua input dan select
            $('.rental-mobil-form-preview-field input, .rental-mobil-form-preview-field select').on('change', function() {
                const fieldId = $(this).attr('name');
                const fieldValue = $(this).val();

                // Cek semua field conditional
                $('.rental-mobil-conditional-field').each(function() {
                    const conditionalField = $(this).data('conditional-field');
                    const conditionalOperator = $(this).data('conditional-operator');
                    const conditionalValue = $(this).data('conditional-value');

                    // Jika field ini tergantung pada field yang berubah
                    if (conditionalField === fieldId) {
                        let shouldShow = false;

                        // Evaluasi kondisi
                        if (conditionalOperator === 'equal') {
                            shouldShow = fieldValue === conditionalValue;
                        } else if (conditionalOperator === 'not_equal') {
                            shouldShow = fieldValue !== conditionalValue;
                        }

                        // Tampilkan atau sembunyikan field
                        if (shouldShow) {
                            $(this).show();
                        } else {
                            $(this).hide();
                            // Reset nilai field
                            $(this).find('input, select').val('');
                        }
                    }
                });
            });

            // Trigger change event pada semua field untuk menginisialisasi kondisi
            $('.rental-mobil-form-preview-field input, .rental-mobil-form-preview-field select').first().trigger('change');
        }
    });
})(jQuery);
