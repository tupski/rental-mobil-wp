# Changelog

Semua perubahan penting pada plugin Rental Mobil WP akan didokumentasikan di file ini.

## [1.7.6] - 2024-05-27

### Ditambahkan
- Pengaturan untuk mengaktifkan/menonaktifkan tombol bagikan di detail kendaraan dan modal zoom
- Perbaikan urutan elemen di mobile pada quick view modal

### Diubah
- Tombol bagikan tidak akan ditampilkan jika dinonaktifkan di pengaturan

## [1.7.5] - 2024-05-26

### Diubah
- Perbaikan tampilan quick view modal pada mobile agar lebih rapi dan sesuai dengan urutan: Judul, Gambar, Deskripsi/Detail, Harga, Tombol Booking dan Bagikan
- Perbaikan tampilan modal share pada mobile dengan animasi dan desain yang lebih baik
- Perbaikan tampilan tombol dan elemen UI pada mobile untuk pengalaman pengguna yang lebih baik

## [1.7.4] - 2024-05-25

### Ditambahkan
- Efek pulse/gelombang pada tombol filter di mobile
- Animasi loading saat memuat kendaraan/saat filter diterapkan/direset

### Diubah
- Filter mobile sekarang otomatis tertutup setelah tombol Terapkan ditekan

## [1.7.3] - 2024-05-24

### Diubah
- Tampilan quick view modal pada mobile menjadi horizontal dengan urutan: Judul, Gambar, Deskripsi/Detail, Harga, Tombol Booking dan Bagikan
- Perbaikan tampilan tombol pada quick view modal untuk mobile
- Perbaikan layout elemen pada quick view modal untuk mobile

## [1.7.2] - 2024-05-23

### Ditambahkan
- Opsi tampilan card yang berbeda (default, vertical, horizontal) melalui parameter `tampilan` di shortcode
- Tampilan card memanjang ke bawah (vertical) dan memanjang ke samping (horizontal)
- CSS untuk mendukung tampilan card yang berbeda
- Perbaikan tampilan quick view modal untuk mobile

### Diubah
- Memperbaiki tombol bagikan yang tidak berfungsi
- Memperbaiki tampilan quick view modal untuk mobile

## [1.7.1] - 2024-05-22

### Ditambahkan
- Tombol "Bagikan" tunggal di mobile yang menampilkan popup modal dengan platform berbagi
- Pengaturan untuk tombol "Salin URL" di tab Share
- Notifikasi saat URL berhasil disalin

### Diubah
- Tampilan tombol close di filter mobile agar lebih mudah diklik
- Filter mobile sekarang otomatis tertutup saat tombol Terapkan atau Reset ditekan
- Overlay filter mobile dengan transparansi 25% untuk bagian di luar sidebar
- Filter mobile sekarang otomatis tertutup saat overlay diklik

## [1.7.0] - 2024-05-21

### Diubah
- Menghapus pengaturan acak kendaraan di tab Filter untuk menyederhanakan pengaturan urutan
- Pengaturan urutan kendaraan di tab Filter sekarang langsung diaplikasikan tanpa perlu menekan tombol reset

### Diperbaiki
- Masalah pengaturan urutan yang tidak langsung diaplikasikan di frontend

## [1.6.9] - 2024-05-20

### Ditambahkan
- Inisialisasi filter otomatis dari parameter URL saat halaman dimuat
- Dukungan untuk filter langsung melalui URL (contoh: /?kata_kunci=Kia%20Rio)

### Diubah
- Perbaikan format URL filter agar lebih bersih tanpa parameter yang tidak diperlukan
- Pengaturan urutan kendaraan di tab Filter sekarang menggunakan dropdown select
- Struktur menu admin yang lebih sederhana dengan menghapus menu duplikat "Rental Mobil WP"

### Diperbaiki
- Masalah pengaturan acak yang hilang setelah disimpan
- Masalah urutan kendaraan yang tidak langsung diaplikasikan
- Masalah parameter yang tidak diinginkan di URL filter

