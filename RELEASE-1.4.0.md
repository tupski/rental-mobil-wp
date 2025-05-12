# Rental Mobil WP - Versi 1.4.0

## Fitur Baru

### Quick View
- Menambahkan fitur quick view untuk melihat detail kendaraan tanpa perlu membuka halaman baru
- Quick view dapat diakses dengan mengklik foto kendaraan, judul kendaraan, atau tombol "Lihat Detail"
- Quick view menampilkan informasi penting seperti merk, transmisi, bahan bakar, dan tahun kendaraan
- Quick view menampilkan harga harian, mingguan, dan bulanan dengan fallback "Hubungi Kami" jika tidak diisi
- Quick view menampilkan galeri kendaraan dengan thumbnail yang bisa diklik
- Quick view responsif dan mobile-friendly
- Peningkatan pengalaman pengguna dengan memudahkan perbandingan kendaraan

## File yang Diubah
- `templates/card-kendaraan.php` - Menambahkan atribut data dan trigger untuk quick view
- `templates/quick-view-modal.php` - File baru untuk template quick view modal
- `includes/shortcodes.php` - Menambahkan AJAX handler untuk galeri kendaraan dan menyertakan template quick view modal
- `assets/css/style.css` - Menambahkan CSS untuk styling quick view modal
- `assets/js/script.js` - Menambahkan JavaScript untuk menangani quick view
- `rental-mobil.php` - Memperbarui versi plugin
- `README.md` - Memperbarui dokumentasi

## Cara Menggunakan
Fitur quick view akan otomatis aktif setelah update. Pengguna dapat mengklik foto kendaraan, judul kendaraan, atau tombol "Lihat Detail" untuk melihat quick view.

## Catatan Pengembang
- Fitur quick view menggunakan AJAX untuk memuat galeri kendaraan secara dinamis
- Quick view modal menggunakan CSS Grid untuk layout yang responsif
- Thumbnail galeri menggunakan event delegation untuk menangani klik
- Harga mingguan dan bulanan ditampilkan dengan fallback "Hubungi Kami" jika tidak diisi

## Kompatibilitas
- WordPress 6.0+
- PHP 8.0+
- Semua tema WordPress, termasuk Divi
- Semua perangkat (desktop, tablet, mobile)

## Kontributor
- Angga Artupas (https://tupski.web.id)
