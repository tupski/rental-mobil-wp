# Changelog

Semua perubahan penting pada plugin Rental Mobil WP akan didokumentasikan di file ini.

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
