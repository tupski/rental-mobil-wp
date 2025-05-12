# Changelog

Semua perubahan penting pada plugin Rental Mobil WP akan didokumentasikan di file ini.

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