## [1.6.8] - 2024-05-18

### Ditambahkan
- Peningkatan filter sidebar pada desktop agar tetap sticky dengan batasan dari awal card hingga akhir halaman
- Scrollbar yang lebih baik untuk filter sidebar saat konten filter terlalu panjang
- Animasi smooth saat filter sidebar bergerak mengikuti scroll
- Efek slide animasi untuk filter mobile saat tombol filter diklik
- Styling untuk select biasa yang lebih baik setelah menghapus Select2

### Diperbaiki
- Perbaikan posisi filter sidebar saat mendekati footer
- Optimasi performa untuk filter sticky dengan event scroll yang lebih efisien
- Perbaikan tombol booking yang tidak bisa diklik di mobile
- Perbaikan tampilan opsi dalam dropdown agar tidak terpotong
- Perbaikan validasi form untuk field kondisional, field yang required hanya divalidasi saat kondisinya terpenuhi
- Perbaikan lebih lanjut untuk field kondisional, menghapus atribut required saat field tersembunyi dan mengembalikannya saat field ditampilkan

### Dihapus
- Menghapus penggunaan library Select2 dan menggantinya dengan select biasa untuk meningkatkan performa dan mengurangi masalah kompatibilitas

## [1.6.7] - 2024-05-18

### Diperbaiki
- Masalah dropdown Select2 yang tidak berfungsi pada modal booking untuk pengunjung non-admin
- Error "openQuickView is not defined" dan "title is not defined" di console
- Masalah validasi form pada field yang tidak terlihat
- Tombol booking yang tidak berfungsi setelah kembali dari WhatsApp
- Peningkatan z-index untuk dropdown Select2 agar tidak tertutup oleh elemen lain
- Perbaikan validasi form secara manual untuk memastikan hanya field yang visible yang divalidasi

## [1.6.3] - 2024-05-16

### Ditambahkan
- Fitur ukuran field pada form builder (25%, 50%, 75%, 100%)
- Tampilan form yang lebih dinamis dan rapi dengan field yang dapat diatur ukurannya
- Responsif pada perangkat mobile
- Fitur conditional form di form builder (field yang hanya muncul jika kondisi terpenuhi)
- Tombol bagikan di lightbox image yang sama dengan tombol bagikan di quick view

## [1.6.2] - 2024-05-16

### Diperbaiki
- Masalah gambar yang tidak terload di quick view modal
- Tombol bagikan yang tidak berfungsi dengan benar
- Masalah nama kendaraan yang salah di booking modal
- Tooltip pada tombol bagikan yang menampilkan nama platform dengan benar

## [1.6.1] - 2024-05-15

### Ditambahkan
- Tooltip pada tombol bagikan yang menampilkan nama platform

### Diperbaiki
- Parameter URL sekarang langsung terfilter saat halaman dimuat
- Masalah nama kendaraan yang salah di booking modal dari quick view
- Scrollbar pada filter sidebar yang seharusnya tidak muncul
- URL bagikan sekarang menggunakan parameter kata kunci yang benar

## [1.6.0] - 2024-05-01

### Ditambahkan
- Fitur berbagi kendaraan ke berbagai platform (WhatsApp, Facebook, Twitter, Telegram, Email)
- Ikon yang sesuai untuk setiap platform berbagi
- Parameter kata kunci di URL untuk berbagi kendaraan

### Diperbaiki
- Tampilan filter pada perangkat mobile
- Responsivitas modal detail kendaraan
- Performa loading gambar kendaraan

## [1.5.0] - 2024-04-15

### Ditambahkan
- Fitur kendaraan unggulan dan paling banyak disewa
- Badge untuk menandai kendaraan unggulan dan paling banyak disewa
- Pengaturan untuk mengaktifkan/menonaktifkan fitur kendaraan unggulan dan paling banyak disewa

### Diperbaiki
- Tampilan filter pada perangkat desktop dan mobile
- Responsivitas card kendaraan
- Performa loading daftar kendaraan

