/**
 * JavaScript untuk menangani aktivasi dan deaktivasi lisensi
 *
 * Versi: 1.1.0
 * Terintegrasi dengan verifikasi.tupski.web.id
 */
jQuery(document).ready(function($) {
    // Aktivasi lisensi
    $(document).on('click', '#rental-mobil-activate-license', function(e) {
        e.preventDefault();

        var button = $(this);
        var originalText = button.text();
        var licenseKey = $('#license_key').val();

        // Log untuk debugging
        console.log('Mencoba aktivasi lisensi dengan kunci: ' + licenseKey);

        // Validasi kunci lisensi
        if (!licenseKey) {
            alert('Kunci lisensi tidak boleh kosong.');
            return;
        }

        // Disable button dan tampilkan loading
        button.prop('disabled', true).text(rental_mobil_license.activating);

        // Simpan kunci lisensi ke input tersembunyi untuk memastikan tersimpan
        if ($('#license_key_hidden').length === 0) {
            $('<input>').attr({
                type: 'hidden',
                id: 'license_key_hidden',
                name: 'rental_mobil_options[license_key]',
                value: licenseKey
            }).insertAfter('#license_key');
        } else {
            $('#license_key_hidden').val(licenseKey);
        }

        $.ajax({
            url: rental_mobil_license.ajax_url,
            type: 'POST',
            data: {
                action: 'rental_mobil_activate_license',
                nonce: rental_mobil_license.nonce,
                license_key: licenseKey
            },
            success: function(response) {
                console.log('Respons aktivasi lisensi:', response);

                if (response.success) {
                    // Tampilkan pesan sukses
                    var message = $('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
                    $('.rental-mobil-license-settings').prepend(message);

                    // Update status lisensi
                    $('.rental-mobil-license-status')
                        .removeClass('rental-mobil-license-inactive rental-mobil-license-invalid rental-mobil-license-expired')
                        .addClass('rental-mobil-license-active')
                        .text(rental_mobil_license.active_text);

                    // Tambahkan informasi tanggal kedaluwarsa jika ada
                    if (response.data.expires) {
                        var expiresText = $('<p class="description">' + rental_mobil_license.expires_text.replace('%s', '<strong>' + response.data.expires + '</strong>') + '</p>');
                        $('.rental-mobil-license-status').after(expiresText);
                    }

                    // Ganti tombol aktivasi dengan tombol deaktivasi
                    button.text(rental_mobil_license.deactivate)
                        .attr('id', 'rental-mobil-deactivate-license');

                    // Auto dismiss pesan setelah 3 detik
                    setTimeout(function() {
                        message.fadeOut(function() {
                            $(this).remove();
                        });

                        // Reload halaman untuk memperbarui tampilan
                        location.reload();
                    }, 3000);
                } else {
                    // Tampilkan pesan error
                    var message = $('<div class="notice notice-error is-dismissible"><p>' + response.data.message + '</p></div>');
                    $('.rental-mobil-license-settings').prepend(message);

                    // Update status lisensi
                    $('.rental-mobil-license-status')
                        .removeClass('rental-mobil-license-active rental-mobil-license-inactive rental-mobil-license-expired')
                        .addClass('rental-mobil-license-invalid')
                        .text(rental_mobil_license.invalid_text);

                    // Re-enable tombol
                    button.prop('disabled', false).text(originalText);

                    // Auto dismiss pesan setelah 3 detik
                    setTimeout(function() {
                        message.fadeOut(function() {
                            $(this).remove();
                        });

                        // Reload halaman untuk memperbarui tampilan jika respons berisi reload=true
                        if (response.data && response.data.reload) {
                            location.reload();
                        }
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                // Log error untuk debugging
                console.error('Error aktivasi lisensi:', status, error);
                console.log(xhr.responseText);

                // Tampilkan pesan error
                var message = $('<div class="notice notice-error is-dismissible"><p>' + rental_mobil_license.error + '</p></div>');
                $('.rental-mobil-license-settings').prepend(message);

                // Re-enable tombol
                button.prop('disabled', false).text(originalText);

                // Auto dismiss pesan setelah 3 detik
                setTimeout(function() {
                    message.fadeOut(function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    });

    // Deaktivasi lisensi
    $(document).on('click', '#rental-mobil-deactivate-license', function(e) {
        e.preventDefault();

        var button = $(this);
        var originalText = button.text();

        // Tampilkan konfirmasi modal
        if (!$('#rental-mobil-deactivate-confirm').length) {
            var modal = $('<div id="rental-mobil-deactivate-confirm" class="rental-mobil-modal">' +
                '<div class="rental-mobil-modal-content">' +
                '<div class="rental-mobil-modal-header">' +
                '<span class="rental-mobil-modal-close">&times;</span>' +
                '<h3>' + (rental_mobil_license.confirm_title || 'Konfirmasi') + '</h3>' +
                '</div>' +
                '<div class="rental-mobil-modal-body">' +
                '<p>' + (rental_mobil_license.confirm_deactivate || 'Anda yakin ingin menghapus lisensi?') + '</p>' +
                '<p>' + (rental_mobil_license.confirm_note || 'Lisensi tetap aktif, dan Anda dapat menggunakannya di domain lain.') + '</p>' +
                '</div>' +
                '<div class="rental-mobil-modal-footer">' +
                '<button type="button" class="button button-secondary rental-mobil-modal-cancel">' + (rental_mobil_license.cancel || 'Batal') + '</button>' +
                '<button type="button" class="button button-primary rental-mobil-modal-confirm">' + (rental_mobil_license.confirm || 'Ya, Nonaktifkan') + '</button>' +
                '</div>' +
                '</div>' +
                '</div>');

            $('body').append(modal);

            // Tambahkan style untuk modal
            if (!$('#rental-mobil-modal-style').length) {
                var style = $('<style id="rental-mobil-modal-style">' +
                    '.rental-mobil-modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }' +
                    '.rental-mobil-modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #ddd; width: 400px; border-radius: 4px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }' +
                    '.rental-mobil-modal-header { padding-bottom: 10px; border-bottom: 1px solid #eee; position: relative; }' +
                    '.rental-mobil-modal-header h3 { margin: 0; }' +
                    '.rental-mobil-modal-close { position: absolute; right: 0; top: 0; font-size: 24px; font-weight: bold; cursor: pointer; }' +
                    '.rental-mobil-modal-body { padding: 15px 0; }' +
                    '.rental-mobil-modal-footer { padding-top: 10px; border-top: 1px solid #eee; text-align: right; }' +
                    '.rental-mobil-modal-footer button { margin-left: 10px; }' +
                    '</style>');
                $('head').append(style);
            }

            // Tampilkan modal
            $('#rental-mobil-deactivate-confirm').show();

            // Event handler untuk tombol close
            $('.rental-mobil-modal-close, .rental-mobil-modal-cancel').on('click', function() {
                $('#rental-mobil-deactivate-confirm').hide();
            });

            // Event handler untuk tombol konfirmasi
            $('.rental-mobil-modal-confirm').on('click', function() {
                // Sembunyikan modal
                $('#rental-mobil-deactivate-confirm').hide();

                // Disable button dan tampilkan loading
                button.prop('disabled', true).text(rental_mobil_license.deactivating);

                // Lakukan deaktivasi
                deactivateLicense(button, originalText);
            });

            // Tutup modal jika user klik di luar modal
            $(window).on('click', function(event) {
                if ($(event.target).is('.rental-mobil-modal')) {
                    $('.rental-mobil-modal').hide();
                }
            });
        } else {
            // Tampilkan modal yang sudah ada
            $('#rental-mobil-deactivate-confirm').show();
        }
    });

    // Fungsi untuk deaktivasi lisensi
    function deactivateLicense(button, originalText) {
        // Log untuk debugging
        console.log('Mencoba deaktivasi lisensi');

        $.ajax({
            url: rental_mobil_license.ajax_url,
            type: 'POST',
            data: {
                action: 'rental_mobil_deactivate_license',
                nonce: rental_mobil_license.nonce
            },
            success: function(response) {
                console.log('Respons deaktivasi lisensi:', response);

                if (response.success) {
                    // Tampilkan pesan sukses
                    var message = $('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
                    $('.rental-mobil-license-settings').prepend(message);

                    // Update status lisensi
                    $('.rental-mobil-license-status')
                        .removeClass('rental-mobil-license-active rental-mobil-license-invalid rental-mobil-license-expired')
                        .addClass('rental-mobil-license-inactive')
                        .text(rental_mobil_license.inactive_text);

                    // Hapus informasi tanggal kedaluwarsa
                    $('.rental-mobil-license-status').next('.description').remove();

                    // Hapus detail lisensi jika ada
                    $('.rental-mobil-license-details').remove();

                    // Ganti tombol deaktivasi dengan tombol aktivasi
                    button.text(rental_mobil_license.activate)
                        .attr('id', 'rental-mobil-activate-license');

                    // Kosongkan input lisensi
                    $('#license_key').val('').prop('disabled', false);
                    $('#license_key_hidden').val('');

                    // Auto dismiss pesan setelah 3 detik
                    setTimeout(function() {
                        message.fadeOut(function() {
                            $(this).remove();
                        });

                        // Reload halaman untuk memperbarui tampilan jika respons berisi reload=true
                        if (response.data && response.data.reload) {
                            location.reload();
                        }
                    }, 3000);
                } else {
                    // Tampilkan pesan error
                    var message = $('<div class="notice notice-error is-dismissible"><p>' + response.data.message + '</p></div>');
                    $('.rental-mobil-license-settings').prepend(message);

                    // Re-enable tombol
                    button.prop('disabled', false).text(originalText);

                    // Auto dismiss pesan setelah 3 detik
                    setTimeout(function() {
                        message.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                // Log error untuk debugging
                console.error('Error deaktivasi lisensi:', status, error);
                console.log(xhr.responseText);

                // Tampilkan pesan error
                var message = $('<div class="notice notice-error is-dismissible"><p>' + rental_mobil_license.error + '</p></div>');
                $('.rental-mobil-license-settings').prepend(message);

                // Re-enable tombol
                button.prop('disabled', false).text(originalText);

                // Auto dismiss pesan setelah 3 detik
                setTimeout(function() {
                    message.fadeOut(function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    }
    // Fungsi untuk membuka WhatsApp
    $(document).on('click', '#rental-mobil-extend-license', function(e) {
        e.preventDefault();

        var whatsappUrl = $(this).attr('href');
        if (whatsappUrl) {
            window.open(whatsappUrl, '_blank');
        }
    });
});