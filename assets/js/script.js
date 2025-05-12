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

        // Quick View Modal
        const quickViewModal = $('#rental-mobil-quick-view-modal');
        const quickViewTriggers = $('.rental-mobil-quick-view-trigger');

        // Zoom Modal
        const zoomModal = $('#rental-mobil-zoom-modal');
        const zoomImage = $('.rental-mobil-zoom-image');

        // Filter Form
        const filterForm = $('#rental-mobil-filter-form');
        const resetButton = $('.rental-mobil-button-reset');
        const filterToggle = $('#rental-mobil-filter-toggle');
        const filterContainer = $('.rental-mobil-filter');
        const filterOverlay = $('#rental-mobil-filter-overlay');
        const filterClose = $('#rental-mobil-filter-close');

        // Galeri Kendaraan
        const galleryThumbnails = $('.rental-mobil-detail-gallery-thumbnail');
        const featuredImage = $('.rental-mobil-detail-featured-image img');

        // Tanggal Sewa (minimal hari ini)
        const today = new Date().toISOString().split('T')[0];
        $('.rental-mobil-booking-form input[type="date"]').attr('min', today);

        // Toggle filter pada mobile
        filterToggle.on('click', function() {
            filterContainer.addClass('active');
            filterOverlay.addClass('active');
        });

        // Tutup filter sidebar
        filterClose.on('click', function() {
            filterContainer.removeClass('active');
            filterOverlay.removeClass('active');
        });

        // Tutup filter sidebar jika klik overlay
        filterOverlay.on('click', function() {
            filterContainer.removeClass('active');
            filterOverlay.removeClass('active');
        });

        // Galeri Kendaraan - Klik thumbnail untuk mengganti gambar utama
        galleryThumbnails.on('click', function() {
            const newImageSrc = $(this).find('img').attr('src');
            featuredImage.attr('src', newImageSrc);

            // Tambahkan kelas aktif
            galleryThumbnails.removeClass('active');
            $(this).addClass('active');
        });

        // Gallery Modal
        const galleryModal = $('#rental-mobil-gallery-modal');
        const galleryModalClose = galleryModal.find('.rental-mobil-modal-close');
        const galleryOpenButton = $('#rental-mobil-open-gallery');
        const galleryImages = $('.rental-mobil-gallery-modal-image');
        const galleryPrev = $('.rental-mobil-gallery-prev');
        const galleryNext = $('.rental-mobil-gallery-next');
        let currentImageIndex = 0;

        // Fungsi untuk menampilkan gambar dengan index tertentu
        function showGalleryImage(index) {
            galleryImages.removeClass('active');
            galleryImages.eq(index).addClass('active');
            currentImageIndex = index;
        }

        // Buka Gallery Modal
        galleryOpenButton.on('click', function() {
            galleryModal.css('display', 'block');
            showGalleryImage(0);
        });

        // Tutup Gallery Modal
        galleryModalClose.on('click', function() {
            galleryModal.css('display', 'none');
        });

        // Navigasi Gallery
        galleryPrev.on('click', function() {
            let newIndex = currentImageIndex - 1;
            if (newIndex < 0) {
                newIndex = galleryImages.length - 1;
            }
            showGalleryImage(newIndex);
        });

        galleryNext.on('click', function() {
            let newIndex = currentImageIndex + 1;
            if (newIndex >= galleryImages.length) {
                newIndex = 0;
            }
            showGalleryImage(newIndex);
        });

        // Tutup Gallery Modal jika klik di luar modal
        $(window).on('click', function(event) {
            if (event.target === galleryModal[0]) {
                galleryModal.css('display', 'none');
            }
        });

        // Buka Modal Booking
        bookingButtons.on('click', function() {
            const kendaraanId = $(this).data('id');
            const kendaraanTitle = $(this).data('title');

            $('#rental-mobil-booking-kendaraan-id').val(kendaraanId);
            $('#rental-mobil-booking-kendaraan-title').val(kendaraanTitle);

            modal.css('display', 'block');

            // Scroll ke form booking jika di mobile
            if ($(window).width() <= 768) {
                $('html, body').animate({
                    scrollTop: $('#rental-mobil-inline-booking-form').offset().top - 20
                }, 500);
            }
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
            if (event.target === quickViewModal[0]) {
                quickViewModal.css('display', 'none');
            }
            if (event.target === zoomModal[0]) {
                zoomModal.css('display', 'none');
            }
        });

        // Quick View Functionality
        function openQuickView(kendaraanId) {
            const card = $(`.rental-mobil-card[data-id="${kendaraanId}"]`);

            if (card.length === 0) {
                return;
            }

            // Dapatkan data dari card
            const title = card.data('title');
            const permalink = card.data('permalink');
            const hargaHarian = card.data('harga-harian');
            const hargaMingguan = card.data('harga-mingguan');
            const hargaBulanan = card.data('harga-bulanan');
            const merk = card.data('merk');
            const transmisi = card.data('transmisi');
            const bahanBakar = card.data('bahan-bakar');
            const tahun = card.data('tahun');
            const isFeatured = card.data('featured') === 1;
            const isPopular = card.data('popular') === 1;

            // Set data ke quick view modal
            $('.rental-mobil-quick-view-title').text(title);
            $('.rental-mobil-quick-view-detail-link').attr('href', permalink);
            $('.rental-mobil-quick-view-booking').attr('data-id', kendaraanId).attr('data-title', title);

            // Set harga
            $('.rental-mobil-quick-view-price-daily').text(hargaHarian);
            $('.rental-mobil-quick-view-price-weekly').text(hargaMingguan);
            $('.rental-mobil-quick-view-price-monthly').text(hargaBulanan);

            // Set badges
            $('.rental-mobil-quick-view-badges').empty();
            if (isFeatured) {
                $('.rental-mobil-quick-view-badges').append('<div class="rental-mobil-badge rental-mobil-badge-featured">Unggulan</div>');
            } else if (isPopular) {
                $('.rental-mobil-quick-view-badges').append('<div class="rental-mobil-badge rental-mobil-badge-popular">Paling Banyak Disewa</div>');
            }

            // Set meta
            $('.rental-mobil-quick-view-meta').empty();
            if (merk) {
                $('.rental-mobil-quick-view-meta').append(`
                    <div class="rental-mobil-quick-view-meta-item">
                        <span class="rental-mobil-quick-view-meta-label">Merk:</span>
                        <span class="rental-mobil-quick-view-meta-value">${merk}</span>
                    </div>
                `);
            }
            if (transmisi) {
                $('.rental-mobil-quick-view-meta').append(`
                    <div class="rental-mobil-quick-view-meta-item">
                        <span class="rental-mobil-quick-view-meta-label">Transmisi:</span>
                        <span class="rental-mobil-quick-view-meta-value">${transmisi}</span>
                    </div>
                `);
            }
            if (bahanBakar) {
                $('.rental-mobil-quick-view-meta').append(`
                    <div class="rental-mobil-quick-view-meta-item">
                        <span class="rental-mobil-quick-view-meta-label">Bahan Bakar:</span>
                        <span class="rental-mobil-quick-view-meta-value">${bahanBakar}</span>
                    </div>
                `);
            }
            if (tahun) {
                $('.rental-mobil-quick-view-meta').append(`
                    <div class="rental-mobil-quick-view-meta-item">
                        <span class="rental-mobil-quick-view-meta-label">Tahun:</span>
                        <span class="rental-mobil-quick-view-meta-value">${tahun}</span>
                    </div>
                `);
            }

            // Set gambar utama
            let featuredImageSrc = '';
            if (card.find('.rental-mobil-card-image img').length > 0) {
                featuredImageSrc = card.find('.rental-mobil-card-image img').attr('src');
            }
            $('.rental-mobil-quick-view-featured-image').attr('src', featuredImageSrc);

            // Tambahkan event click untuk zoom gambar
            $('.rental-mobil-quick-view-main-image').off('click').on('click', function() {
                const imgSrc = $('.rental-mobil-quick-view-featured-image').attr('src');
                openZoomModal(imgSrc, title);
            });

            // Dapatkan galeri kendaraan melalui AJAX
            $.ajax({
                url: rental_mobil_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'rental_mobil_get_gallery',
                    nonce: rental_mobil_ajax.nonce,
                    kendaraan_id: kendaraanId
                },
                success: function(response) {
                    if (response.success && response.data.gallery) {
                        // Tampilkan galeri
                        $('.rental-mobil-quick-view-thumbnails').empty();

                        // Tambahkan featured image sebagai thumbnail pertama
                        if (featuredImageSrc) {
                            $('.rental-mobil-quick-view-thumbnails').append(`
                                <div class="rental-mobil-quick-view-thumbnail active" data-src="${featuredImageSrc}">
                                    <img src="${featuredImageSrc}" alt="${title}">
                                </div>
                            `);
                        }

                        // Tambahkan galeri lainnya
                        $.each(response.data.gallery, function(index, image) {
                            $('.rental-mobil-quick-view-thumbnails').append(`
                                <div class="rental-mobil-quick-view-thumbnail" data-src="${image.url}">
                                    <img src="${image.thumbnail}" alt="${title}">
                                </div>
                            `);
                        });

                        // Inisialisasi thumbnail click
                        $('.rental-mobil-quick-view-thumbnail').on('click', function() {
                            const src = $(this).data('src');
                            $('.rental-mobil-quick-view-featured-image').attr('src', src);
                            $('.rental-mobil-quick-view-thumbnail').removeClass('active');
                            $(this).addClass('active');

                            // Tambahkan event click untuk zoom pada thumbnail
                            $('.rental-mobil-quick-view-thumbnail').off('dblclick').on('dblclick', function() {
                                const imgSrc = $(this).data('src');
                                openZoomModal(imgSrc, title);
                            });
                        });
                    }
                }
            });

            // Tampilkan modal
            quickViewModal.css('display', 'block');
        }

        // Buka Quick View saat klik trigger
        quickViewTriggers.on('click', function() {
            const kendaraanId = $(this).data('id');
            openQuickView(kendaraanId);
        });

        // Tutup Quick View Modal
        quickViewModal.find('.rental-mobil-modal-close').on('click', function() {
            quickViewModal.css('display', 'none');
        });

        // Zoom Modal Functionality
        function openZoomModal(imgSrc, title) {
            // Set gambar
            zoomImage.attr('src', imgSrc).attr('alt', title);

            // Tampilkan modal
            zoomModal.css('display', 'block');
        }

        // Tutup Zoom Modal
        zoomModal.find('.rental-mobil-modal-close').on('click', function() {
            zoomModal.css('display', 'none');
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

                            // Scroll ke form booking jika di mobile
                            if ($(window).width() <= 768) {
                                $('html, body').animate({
                                    scrollTop: $('#rental-mobil-inline-booking-form').offset().top - 20
                                }, 500);
                            }
                        });

                        // Reinitialize quick view triggers
                        $('.rental-mobil-quick-view-trigger').on('click', function() {
                            const kendaraanId = $(this).data('id');
                            openQuickView(kendaraanId);
                        });

                        // Sembunyikan filter pada mobile setelah submit
                        if ($(window).width() <= 768) {
                            filterContainer.removeClass('active');
                            filterOverlay.removeClass('active');
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
        // Inisialisasi slider
        initSlider();
    });

    // Fungsi untuk menginisialisasi slider
    function initSlider() {
        const $ = jQuery;
        const sliders = $('.rental-mobil-slider');

        if (sliders.length === 0) {
            return;
        }

        sliders.each(function() {
            const slider = $(this);
            const container = slider.find('.rental-mobil-slider-container');
            const prevButton = slider.find('.rental-mobil-slider-prev');
            const nextButton = slider.find('.rental-mobil-slider-next');
            const items = slider.find('.rental-mobil-slider-item');

            if (items.length <= 0) {
                return;
            }

            // Scroll ke item berikutnya
            nextButton.on('click', function() {
                const itemWidth = items.first().outerWidth(true);
                const scrollLeft = container.scrollLeft();
                const targetScroll = scrollLeft + itemWidth;

                container.animate({
                    scrollLeft: targetScroll
                }, 300);
            });

            // Scroll ke item sebelumnya
            prevButton.on('click', function() {
                const itemWidth = items.first().outerWidth(true);
                const scrollLeft = container.scrollLeft();
                const targetScroll = scrollLeft - itemWidth;

                container.animate({
                    scrollLeft: targetScroll
                }, 300);
            });
        });
    }

})(jQuery);
