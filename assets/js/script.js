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
        const resetButton = $('#rental-mobil-reset-filter');
        const filterToggle = $('#rental-mobil-filter-toggle');
        const filterContainer = $('.rental-mobil-filter');
        const filterOverlay = $('#rental-mobil-filter-overlay');
        const filterClose = $('#rental-mobil-filter-close');
        const sidebar = $('.rental-mobil-sidebar');
        const sidebarInner = $('.rental-mobil-sidebar-inner');
        const activeFilters = $('#rental-mobil-active-filters');

        // Time Dropdown - Modal Booking
        const hourDropdown = $('#rental-mobil-booking-jam-hour');
        const minuteDropdown = $('#rental-mobil-booking-jam-minute');
        const timeInput = $('#rental-mobil-booking-jam');

        // Time Dropdown - Inline Booking
        const inlineHourDropdown = $('#rental-mobil-inline-booking-jam-hour');
        const inlineMinuteDropdown = $('#rental-mobil-inline-booking-jam-minute');
        const inlineTimeInput = $('#rental-mobil-inline-booking-jam');

        // Inisialisasi formData untuk paginasi awal
        let initialFormData = filterForm.serialize();

        // Inisialisasi time dropdown untuk modal booking
        function updateTimeInput() {
            const hour = hourDropdown.val();
            const minute = minuteDropdown.val();

            if (hour && minute) {
                timeInput.val(`${hour}:${minute}`);
            } else {
                timeInput.val('');
            }
        }

        // Inisialisasi time dropdown untuk inline booking
        function updateInlineTimeInput() {
            const hour = inlineHourDropdown.val();
            const minute = inlineMinuteDropdown.val();

            if (hour && minute) {
                inlineTimeInput.val(`${hour}:${minute}`);
            } else {
                inlineTimeInput.val('');
            }
        }

        // Event listener untuk dropdown jam dan menit - modal booking
        hourDropdown.on('change', updateTimeInput);
        minuteDropdown.on('change', updateTimeInput);

        // Event listener untuk dropdown jam dan menit - inline booking
        inlineHourDropdown.on('change', updateInlineTimeInput);
        inlineMinuteDropdown.on('change', updateInlineTimeInput);

        // Pastikan filter sticky berfungsi dengan benar
        function updateStickyFilter() {
            // Pastikan sidebar memiliki tinggi yang cukup
            if ($(window).width() > 768) {
                // Hanya terapkan di desktop
                const sidebarHeight = sidebar.outerHeight();
                const contentHeight = $('.rental-mobil-content').outerHeight();

                // Pastikan sidebar memiliki tinggi yang cukup untuk sticky
                sidebar.css('min-height', contentHeight + 'px');

                // Pastikan filter selalu sticky
                sidebarInner.css({
                    'position': 'sticky',
                    'top': '50px',
                    'max-height': 'calc(100vh - 100px)',
                    'overflow-y': 'auto'
                });
            }
        }

        // Panggil fungsi saat halaman dimuat
        updateStickyFilter();

        // Panggil fungsi saat jendela diubah ukurannya
        $(window).on('resize', function() {
            updateStickyFilter();
        });

        // Search Functionality
        const searchInput = $('#rental-mobil-search-input');
        const searchButton = $('#rental-mobil-search-button');
        const searchResults = $('#rental-mobil-search-results');
        const searchModal = $('#rental-mobil-search-modal');
        const searchModalResults = $('#rental-mobil-search-modal-results');

        // Galeri Kendaraan
        const galleryThumbnails = $('.rental-mobil-detail-gallery-thumbnail');
        const featuredImage = $('.rental-mobil-detail-featured-image img');

        // Tanggal Sewa (minimal hari ini)
        const today = new Date().toISOString().split('T')[0];
        $('.rental-mobil-booking-form input[type="date"]').attr('min', today);

        // Initialize time picker for time fields
        $('.rental-mobil-time-picker').each(function() {
            $(this).on('focus', function() {
                $(this).attr('type', 'time');
                $(this).attr('step', '1800'); // 30 minutes step
            }).on('blur', function() {
                if (!$(this).val()) {
                    $(this).attr('type', 'text');
                }
            });
        });

        // Toggle filter pada mobile
        filterToggle.on('click', function() {
            sidebar.addClass('active');
            filterOverlay.addClass('active');
        });

        // Tutup filter sidebar
        filterClose.on('click', function() {
            sidebar.removeClass('active');
            filterOverlay.removeClass('active');
        });

        // Tutup filter sidebar jika klik overlay
        filterOverlay.on('click', function() {
            sidebar.removeClass('active');
            filterOverlay.removeClass('active');
        });

        // Search Functionality
        let searchTimeout;

        // Autocomplete search
        searchInput.on('keyup', function() {
            const keyword = $(this).val().trim();

            // Clear previous timeout
            clearTimeout(searchTimeout);

            // Hide results if empty
            if (keyword.length < 2) {
                searchResults.removeClass('active').empty();
                return;
            }

            // Set timeout to prevent too many requests
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: rental_mobil_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'rental_mobil_autocomplete',
                        nonce: rental_mobil_ajax.nonce,
                        keyword: keyword
                    },
                    success: function(response) {
                        if (response.success) {
                            searchResults.empty();

                            if (response.data.results.length > 0) {
                                $.each(response.data.results, function(_, item) {
                                    searchResults.append(`
                                        <div class="rental-mobil-search-item" data-id="${item.id}" data-permalink="${item.permalink}">
                                            <div class="rental-mobil-search-item-title">${item.title}</div>
                                        </div>
                                    `);
                                });

                                // Add view all results link
                                searchResults.append(`
                                    <div class="rental-mobil-search-item rental-mobil-search-view-all">
                                        <div class="rental-mobil-search-item-title">Lihat semua hasil untuk "${keyword}"</div>
                                    </div>
                                `);

                                // Show results
                                searchResults.addClass('active');

                                // Click on search item
                                $('.rental-mobil-search-item').on('click', function() {
                                    if ($(this).hasClass('rental-mobil-search-view-all')) {
                                        // Search all results
                                        searchAllResults(keyword);
                                    } else {
                                        // Go to permalink
                                        window.location.href = $(this).data('permalink');
                                    }
                                });
                            } else {
                                searchResults.append(`
                                    <div class="rental-mobil-search-no-results">
                                        Tidak ada hasil untuk "${keyword}"
                                    </div>
                                `);
                                searchResults.addClass('active');
                            }
                        }
                    }
                });
            }, 300);
        });

        // Hide search results when click outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.rental-mobil-search-box').length) {
                searchResults.removeClass('active');
            }
        });

        // Search button click
        searchButton.on('click', function() {
            const keyword = searchInput.val().trim();

            if (keyword.length < 2) {
                return;
            }

            searchAllResults(keyword);
        });

        // Search all results function
        function searchAllResults(keyword) {
            // Show search modal
            searchModal.css('display', 'block');

            // Show loading
            searchModalResults.html(`
                <div class="rental-mobil-search-loading">
                    <div class="rental-mobil-spinner"></div>
                    <p>Mencari kendaraan...</p>
                </div>
            `);

            // Get search results
            $.ajax({
                url: rental_mobil_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'rental_mobil_search',
                    nonce: rental_mobil_ajax.nonce,
                    keyword: keyword
                },
                success: function(response) {
                    if (response.success) {
                        searchModalResults.empty();

                        if (response.data.results.length > 0) {
                            searchModalResults.append(`<div class="rental-mobil-grid"></div>`);

                            $.each(response.data.results, function(_, item) {
                                searchModalResults.find('.rental-mobil-grid').append(`
                                    <div class="rental-mobil-card" data-id="${item.id}">
                                        <div class="rental-mobil-card-image rental-mobil-quick-view-trigger" data-id="${item.id}">
                                            <img src="${item.thumbnail}" alt="${item.title}">
                                        </div>
                                        <div class="rental-mobil-card-content">
                                            <h3 class="rental-mobil-card-title rental-mobil-quick-view-trigger" data-id="${item.id}">
                                                ${item.title}
                                            </h3>
                                            <div class="rental-mobil-card-meta">
                                                <div class="rental-mobil-card-meta-item">
                                                    <span class="rental-mobil-card-meta-label">Merk:</span>
                                                    <span class="rental-mobil-card-meta-value">${item.merk}</span>
                                                </div>
                                                <div class="rental-mobil-card-meta-item">
                                                    <span class="rental-mobil-card-meta-label">Transmisi:</span>
                                                    <span class="rental-mobil-card-meta-value">${item.transmisi}</span>
                                                </div>
                                            </div>
                                            <div class="rental-mobil-card-price">
                                                <span class="rental-mobil-card-price-label">Mulai dari</span>
                                                <span class="rental-mobil-card-price-value">${item.harga}</span>
                                                <span class="rental-mobil-card-price-period">/ hari</span>
                                            </div>
                                            <div class="rental-mobil-card-actions">
                                                <button class="rental-mobil-button rental-mobil-button-detail rental-mobil-quick-view-trigger" data-id="${item.id}">
                                                    Lihat Detail
                                                </button>
                                                <button class="rental-mobil-button rental-mobil-button-booking" data-id="${item.id}" data-title="${item.title}">
                                                    Booking
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            });

                            // Inisialisasi quick view trigger untuk hasil pencarian
                            $('.rental-mobil-search-modal .rental-mobil-quick-view-trigger').on('click', function() {
                                const kendaraanId = $(this).data('id');
                                openQuickView(kendaraanId);
                            });

                            // Inisialisasi booking button untuk hasil pencarian
                            $('.rental-mobil-search-modal .rental-mobil-button-booking').on('click', function() {
                                const kendaraanId = $(this).data('id');
                                const kendaraanTitle = $(this).data('title');
                                openBookingModal(kendaraanId, kendaraanTitle);
                            });
                        } else {
                            searchModalResults.html(`
                                <div class="rental-mobil-search-no-results">
                                    <p>Tidak ada hasil untuk "${keyword}"</p>
                                    <p>Silakan coba kata kunci lain atau gunakan filter untuk menemukan kendaraan yang Anda cari.</p>
                                </div>
                            `);
                        }
                    } else {
                        searchModalResults.html(`
                            <div class="rental-mobil-search-no-results">
                                <p>Terjadi kesalahan. Silakan coba lagi.</p>
                            </div>
                        `);
                    }
                },
                error: function() {
                    searchModalResults.html(`
                        <div class="rental-mobil-search-no-results">
                            <p>Terjadi kesalahan. Silakan coba lagi.</p>
                        </div>
                    `);
                }
            });
        }

        // Close search modal
        searchModal.find('.rental-mobil-modal-close').on('click', function() {
            searchModal.css('display', 'none');
        });

        // Close search modal when click outside
        $(window).on('click', function(event) {
            if (event.target === searchModal[0]) {
                searchModal.css('display', 'none');
            }
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

            // Set judul dinamis
            $('.rental-mobil-modal-title-kendaraan').text(kendaraanTitle);
            $('.rental-mobil-modal-subtitle-kendaraan').text(kendaraanTitle);

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
                // Jika card tidak ditemukan, coba dapatkan data dari AJAX
                $.ajax({
                    url: rental_mobil_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'rental_mobil_get_kendaraan_data',
                        nonce: rental_mobil_ajax.nonce,
                        kendaraan_id: kendaraanId
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            // Tampilkan quick view dengan data dari AJAX
                            displayQuickView(kendaraanId, response.data);
                        } else {
                            console.error('Gagal mendapatkan data kendaraan');
                        }
                    },
                    error: function() {
                        console.error('Gagal mendapatkan data kendaraan');
                    }
                });
                return;
            }

            // Dapatkan data dari card
            const title = card.data('title');
            const hargaHarian = card.data('harga-harian');
            const hargaMingguan = card.data('harga-mingguan');
            const hargaBulanan = card.data('harga-bulanan');
            const merk = card.data('merk');
            const transmisi = card.data('transmisi');
            const bahanBakar = card.data('bahan-bakar');
            const tahun = card.data('tahun');
            const isFeatured = card.data('featured') === 1;
            const isPopular = card.data('popular') === 1;

            // Tampilkan quick view dengan data dari card
            displayQuickView(kendaraanId, {
                title: title,
                harga_harian: hargaHarian,
                harga_mingguan: hargaMingguan,
                harga_bulanan: hargaBulanan,
                merk: merk,
                transmisi: transmisi,
                bahan_bakar: bahanBakar,
                tahun: tahun,
                is_featured: isFeatured,
                is_popular: isPopular
            });
        }

        // Fungsi untuk menampilkan quick view
        function displayQuickView(kendaraanId, data) {
            // Set data ke quick view modal
            $('.rental-mobil-quick-view-title').text(data.title);
            $('.rental-mobil-quick-view-booking').attr('data-id', kendaraanId).attr('data-title', data.title);

            // Set harga
            $('.rental-mobil-quick-view-price-daily').text(data.harga_harian);
            $('.rental-mobil-quick-view-price-weekly').text(data.harga_mingguan);
            $('.rental-mobil-quick-view-price-monthly').text(data.harga_bulanan);

            // Set badges
            $('.rental-mobil-quick-view-badges').empty();
            if (data.is_featured) {
                $('.rental-mobil-quick-view-badges').append('<div class="rental-mobil-badge rental-mobil-badge-featured">Unggulan</div>');
            } else if (data.is_popular) {
                $('.rental-mobil-quick-view-badges').append('<div class="rental-mobil-badge rental-mobil-badge-popular">Paling Banyak Disewa</div>');
            }

            // Set meta
            $('.rental-mobil-quick-view-meta').empty();
            if (data.merk) {
                $('.rental-mobil-quick-view-meta').append(`
                    <div class="rental-mobil-quick-view-meta-item">
                        <span class="rental-mobil-quick-view-meta-label">Merk:</span>
                        <span class="rental-mobil-quick-view-meta-value">${data.merk}</span>
                    </div>
                `);
            }
            if (data.transmisi) {
                $('.rental-mobil-quick-view-meta').append(`
                    <div class="rental-mobil-quick-view-meta-item">
                        <span class="rental-mobil-quick-view-meta-label">Transmisi:</span>
                        <span class="rental-mobil-quick-view-meta-value">${data.transmisi}</span>
                    </div>
                `);
            }
            if (data.bahan_bakar) {
                $('.rental-mobil-quick-view-meta').append(`
                    <div class="rental-mobil-quick-view-meta-item">
                        <span class="rental-mobil-quick-view-meta-label">Bahan Bakar:</span>
                        <span class="rental-mobil-quick-view-meta-value">${data.bahan_bakar}</span>
                    </div>
                `);
            }
            if (data.tahun) {
                $('.rental-mobil-quick-view-meta').append(`
                    <div class="rental-mobil-quick-view-meta-item">
                        <span class="rental-mobil-quick-view-meta-label">Tahun:</span>
                        <span class="rental-mobil-quick-view-meta-value">${data.tahun}</span>
                    </div>
                `);
            }

            // Set gambar utama
            let featuredImageSrc = data.featured_image || '';
            $('.rental-mobil-quick-view-featured-image').attr('src', featuredImageSrc);

            // Tambahkan event click untuk zoom gambar
            $('.rental-mobil-quick-view-main-image').off('click').on('click', function() {
                const imgSrc = $('.rental-mobil-quick-view-featured-image').attr('src');
                openZoomModal(imgSrc, data.title);
            });

            // Tambahkan event click untuk tombol share
            $('.rental-mobil-share-button').off('click').on('click', function() {
                const platform = $(this).data('platform');

                // Dapatkan path URL saat ini (tanpa domain dan query string)
                const currentPath = window.location.pathname;

                // Dapatkan parameter URL saat ini
                const urlParams = new URLSearchParams(window.location.search);

                // Tentukan base URL berdasarkan halaman saat ini
                let baseUrl;
                if (currentPath.includes('daftar-kendaraan')) {
                    baseUrl = window.location.origin + '/daftar-kendaraan/';
                } else if (currentPath.includes('daftar-mobil-rental')) {
                    baseUrl = window.location.origin + '/daftar-mobil-rental/';
                } else {
                    // Gunakan path saat ini jika bukan salah satu di atas
                    baseUrl = window.location.origin + currentPath;
                }

                // Buat URL untuk berbagi dengan parameter kata_kunci
                urlParams.set('kata_kunci', title);
                urlParams.set('halaman', '1');

                // Buat URL lengkap
                const shareUrl = baseUrl + '?' + urlParams.toString();

                switch(platform) {
                    case 'whatsapp':
                        window.open('https://api.whatsapp.com/send?text=' + encodeURIComponent(title + ' - ' + shareUrl), '_blank');
                        break;
                    case 'facebook':
                        window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl), '_blank');
                        break;
                    case 'twitter':
                        window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(title) + '&url=' + encodeURIComponent(shareUrl), '_blank');
                        break;
                    case 'telegram':
                        window.open('https://t.me/share/url?url=' + encodeURIComponent(shareUrl) + '&text=' + encodeURIComponent(title), '_blank');
                        break;
                    case 'email':
                        window.open('mailto:?subject=' + encodeURIComponent('Info Rental Mobil: ' + title) + '&body=' + encodeURIComponent('Lihat info tentang ' + title + ' di ' + shareUrl), '_blank');
                        break;
                }
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
                        $.each(response.data.gallery, function(_, image) {
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

                        // Inisialisasi navigasi thumbnail
                        initThumbnailNavigation();
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

        // Tambahkan event handler untuk tombol booking di quick-view modal
        $(document).on('click', '.rental-mobil-quick-view-booking', function() {
            const kendaraanId = $(this).data('id');
            const kendaraanTitle = $(this).data('title');

            // Pastikan kita mendapatkan judul yang benar
            console.log('Booking kendaraan:', kendaraanId, kendaraanTitle);

            // Set data ke form booking
            $('#rental-mobil-booking-kendaraan-id').val(kendaraanId);
            $('#rental-mobil-booking-kendaraan-title').val(kendaraanTitle);

            // Set judul dinamis
            $('.rental-mobil-modal-title-kendaraan').text(kendaraanTitle);
            $('.rental-mobil-modal-subtitle-kendaraan').text(kendaraanTitle);

            // Tampilkan modal booking
            modal.css('display', 'block');

            // Tutup modal quick-view
            quickViewModal.css('display', 'none');

            // Scroll ke form booking jika di mobile
            if ($(window).width() <= 768) {
                $('html, body').animate({
                    scrollTop: $('#rental-mobil-inline-booking-form').offset().top - 20
                }, 500);
            }
        });

        // Zoom Modal Functionality
        function openZoomModal(imgSrc, title) {
            // Set gambar
            zoomImage.attr('src', imgSrc).attr('alt', title);

            // Dapatkan semua thumbnail dari quick view
            const thumbnails = $('.rental-mobil-quick-view-thumbnail');
            const zoomThumbnailsContainer = $('.rental-mobil-zoom-thumbnails');

            // Kosongkan container thumbnail zoom
            zoomThumbnailsContainer.empty();

            // Tambahkan semua thumbnail ke zoom modal
            thumbnails.each(function() {
                const thumbSrc = $(this).data('src');
                const thumbImg = $(this).find('img').attr('src');
                const isActive = thumbSrc === imgSrc;

                zoomThumbnailsContainer.append(`
                    <div class="rental-mobil-zoom-thumbnail ${isActive ? 'active' : ''}" data-src="${thumbSrc}">
                        <img src="${thumbImg}" alt="${title}">
                    </div>
                `);
            });

            // Inisialisasi thumbnail click
            $('.rental-mobil-zoom-thumbnail').on('click', function() {
                const src = $(this).data('src');
                zoomImage.attr('src', src);
                $('.rental-mobil-zoom-thumbnail').removeClass('active');
                $(this).addClass('active');
            });

            // Inisialisasi navigasi thumbnail
            initZoomThumbnailNavigation();

            // Inisialisasi tombol share
            $('.rental-mobil-zoom-share').on('click', function() {
                // Dapatkan path URL saat ini (tanpa domain dan query string)
                const currentPath = window.location.pathname;

                // Dapatkan parameter URL saat ini
                const urlParams = new URLSearchParams(window.location.search);

                // Tentukan base URL berdasarkan halaman saat ini
                let baseUrl;
                if (currentPath.includes('daftar-kendaraan')) {
                    baseUrl = window.location.origin + '/daftar-kendaraan/';
                } else if (currentPath.includes('daftar-mobil-rental')) {
                    baseUrl = window.location.origin + '/daftar-mobil-rental/';
                } else {
                    // Gunakan path saat ini jika bukan salah satu di atas
                    baseUrl = window.location.origin + currentPath;
                }

                // Buat URL untuk berbagi dengan parameter kata_kunci
                urlParams.set('kata_kunci', title);
                urlParams.set('halaman', '1');

                // Tambahkan nonce jika ada di URL saat ini
                const nonceParam = urlParams.get('rental_mobil_filter_nonce');
                if (nonceParam) {
                    urlParams.set('rental_mobil_filter_nonce', nonceParam);
                }

                // Buat URL lengkap
                const shareUrl = baseUrl + '?' + urlParams.toString();

                if (navigator.share) {
                    navigator.share({
                        title: title,
                        url: shareUrl
                    })
                    .catch(console.error);
                } else {
                    // Fallback untuk browser yang tidak mendukung Web Share API
                    // Gunakan Clipboard API jika tersedia
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(shareUrl)
                            .then(() => {
                                alert('URL telah disalin ke clipboard');
                            })
                            .catch(err => {
                                console.error('Gagal menyalin URL: ', err);
                                alert('Gagal menyalin URL. Silakan coba lagi.');
                            });
                    } else {
                        // Fallback untuk browser yang tidak mendukung Clipboard API
                        try {
                            // Gunakan navigator.clipboard API jika tersedia
                            if (navigator.clipboard) {
                                navigator.clipboard.writeText(shareUrl)
                                    .then(() => {
                                        alert('URL telah disalin ke clipboard');
                                    })
                                    .catch(() => {
                                        alert('Gagal menyalin URL. Silakan coba lagi.');
                                    });
                            } else {
                                // Fallback lama jika tidak ada pilihan lain
                                const tempInput = $('<input>');
                                $('body').append(tempInput);
                                tempInput.val(shareUrl).select();

                                // Gunakan document.execCommand dengan peringatan
                                // eslint-disable-next-line deprecation/deprecation
                                const successful = document.execCommand('copy');
                                if (successful) {
                                    alert('URL telah disalin ke clipboard');
                                } else {
                                    alert('Gagal menyalin URL. Silakan coba lagi.');
                                }
                                tempInput.remove();
                            }
                        } catch (err) {
                            console.error('Gagal menyalin URL: ', err);
                            alert('Gagal menyalin URL. Silakan coba lagi.');
                        }
                    }
                }
            });

            // Tampilkan modal
            zoomModal.css('display', 'block');
        }

        // Fungsi untuk menginisialisasi navigasi thumbnail di zoom modal
        function initZoomThumbnailNavigation() {
            const thumbnails = $('.rental-mobil-zoom-thumbnail');
            const prevButton = $('.rental-mobil-zoom-prev');
            const nextButton = $('.rental-mobil-zoom-next');

            // Jika kurang dari 6 thumbnail, sembunyikan navigasi
            if (thumbnails.length <= 5) {
                prevButton.hide();
                nextButton.hide();
                return;
            }

            // Tampilkan maksimal 5 thumbnail
            if (thumbnails.length > 5) {
                // Sembunyikan thumbnail ke-6 dan seterusnya
                thumbnails.slice(5).css('display', 'none');

                // Tampilkan tombol navigasi
                prevButton.show();
                nextButton.show();
            }

            // Navigasi ke thumbnail sebelumnya
            prevButton.on('click', function() {
                const firstVisible = thumbnails.filter(':visible').first().index();

                if (firstVisible > 0) {
                    // Sembunyikan thumbnail terakhir yang terlihat
                    thumbnails.eq(firstVisible + 4).css('display', 'none');
                    // Tampilkan thumbnail sebelumnya
                    thumbnails.eq(firstVisible - 1).css('display', 'flex');
                } else {
                    // Loop ke akhir
                    thumbnails.slice(0, 5).css('display', 'none');
                    thumbnails.slice(Math.max(0, thumbnails.length - 5)).css('display', 'flex');
                }
            });

            // Navigasi ke thumbnail berikutnya
            nextButton.on('click', function() {
                const lastVisible = thumbnails.filter(':visible').last().index();

                if (lastVisible < thumbnails.length - 1) {
                    // Sembunyikan thumbnail pertama yang terlihat
                    thumbnails.filter(':visible').first().css('display', 'none');
                    // Tampilkan thumbnail berikutnya
                    thumbnails.eq(lastVisible + 1).css('display', 'flex');
                } else {
                    // Loop ke awal
                    thumbnails.slice(Math.max(0, thumbnails.length - 5)).css('display', 'none');
                    thumbnails.slice(0, 5).css('display', 'flex');
                }
            });
        }

        // Tutup Zoom Modal
        zoomModal.find('.rental-mobil-modal-close').on('click', function() {
            zoomModal.css('display', 'none');
        });

        // Submit Form Booking
        bookingForm.on('submit', function(e) {
            e.preventDefault();

            // Update time input dari dropdown
            updateTimeInput();

            const kendaraanId = $('#rental-mobil-booking-kendaraan-id').val();

            // Collect all form data
            const formData = $(this).serializeArray();
            const formValues = {};

            // Convert form data to object
            $.each(formData, function(_, field) {
                formValues[field.name] = field.value;
            });

            // Format date fields if they exist
            $.each(formValues, function(key, value) {
                // Check if this is a date field by looking at the input type
                const input = $(`#rental-mobil-booking-${key}`);
                if (input.attr('type') === 'date' && value) {
                    const dateObj = new Date(value);
                    formValues[key] = dateObj.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                }
            });

            // Prepare data for AJAX request
            const ajaxData = {
                action: 'rental_mobil_get_whatsapp',
                nonce: rental_mobil_ajax.nonce,
                kendaraan_id: kendaraanId
            };

            // Add all form values to AJAX data
            $.each(formValues, function(key, value) {
                ajaxData[key] = value;
            });

            // Dapatkan nomor WhatsApp dan template pesan dari AJAX
            $.ajax({
                url: rental_mobil_ajax.ajax_url,
                type: 'POST',
                data: ajaxData,
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

            // Update URL dengan parameter filter
            const formValues = {};
            $.each($(this).serializeArray(), function(_, field) {
                if (field.value) {
                    formValues[field.name] = field.value;
                }
            });

            // Buat parameter URL dalam bahasa Indonesia
            const urlParams = new URLSearchParams();
            if (formValues.keyword) urlParams.set('kata_kunci', formValues.keyword);
            if (formValues.merk) urlParams.set('merk', formValues.merk);
            if (formValues.transmisi) urlParams.set('transmisi', formValues.transmisi);
            if (formValues.bahan_bakar) urlParams.set('bahan_bakar', formValues.bahan_bakar);
            if (formValues.tipe) urlParams.set('tipe', formValues.tipe);
            if (formValues.tahun) urlParams.set('tahun', formValues.tahun);
            if (formValues.orderby) urlParams.set('orderby', formValues.orderby);
            if (formValues.order) urlParams.set('order', formValues.order);

            // Tambahkan parameter halaman=1 karena ini adalah filter baru
            urlParams.set('halaman', 1);

            // Update URL tanpa reload halaman
            const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
            window.history.pushState({ path: newUrl }, '', newUrl);

            // Tambahkan paged=1 untuk reset ke halaman pertama saat filter
            loadKendaraan(formData, 1);
        });

        // Fungsi untuk memuat kendaraan dengan AJAX
        function loadKendaraan(formData, page) {
            // Tambahkan parameter halaman
            const data = formData ? formData + '&halaman=' + page : 'halaman=' + page;

            // Update URL dengan parameter halaman
            const urlParams = new URLSearchParams(window.location.search);

            // Dapatkan parameter filter dari formData
            const formParams = new URLSearchParams(formData);

            // Tambahkan atau perbarui parameter filter ke URL
            formParams.forEach((value, key) => {
                if (value) {
                    urlParams.set(key, value);
                }
            });

            // Tambahkan parameter halaman ke URL
            urlParams.set('halaman', page);

            // Update URL tanpa reload halaman
            const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
            window.history.pushState({ path: newUrl }, '', newUrl);

            // Scroll ke atas halaman sebelum memuat konten baru
            $('html, body').animate({
                scrollTop: $('.rental-mobil-layout').offset().top - 50
            }, 300);

            $.ajax({
                url: rental_mobil_ajax.ajax_url,
                type: 'POST',
                data: data + '&action=rental_mobil_filter&nonce=' + rental_mobil_ajax.nonce,
                beforeSend: function() {
                    $('#rental-mobil-results').html('<p>Memuat...</p>');
                },
                success: function(response) {
                    if (response.success) {
                        // Perbarui konten hasil
                        $('#rental-mobil-results').html(response.data.html);

                        // Tampilkan filter aktif
                        renderActiveFilters(response.data.active_filters);

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

                        // Inisialisasi paginasi
                        initPagination(formData);

                        // Sembunyikan filter pada mobile setelah submit
                        if ($(window).width() <= 768) {
                            filterContainer.removeClass('active');
                            filterOverlay.removeClass('active');
                        }

                        // Update sticky filter setelah konten dimuat
                        updateStickyFilter();
                    } else {
                        $('#rental-mobil-results').html('<p>Terjadi kesalahan. Silakan coba lagi.</p>');
                    }
                },
                error: function() {
                    $('#rental-mobil-results').html('<p>Terjadi kesalahan. Silakan coba lagi.</p>');
                }
            });
        }

        // Fungsi untuk menginisialisasi paginasi
        function initPagination(formData) {
            // Delegasi event untuk tombol paginasi
            $(document).off('click', '.rental-mobil-pagination-links a').on('click', '.rental-mobil-pagination-links a', function(e) {
                e.preventDefault(); // Selalu mencegah perilaku default

                const page = $(this).data('page');

                // Jika pengguna menekan tombol Ctrl atau Command saat mengklik, buka di tab baru
                if (e.ctrlKey || e.metaKey) {
                    // Buat URL dengan parameter halaman
                    const urlParams = new URLSearchParams(window.location.search);
                    urlParams.set('halaman', page);
                    const newUrl = window.location.pathname + '?' + urlParams.toString();

                    // Buka di tab baru
                    window.open(newUrl, '_blank');
                    return;
                }

                // Gunakan AJAX untuk memuat konten tanpa refresh halaman
                loadKendaraan(formData, page);
            });
        }

        // Inisialisasi paginasi saat halaman pertama kali dimuat
        initPagination(initialFormData);

        // Reset Filter
        resetButton.on('click', function() {
            filterForm[0].reset();
            filterForm.trigger('submit');

            // Sembunyikan filter pada mobile setelah reset
            if ($(window).width() <= 768) {
                sidebar.removeClass('active');
                filterOverlay.removeClass('active');
            }
        });

        // Fungsi untuk menampilkan filter aktif
        function renderActiveFilters(filters) {
            activeFilters.empty();

            if (!filters || Object.keys(filters).length === 0) {
                return;
            }

            $.each(filters, function(key, filter) {
                activeFilters.append(`
                    <div class="rental-mobil-active-filter" data-filter="${key}">
                        <span class="rental-mobil-active-filter-label">${filter.label}:</span>
                        <span class="rental-mobil-active-filter-value">${filter.value}</span>
                        <span class="rental-mobil-active-filter-remove" data-filter="${key}">×</span>
                    </div>
                `);
            });

            // Inisialisasi tombol hapus filter
            $('.rental-mobil-active-filter-remove').on('click', function() {
                const filterKey = $(this).data('filter');

                // Reset nilai filter
                if (filterKey === 'keyword') {
                    $('#rental-mobil-filter-keyword').val('');
                } else if (filterKey === 'merk') {
                    $('#rental-mobil-filter-merk').val('');
                } else if (filterKey === 'transmisi') {
                    $('#rental-mobil-filter-transmisi').val('');
                } else if (filterKey === 'bahan_bakar') {
                    $('#rental-mobil-filter-bahan-bakar').val('');
                } else if (filterKey === 'tipe') {
                    $('#rental-mobil-filter-tipe').val('');
                } else if (filterKey === 'tahun') {
                    $('#rental-mobil-filter-tahun').val('');
                } else if (filterKey === 'orderby') {
                    $('#rental-mobil-filter-orderby').val('date');
                } else if (filterKey === 'order') {
                    $('#rental-mobil-filter-order').val('DESC');
                }

                // Submit form
                filterForm.submit();
            });
        }

        // Submit Form Booking Inline
        inlineBookingForm.on('submit', function(e) {
            e.preventDefault();

            // Update time input dari dropdown
            updateInlineTimeInput();

            const kendaraanId = $('#rental-mobil-inline-booking-kendaraan-id').val();

            // Collect all form data
            const formData = $(this).serializeArray();
            const formValues = {};

            // Convert form data to object
            $.each(formData, function(_, field) {
                formValues[field.name] = field.value;
            });

            // Format date fields if they exist
            $.each(formValues, function(key, value) {
                // Check if this is a date field by looking at the input type
                const input = $(`#rental-mobil-inline-booking-${key}`);
                if (input.attr('type') === 'date' && value) {
                    const dateObj = new Date(value);
                    formValues[key] = dateObj.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                }
            });

            // Prepare data for AJAX request
            const ajaxData = {
                action: 'rental_mobil_get_whatsapp',
                nonce: rental_mobil_ajax.nonce,
                kendaraan_id: kendaraanId
            };

            // Add all form values to AJAX data
            $.each(formValues, function(key, value) {
                ajaxData[key] = value;
            });

            // Dapatkan nomor WhatsApp dan template pesan dari AJAX
            $.ajax({
                url: rental_mobil_ajax.ajax_url,
                type: 'POST',
                data: ajaxData,
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

        // Inisialisasi quick view trigger untuk slider
        initSliderQuickView();

        // Cek parameter URL dan isi form filter
        initFilterFromUrl();
    });

    // Fungsi untuk menginisialisasi quick view trigger untuk slider
    function initSliderQuickView() {
        const $ = jQuery;

        // Inisialisasi quick view trigger untuk slider
        $('.rental-mobil-slider .rental-mobil-quick-view-trigger').on('click', function() {
            const kendaraanId = $(this).data('id');
            openQuickView(kendaraanId);
        });
    }

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

            // Dapatkan pengaturan slider
            const autoSlide = slider.data('auto-slide') === true || slider.data('auto-slide') === 'true';
            const loop = slider.data('loop') === true || slider.data('loop') === 'true';
            const speed = parseInt(slider.data('speed')) || 300;
            const interval = parseInt(slider.data('interval')) || 5000;

            let autoSlideInterval;

            if (items.length <= 0) {
                return;
            }

            // Fungsi untuk scroll ke item berikutnya
            function scrollToNext() {
                const itemWidth = items.first().outerWidth(true);
                const scrollLeft = container.scrollLeft();
                const containerWidth = container.width();
                const scrollWidth = container.get(0).scrollWidth;

                // Jika sudah di akhir dan loop diaktifkan, kembali ke awal
                if (scrollLeft + containerWidth >= scrollWidth - 10) {
                    if (loop) {
                        container.animate({
                            scrollLeft: 0
                        }, speed);
                    }
                } else {
                    container.animate({
                        scrollLeft: scrollLeft + itemWidth
                    }, speed);
                }
            }

            // Fungsi untuk scroll ke item sebelumnya
            function scrollToPrev() {
                const itemWidth = items.first().outerWidth(true);
                const scrollLeft = container.scrollLeft();
                const containerWidth = container.width();
                const scrollWidth = container.get(0).scrollWidth;

                // Jika sudah di awal dan loop diaktifkan, ke akhir
                if (scrollLeft <= 10) {
                    if (loop) {
                        container.animate({
                            scrollLeft: scrollWidth - containerWidth
                        }, speed);
                    }
                } else {
                    container.animate({
                        scrollLeft: scrollLeft - itemWidth
                    }, speed);
                }
            }

            // Scroll ke item berikutnya
            nextButton.on('click', function() {
                scrollToNext();

                // Reset auto slide interval jika auto slide diaktifkan
                if (autoSlide && autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                    autoSlideInterval = setInterval(scrollToNext, interval);
                }
            });

            // Scroll ke item sebelumnya
            prevButton.on('click', function() {
                scrollToPrev();

                // Reset auto slide interval jika auto slide diaktifkan
                if (autoSlide && autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                    autoSlideInterval = setInterval(scrollToNext, interval);
                }
            });

            // Aktifkan auto slide jika diatur
            if (autoSlide) {
                autoSlideInterval = setInterval(scrollToNext, interval);

                // Hentikan auto slide saat hover
                slider.on('mouseenter', function() {
                    clearInterval(autoSlideInterval);
                });

                // Lanjutkan auto slide saat mouse keluar
                slider.on('mouseleave', function() {
                    autoSlideInterval = setInterval(scrollToNext, interval);
                });
            }
        });
    }

    // Fungsi untuk menginisialisasi navigasi thumbnail
    function initThumbnailNavigation() {
        const $ = jQuery;
        const thumbnailsContainer = $('.rental-mobil-quick-view-thumbnails');
        const prevButton = $('.rental-mobil-quick-view-prev');
        const nextButton = $('.rental-mobil-quick-view-next');
        const thumbnails = $('.rental-mobil-quick-view-thumbnail');

        // Sembunyikan navigasi secara default
        prevButton.hide();
        nextButton.hide();

        // Jika tidak ada thumbnail, keluar dari fungsi
        if (thumbnails.length <= 1) {
            return;
        }

        // Tampilkan semua thumbnail terlebih dahulu
        thumbnails.css('display', 'flex');

        // Periksa apakah container penuh (overflow)
        const containerWidth = thumbnailsContainer.width();
        let totalWidth = 0;
        let visibleCount = 0;

        thumbnails.each(function() {
            const thumbWidth = $(this).outerWidth(true); // Termasuk margin
            totalWidth += thumbWidth;

            // Jika total lebar melebihi container, tandai sebagai overflow
            if (totalWidth <= containerWidth) {
                visibleCount++;
            }
        });

        // Jika total lebar thumbnail melebihi container, tampilkan navigasi
        if (totalWidth > containerWidth) {
            // Sembunyikan thumbnail yang tidak muat
            thumbnails.slice(visibleCount).css('display', 'none');

            // Tampilkan tombol navigasi
            prevButton.show();
            nextButton.show();

            // Navigasi ke thumbnail sebelumnya
            prevButton.off('click').on('click', function() {
                const firstVisible = thumbnails.filter(':visible').first().index();

                if (firstVisible > 0) {
                    // Sembunyikan thumbnail terakhir yang terlihat
                    thumbnails.eq(firstVisible + visibleCount - 1).css('display', 'none');
                    // Tampilkan thumbnail sebelumnya
                    thumbnails.eq(firstVisible - 1).css('display', 'flex');
                } else {
                    // Loop ke akhir
                    thumbnails.slice(0, visibleCount).css('display', 'none');
                    thumbnails.slice(Math.max(0, thumbnails.length - visibleCount)).css('display', 'flex');
                }
            });

            // Navigasi ke thumbnail berikutnya
            nextButton.off('click').on('click', function() {
                const lastVisible = thumbnails.filter(':visible').last().index();

                if (lastVisible < thumbnails.length - 1) {
                    // Sembunyikan thumbnail pertama yang terlihat
                    thumbnails.filter(':visible').first().css('display', 'none');
                    // Tampilkan thumbnail berikutnya
                    thumbnails.eq(lastVisible + 1).css('display', 'flex');
                } else {
                    // Loop ke awal
                    thumbnails.slice(Math.max(0, thumbnails.length - visibleCount)).css('display', 'none');
                    thumbnails.slice(0, visibleCount).css('display', 'flex');
                }
            });
        }
    }

    // Fungsi untuk menginisialisasi filter dari parameter URL
    function initFilterFromUrl() {
        const $ = jQuery;
        const urlParams = new URLSearchParams(window.location.search);
        let hasFilter = false;
        let currentPage = 1;

        // Mapping parameter URL ke field form
        if (urlParams.has('kata_kunci')) {
            $('#rental-mobil-filter-keyword').val(urlParams.get('kata_kunci'));
            hasFilter = true;
        }

        if (urlParams.has('merk')) {
            $('#rental-mobil-filter-merk').val(urlParams.get('merk'));
            hasFilter = true;
        }

        if (urlParams.has('transmisi')) {
            $('#rental-mobil-filter-transmisi').val(urlParams.get('transmisi'));
            hasFilter = true;
        }

        if (urlParams.has('bahan_bakar')) {
            $('#rental-mobil-filter-bahan-bakar').val(urlParams.get('bahan_bakar'));
            hasFilter = true;
        }

        if (urlParams.has('tipe')) {
            $('#rental-mobil-filter-tipe').val(urlParams.get('tipe'));
            hasFilter = true;
        }

        if (urlParams.has('tahun')) {
            $('#rental-mobil-filter-tahun').val(urlParams.get('tahun'));
            hasFilter = true;
        }

        if (urlParams.has('orderby')) {
            $('#rental-mobil-filter-orderby').val(urlParams.get('orderby'));
            hasFilter = true;
        }

        if (urlParams.has('order')) {
            $('#rental-mobil-filter-order').val(urlParams.get('order'));
            hasFilter = true;
        }

        // Cek parameter halaman
        if (urlParams.has('halaman')) {
            currentPage = parseInt(urlParams.get('halaman')) || 1;
            hasFilter = true;
        } else if (urlParams.has('paged')) {
            // Untuk kompatibilitas dengan parameter lama
            currentPage = parseInt(urlParams.get('paged')) || 1;
            hasFilter = true;
        }

        // Jika ada parameter filter, submit form dan muat halaman yang benar
        if (hasFilter) {
            // Trigger submit form untuk menerapkan filter
            $('#rental-mobil-filter-form').trigger('submit');
        }
    }

})(jQuery);
