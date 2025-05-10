/**
 * Rental Mobil WP - JavaScript
 */

(function($) {
    'use strict';

    // DOM Ready
    $(function() {
        // Modal Booking
        const modal = $('#rental-mobil-booking-modal');
        const modalClose = $('.rental-mobil-modal-close');
        const bookingButtons = $('.rental-mobil-button-booking');
        const bookingForm = $('#rental-mobil-booking-form');
        const inlineBookingForm = $('#rental-mobil-inline-booking-form');

        // Filter Form
        const filterForm = $('#rental-mobil-filter-form');
        const resetButton = $('.rental-mobil-button-reset');
        const filterToggle = $('#rental-mobil-filter-toggle');
        const filterContainer = $('.rental-mobil-filter');

        // Galeri Kendaraan
        const galleryThumbnails = $('.rental-mobil-detail-gallery-thumbnail');
        const featuredImage = $('.rental-mobil-detail-featured-image img');

        // Tanggal Sewa (minimal hari ini)
        const today = new Date().toISOString().split('T')[0];
        $('.rental-mobil-booking-form input[type="date"]').attr('min', today);

        // Toggle filter pada mobile
        filterToggle.on('click', function() {
            filterContainer.toggleClass('active');
        });

        // Galeri Kendaraan - Klik thumbnail untuk mengganti gambar utama
        galleryThumbnails.on('click', function() {
            const newImageSrc = $(this).find('img').attr('src');
            featuredImage.attr('src', newImageSrc);

            // Tambahkan kelas aktif
            galleryThumbnails.removeClass('active');
            $(this).addClass('active');
        });

        // Buka Modal Booking
        bookingButtons.on('click', function() {
            const kendaraanId = $(this).data('id');
            const kendaraanTitle = $(this).data('title');

            $('#rental-mobil-booking-kendaraan-id').val(kendaraanId);
            $('#rental-mobil-booking-kendaraan-title').val(kendaraanTitle);

            modal.css('display', 'block');
        });

        // Tutup Modal
        modalClose.on('click', function() {
            modal.css('display', 'none');
        });

        // Tutup Modal jika klik di luar modal
        $(window).on('click', function(event) {
            if (event.target === modal[0]) {
                modal.css('display', 'none');
            }
        });

        // Submit Form Booking
        bookingForm.on('submit', function(e) {
            e.preventDefault();

            const kendaraanId = $('#rental-mobil-booking-kendaraan-id').val();
            const kendaraanTitle = $('#rental-mobil-booking-kendaraan-title').val();
            const nama = $('#rental-mobil-booking-nama').val();
            const domisili = $('#rental-mobil-booking-domisili').val();
            const tanggalSewa = $('#rental-mobil-booking-tanggal').val();
            const jamSewa = $('#rental-mobil-booking-jam').val();
            const durasiSewa = $('#rental-mobil-booking-durasi').val();
            const satuanDurasi = $('#rental-mobil-booking-satuan').val();

            // Format tanggal
            const tanggalObj = new Date(tanggalSewa);
            const formattedTanggal = tanggalObj.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            // Buat pesan WhatsApp
            let message = `Halo, saya ingin menyewa kendaraan *${kendaraanTitle}* dengan detail berikut:

Nama: ${nama}
Domisili: ${domisili}
Tanggal Sewa: ${formattedTanggal}
Jam Sewa: ${jamSewa}
Durasi Sewa: ${durasiSewa} ${satuanDurasi}

Mohon informasi lebih lanjut. Terima kasih.`;

            // Dapatkan nomor WhatsApp dari AJAX
            $.ajax({
                url: rental_mobil_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'rental_mobil_get_whatsapp',
                    nonce: rental_mobil_ajax.nonce,
                    kendaraan_id: kendaraanId,
                    nama: nama,
                    domisili: domisili,
                    tanggal_sewa: formattedTanggal,
                    jam_sewa: jamSewa,
                    durasi_sewa: durasiSewa,
                    satuan_durasi: satuanDurasi
                },
                success: function(response) {
                    if (response.success) {
                        // Buka WhatsApp
                        const whatsappUrl = `https://wa.me/${response.data.whatsapp_number}?text=${encodeURIComponent(response.data.message)}`;
                        window.open(whatsappUrl, '_blank');

                        // Reset form dan tutup modal
                        bookingForm[0].reset();
                        modal.css('display', 'none');
                    } else {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        });

        // Filter Kendaraan
        filterForm.on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: rental_mobil_ajax.ajax_url,
                type: 'POST',
                data: formData + '&action=rental_mobil_filter&nonce=' + rental_mobil_ajax.nonce,
                beforeSend: function() {
                    $('#rental-mobil-results').html('<p>Memuat...</p>');
                },
                success: function(response) {
                    if (response.success) {
                        $('#rental-mobil-results').html(response.data.html);

                        // Reinitialize booking buttons
                        $('.rental-mobil-button-booking').on('click', function() {
                            const kendaraanId = $(this).data('id');
                            const kendaraanTitle = $(this).data('title');

                            $('#rental-mobil-booking-kendaraan-id').val(kendaraanId);
                            $('#rental-mobil-booking-kendaraan-title').val(kendaraanTitle);

                            modal.css('display', 'block');
                        });

                        // Sembunyikan filter pada mobile setelah submit
                        if ($(window).width() <= 768) {
                            filterContainer.removeClass('active');
                        }
                    } else {
                        $('#rental-mobil-results').html('<p>Terjadi kesalahan. Silakan coba lagi.</p>');
                    }
                },
                error: function() {
                    $('#rental-mobil-results').html('<p>Terjadi kesalahan. Silakan coba lagi.</p>');
                }
            });
        });

        // Reset Filter
        resetButton.on('click', function() {
            filterForm[0].reset();
            filterForm.trigger('submit');
        });

        // Submit Form Booking Inline
        inlineBookingForm.on('submit', function(e) {
            e.preventDefault();

            const kendaraanId = $('#rental-mobil-inline-booking-kendaraan-id').val();
            const kendaraanTitle = $('#rental-mobil-inline-booking-kendaraan-title').val();
            const nama = $('#rental-mobil-inline-booking-nama').val();
            const domisili = $('#rental-mobil-inline-booking-domisili').val();
            const tanggalSewa = $('#rental-mobil-inline-booking-tanggal').val();
            const jamSewa = $('#rental-mobil-inline-booking-jam').val();
            const durasiSewa = $('#rental-mobil-inline-booking-durasi').val();
            const satuanDurasi = $('#rental-mobil-inline-booking-satuan').val();

            // Format tanggal
            const tanggalObj = new Date(tanggalSewa);
            const formattedTanggal = tanggalObj.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            // Dapatkan nomor WhatsApp dari AJAX
            $.ajax({
                url: rental_mobil_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'rental_mobil_get_whatsapp',
                    nonce: rental_mobil_ajax.nonce,
                    kendaraan_id: kendaraanId,
                    nama: nama,
                    domisili: domisili,
                    tanggal_sewa: formattedTanggal,
                    jam_sewa: jamSewa,
                    durasi_sewa: durasiSewa,
                    satuan_durasi: satuanDurasi
                },
                success: function(response) {
                    if (response.success) {
                        // Buka WhatsApp
                        const whatsappUrl = `https://wa.me/${response.data.whatsapp_number}?text=${encodeURIComponent(response.data.message)}`;
                        window.open(whatsappUrl, '_blank');

                        // Reset form
                        inlineBookingForm[0].reset();

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

})(jQuery);
