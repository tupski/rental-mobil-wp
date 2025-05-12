# Rental Mobil WP - Versi 1.4.1

## Fitur Baru dan Perbaikan

### Fitur Zoom pada Galeri Foto
- Menambahkan fitur zoom pada galeri foto di quick view
- Menambahkan ikon zoom pada gambar utama
- Menambahkan efek hover pada gambar untuk menunjukkan bahwa gambar bisa di-zoom
- Menambahkan modal zoom untuk melihat gambar dalam ukuran penuh
- Gambar dapat di-zoom dengan mengklik gambar utama atau double-click pada thumbnail

### Perbaikan Tampilan Mobile
- Perbaikan tampilan quick view pada perangkat mobile
- Peningkatan layout dan responsivitas quick view
- Perbaikan padding dan margin untuk tampilan mobile
- Perbaikan ukuran font dan elemen untuk tampilan mobile
- Perbaikan scrolling pada quick view modal

## File yang Diubah
- `templates/quick-view-modal.php` - Menyesuaikan struktur HTML untuk quick view
- `templates/zoom-modal.php` - File baru untuk template zoom modal
- `includes/shortcodes.php` - Menambahkan zoom modal ke semua shortcode
- `assets/css/style.css` - Menambahkan CSS untuk zoom modal dan memperbaiki tampilan mobile
- `assets/js/script.js` - Menambahkan JavaScript untuk menangani zoom modal
- `rental-mobil.php` - Memperbarui versi plugin
- `README.md` - Memperbarui dokumentasi
- `CHANGELOG.md` - Memperbarui changelog
- `readme.txt` - Memperbarui informasi plugin untuk WordPress.org

## Cara Menggunakan
Fitur zoom pada galeri foto akan otomatis aktif setelah update. Pengguna dapat mengklik gambar utama atau double-click pada thumbnail untuk melihat gambar dalam ukuran penuh.

## Catatan Pengembang
- Fitur zoom menggunakan modal terpisah dengan z-index yang lebih tinggi
- Ikon zoom menggunakan font Dashicons yang sudah tersedia di WordPress
- Efek hover menggunakan CSS transform untuk memberikan indikasi visual
- Modal zoom responsif dan mobile-friendly

## Kompatibilitas
- WordPress 6.0+
- PHP 8.0+
- Semua tema WordPress, termasuk Divi
- Semua perangkat (desktop, tablet, mobile)

## Kontributor
- Angga Artupas (https://tupski.web.id)