## [1.4.3] - 2024-02-15

### Ditambahkan
- Paginasi AJAX untuk daftar kendaraan (9 kendaraan per halaman)
- Overlay dengan ikon mata saat hover pada foto di card kendaraan
- Galeri dengan navigasi panah untuk foto-foto kendaraan (maksimal 3 foto, dengan navigasi jika lebih)
- Lightbox yang menampilkan foto-foto lain di bawah saat memperbesar gambar
- Opsi berbagi di lightbox dengan URL format /daftar-kendaraan/?kata_kunci=judul
- Layout modal Detail yang dioptimalkan untuk mobile

### Diubah
- Perbaikan tampilan galeri pada modal Detail
- Perbaikan tampilan harga pada modal Detail di mobile (tidak inline)
- Tombol Booking Sekarang fullwidth di mobile
- Perbaikan ukuran modal pada mobile agar tidak bisa digeser kanan-kiri
- Perbaikan layout galeri di mobile dengan foto-foto kecil di bawah foto utama
- Perbaikan navigasi thumbnail di desktop berdasarkan container yang penuh
- Perbaikan field keyword agar tidak melebihi border
- Penambahan padding pada filter saat scroll lock agar tidak terlalu mepet dengan browser

## [1.4.2] - 2024-01-30

### Ditambahkan
- Tab Dokumentasi di admin menggantikan tab Pengaturan Umum
- Informasi versi plugin di halaman Dokumentasi
- Link kontribusi GitHub di halaman Dokumentasi
- Border untuk filter di semua sisi

### Diubah
- Judul "Daftar Kendaraan" dan "Daftar Kendaraan Rental" dihapus
- Styling field keyword untuk konsistensi dengan elemen lain
- Background untuk judul "Filter Kendaraan"
- Parameter filter di URL menggunakan bahasa Indonesia

### Diperbaiki
- Styling input keyword yang tidak konsisten
- Tampilan filter dengan border yang lebih jelas
- Konsistensi styling antara input dan select

## [1.4.1] - 2023-12-25

### Ditambahkan
- Fitur zoom pada galeri foto di quick view
- Ikon zoom pada gambar utama
- Efek hover pada gambar untuk menunjukkan bahwa gambar bisa di-zoom
- Modal zoom untuk melihat gambar dalam ukuran penuh
- Fitur pencarian dengan keyword menggunakan AJAX
- Autocomplete pada kolom pencarian
- Layout baru dengan filter di sebelah kiri (25%) dan card di sebelah kanan (75%)
- Tombol reset filter
- Scroll lock pada sidebar filter

### Diubah
- Perbaikan tampilan quick view pada perangkat mobile
- Peningkatan layout dan responsivitas quick view
- Perbaikan padding dan margin untuk tampilan mobile
- Perbaikan ukuran font dan elemen untuk tampilan mobile
- Tampilan filter yang lebih modern dengan efek hover dan focus
- Peningkatan UX dengan autocomplete dan hasil pencarian yang lebih baik

### Diperbaiki
- Masalah pengaturan yang tidak tersimpan dengan benar di beberapa tab browser
- Perbaikan caching pengaturan untuk memastikan konsistensi data
- Perbaikan tampilan filter pada perangkat mobile
- Perbaikan card mobil unggulan dan banyak disewa agar modal ajax muncul
- Perbaikan tombol pada modal popup (hanya tombol booking)
- Perbaikan fatal error karena fungsi AJAX yang dideklarasikan dua kali
- Perbaikan pencarian dengan menghapus autocomplete dan hanya berfungsi saat tombol filter diklik
- Penambahan filter aktif dengan tombol close
- Perbaikan judul "Daftar Kendaraan Rental" dan ukuran pada mobile
- Perbaikan modal booking dengan judul dinamis
- Perbaikan warna background untuk card mobil unggulan dan banyak disewa
- Perbaikan posisi badge unggulan dan banyak disewa

## [1.4.0] - 2023-12-20

