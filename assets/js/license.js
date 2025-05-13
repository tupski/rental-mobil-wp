/**
 * JavaScript untuk menangani aktivasi dan deaktivasi lisensi
 *
 * Versi: 1.1.0
 * Terintegrasi dengan verifikasi.tupski.web.id
 */
jQuery(document).ready(function($) {
    // Aktivasi lisensi
    $('#rental-mobil-activate-license').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var originalText = button.text();

        // Disable button dan tampilkan loading
        button.prop('disabled', true).text(rental_mobil_license.activating);

        $.ajax({
            url: rental_mobil_license.ajax_url,
            type: 'POST',
            data: {
                action: 'rental_mobil_activate_license',
                nonce: rental_mobil_license.nonce
            },
            success: function(response) {
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
                    }, 3000);
                }
            },
            error: function() {
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

        // Disable button dan tampilkan loading
        button.prop('disabled', true).text(rental_mobil_license.deactivating);

        $.ajax({
            url: rental_mobil_license.ajax_url,
            type: 'POST',
            data: {
                action: 'rental_mobil_deactivate_license',
                nonce: rental_mobil_license.nonce
            },
            success: function(response) {
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

                    // Ganti tombol deaktivasi dengan tombol aktivasi
                    button.text(rental_mobil_license.activate)
                        .attr('id', 'rental-mobil-activate-license');

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
            error: function() {
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
    // Periksa status lisensi
    $(document).on('click', '#rental-mobil-check-license', function(e) {
        e.preventDefault();

        var button = $(this);
        var originalText = button.text();

        // Disable button dan tampilkan loading
        button.prop('disabled', true).text(rental_mobil_license.checking || 'Memeriksa...');

        $.ajax({
            url: rental_mobil_license.ajax_url,
            type: 'POST',
            data: {
                action: 'rental_mobil_check_license_status',
                nonce: rental_mobil_license.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Tampilkan pesan sukses
                    var message = $('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
                    $('.rental-mobil-license-settings').prepend(message);

                    // Update status lisensi jika valid
                    if (response.data.status === 'valid') {
                        $('.rental-mobil-license-status')
                            .removeClass('rental-mobil-license-inactive rental-mobil-license-invalid rental-mobil-license-expired')
                            .addClass('rental-mobil-license-active')
                            .text(rental_mobil_license.active_text || 'Aktif');
                    }

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

                    // Update status lisensi jika tidak valid
                    $('.rental-mobil-license-status')
                        .removeClass('rental-mobil-license-active rental-mobil-license-inactive rental-mobil-license-expired')
                        .addClass('rental-mobil-license-invalid')
                        .text(rental_mobil_license.invalid_text || 'Lisensi Salah');

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
            error: function() {
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
});