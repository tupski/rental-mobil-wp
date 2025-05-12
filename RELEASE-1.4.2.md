# Rental Mobil WP v1.4.2

## Deskripsi
Rental Mobil WP adalah plugin WordPress yang dirancang khusus untuk bisnis rental mobil. Plugin ini memungkinkan Anda menampilkan daftar kendaraan, detail kendaraan, dan sistem booking yang terintegrasi dengan WhatsApp.

## Perubahan di Versi 1.4.2

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

## File yang Diubah
- `templates/layout-kendaraan.php` - Menghapus judul "Daftar Kendaraan Rental"
- `assets/css/style.css` - Memperbaiki styling filter dan input
- `assets/js/script.js` - Menambahkan parameter filter di URL dalam bahasa Indonesia
- `includes/settings.php` - Mengubah tab "Pengaturan Umum" menjadi "Dokumentasi"
- `rental-mobil.php` - Memperbarui versi plugin dan menambahkan konstanta RENTAL_MOBIL_PLUGIN_FILE
- `readme.txt` - Memperbarui versi plugin
- `CHANGELOG.md` - Memperbarui changelog

## Cara Menggunakan
Perubahan ini akan otomatis aktif setelah update. Pengguna dapat melihat tab Dokumentasi di admin dan parameter filter di URL akan menggunakan bahasa Indonesia.

## Kompatibilitas
- WordPress 6.0 atau lebih baru
- PHP 8.0 atau lebih baru
- Kompatibel dengan tema Divi

## Catatan Penting
- Pastikan untuk memperbarui permalink setelah upgrade untuk memastikan perubahan berfungsi dengan baik
- Jika Anda telah melakukan modifikasi pada file plugin, pastikan untuk backup sebelum upgrade