### Ditambahkan
- Fitur quick view untuk melihat detail kendaraan tanpa perlu membuka halaman baru
- Quick view dapat diakses dengan mengklik foto kendaraan, judul kendaraan, atau tombol "Lihat Detail"
- Quick view menampilkan informasi penting seperti merk, transmisi, bahan bakar, dan tahun kendaraan
- Quick view menampilkan harga harian, mingguan, dan bulanan dengan fallback "Hubungi Kami" jika tidak diisi
- Quick view menampilkan galeri kendaraan dengan thumbnail yang bisa diklik
- AJAX handler untuk memuat galeri kendaraan secara dinamis

### Diubah
- Tombol "Lihat Detail" sekarang membuka quick view modal alih-alih mengarahkan ke halaman detail
- Peningkatan pengalaman pengguna dengan memudahkan perbandingan kendaraan

## [1.3.2] - 2023-12-15

### Ditambahkan
- Kompatibilitas dengan PHP 8.0

### Diubah
- Peningkatan persyaratan versi WordPress menjadi 6.0
- Peningkatan persyaratan versi PHP menjadi 8.0

### Diperbaiki
- Perbaikan kompatibilitas dengan PHP 8.0 dan WordPress 6.0+
- Optimasi performa dan keamanan

## [1.3.1] - 2023-12-05

### Ditambahkan
- Kompatibilitas dengan WordPress 6.8

### Diubah
- Penggunaan gambar dengan wp_get_attachment_image() untuk meningkatkan kompatibilitas
- Optimasi query database untuk performa yang lebih baik

### Diperbaiki
- Peringatan slow query pada penggunaan tax_query
- Peringatan penggunaan gambar non-enqueued
- Jumlah tag di readme.txt dibatasi menjadi 5 tag

## [1.3.0] - 2023-11-30

### Ditambahkan
- Galeri kendaraan dengan integrasi Media Library WordPress
- Modal galeri untuk melihat gambar dalam ukuran penuh
- Floating booking button untuk tampilan mobile
- Bagian kendaraan terkait berdasarkan tipe kendaraan

### Diubah
- Peningkatan tampilan detail kendaraan dengan layout yang lebih modern
- Perbaikan tampilan harga dengan format kartu yang lebih menarik
- Peningkatan responsivitas untuk semua ukuran layar

### Diperbaiki
- Tampilan galeri pada perangkat mobile
- Navigasi galeri untuk pengalaman pengguna yang lebih baik

## [1.2.0] - 2023-11-25

### Ditambahkan
- Fitur harga sewa mingguan dan bulanan
- Tampilan harga mingguan dan bulanan di halaman detail kendaraan
- Floating filter button untuk tampilan mobile
- Container full width untuk tampilan daftar kendaraan

### Diubah
- Slug halaman detail dari `/kendaraan/nama-kendaraan` menjadi `/rental/nama-kendaraan`
- Perbaikan tampilan detail kendaraan untuk mobile
- Perbaikan tampilan daftar kendaraan dengan layout fullwidth

### Diperbaiki
- Responsivitas tampilan pada perangkat mobile
- Tata letak filter pada tampilan mobile

## [1.1.0] - 2023-11-20

### Ditambahkan
- Fitur tahun kendaraan
- Filter berdasarkan tahun kendaraan
- Taxonomy baru untuk tahun kendaraan

### Diubah
- Perbaikan tampilan dan responsivitas
- Peningkatan performa filter

## [1.0.0] - 2023-11-15

### Ditambahkan
- Rilis pertama plugin Rental Mobil WP
- Custom Post Type untuk Kendaraan
- Taxonomy untuk Merk, Transmisi, Bahan Bakar, dan Tipe Kendaraan
- Shortcode untuk menampilkan daftar kendaraan dan detail kendaraan
- Filter kendaraan berdasarkan berbagai kriteria
- Form booking yang terintegrasi dengan WhatsApp
- Pengaturan untuk konfigurasi nomor WhatsApp dan template pesan
