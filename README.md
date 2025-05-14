# Rental Mobil WP

Plugin WordPress untuk rental mobil dengan fitur menampilkan daftar kendaraan, detail, dan booking via WhatsApp.

> Plugin ini memerlukan lisensi untuk menggunakan semua fitur premium

## Deskripsi

Rental Mobil WP adalah plugin WordPress yang dirancang khusus untuk bisnis rental mobil. Plugin ini memungkinkan Anda menampilkan daftar kendaraan, detail kendaraan, dan sistem booking yang terintegrasi dengan WhatsApp.

### Fitur Utama

#### Fitur Gratis
* **Custom Post Type untuk Kendaraan** - Mengelola kendaraan dengan mudah
* **Tampilan Responsif** - Kompatibel dengan semua tema WordPress, termasuk Divi
* **Shortcode Dasar** - Tampilkan daftar kendaraan dan detail kendaraan di mana saja
* **Pengaturan Dasar** - Konfigurasi dasar plugin

#### Fitur Premium (Memerlukan Lisensi)
* **Filter Kendaraan** - Filter berdasarkan merk, transmisi, bahan bakar, tipe, dan tahun kendaraan
* **Booking via WhatsApp** - Form booking yang mengirim pesan langsung ke WhatsApp
* **Pengaturan Kustom** - Konfigurasi nomor WhatsApp dan template pesan
* **Harga Fleksibel** - Atur harga sewa harian, mingguan, dan bulanan
* **Floating Filter Button** - Tombol filter yang responsif untuk tampilan mobile
* **Quick View** - Fitur quick view untuk melihat detail kendaraan tanpa perlu membuka halaman baru
* **Sortir Berdasarkan Harga** - Urutkan kendaraan dari harga tertinggi ke terendah atau sebaliknya
* **Slider Kendaraan Unggulan** - Tampilkan kendaraan unggulan dengan slider yang dapat dikonfigurasi
* **Share Button** - Tombol berbagi ke WhatsApp, Facebook, Twitter, Telegram, dan Email
* **Pengaturan Filter Konfigurabel** - Atur filter yang ditampilkan di frontend dan admin

### Shortcode

Plugin ini menyediakan dua shortcode utama:

1. `[daftar_kendaraan]` - Menampilkan daftar kendaraan dengan filter
   * Parameter: `jumlah`, `merk`, `transmisi`, `bahan_bakar`, `tipe`, `tahun`, `orderby`, `order`
   * Contoh: `[daftar_kendaraan jumlah="6" tahun="2022" orderby="meta_value_num" order="ASC"]`

2. `[detail_kendaraan id="ID"]` - Menampilkan detail kendaraan
   * Parameter: `id` (ID kendaraan)
   * Contoh: `[detail_kendaraan id="123"]`

## Instalasi

1. Unggah folder `rental-mobil-wp` ke direktori `/wp-content/plugins/`
2. Aktifkan plugin melalui menu 'Plugins' di WordPress
3. Buka menu 'Rental Mobil' dan masuk ke tab 'Lisensi'
4. Masukkan kunci lisensi yang Anda dapatkan saat membeli plugin
5. Klik tombol 'Aktivasi Lisensi' untuk mengaktifkan fitur premium
6. Buka menu 'Rental Mobil' untuk mengatur nomor WhatsApp dan template pesan
7. Tambahkan kendaraan melalui menu 'Kendaraan'
8. Gunakan shortcode untuk menampilkan daftar kendaraan dan detail kendaraan

> **Catatan**: Tanpa lisensi yang valid, beberapa fitur premium tidak akan tersedia.

## Penggunaan

### Menambahkan Kendaraan

1. Buka menu 'Kendaraan' > 'Tambah Baru'
2. Isi judul, deskripsi, dan tambahkan gambar featured
3. Pilih atau tambahkan merk, transmisi, bahan bakar, dan tipe kendaraan
4. Isi harga sewa per hari, per minggu, dan per bulan
5. Klik 'Terbitkan'

### Menampilkan Daftar Kendaraan

Tambahkan shortcode `[daftar_kendaraan]` ke halaman atau post untuk menampilkan daftar kendaraan dengan filter.

### Menampilkan Detail Kendaraan

Tambahkan shortcode `[detail_kendaraan id="ID"]` ke halaman atau post untuk menampilkan detail kendaraan. Ganti "ID" dengan ID kendaraan yang ingin ditampilkan.

### Mengatur Nomor WhatsApp

1. Buka menu 'Rental Mobil'
2. Isi nomor WhatsApp dengan format internasional (contoh: 628123456789)
3. Sesuaikan template pesan jika diperlukan
4. Klik 'Simpan Pengaturan'

## FAQ

### Apakah plugin ini kompatibel dengan tema saya?

Ya, plugin ini dirancang untuk kompatibel dengan semua tema WordPress, termasuk Divi. Tampilan responsif memastikan plugin terlihat baik di semua perangkat.

### Bagaimana cara mendapatkan lisensi?

Anda dapat membeli lisensi melalui website resmi kami. Lisensi tersedia dalam beberapa paket dengan jumlah domain yang berbeda.

### Apa yang terjadi jika lisensi saya kedaluwarsa?

Jika lisensi Anda kedaluwarsa, fitur premium akan dinonaktifkan. Anda masih dapat menggunakan fitur dasar, tetapi fitur premium seperti filter kendaraan, booking via WhatsApp, dan quick view tidak akan tersedia.

### Bagaimana cara menambahkan filter kendaraan?

Filter kendaraan otomatis ditampilkan saat Anda menggunakan shortcode `[daftar_kendaraan]`. Anda dapat menambahkan merk, transmisi, bahan bakar, tipe, dan tahun kendaraan melalui menu 'Kendaraan'. Fitur ini memerlukan lisensi yang valid.

### Apakah saya bisa mengubah template pesan WhatsApp?

Ya, Anda dapat mengubah template pesan WhatsApp melalui menu 'Rental Mobil' di dashboard admin. Fitur ini memerlukan lisensi yang valid.

## Screenshots

1. Daftar Kendaraan
2. Detail Kendaraan
3. Form Booking
4. Pengaturan Plugin

## Changelog

### 1.4.9
* Perbaikan filter sticky yang tidak berfungsi
* Memperbaiki nama fungsi untuk menambahkan custom CSS
* Optimasi kode dan perbaikan bug

### 1.4.8
* Perbaikan tampilan harga mingguan dan bulanan pada card kendaraan unggulan
* Memastikan harga mingguan dan bulanan ditampilkan dengan benar jika sudah diisi
* Perbaikan konflik nama fungsi dengan plugin lain
* Optimasi kode dan perbaikan bug

### 1.4.7
* Perbaikan posisi modal agar tidak tertutup oleh menu sticky
* Memindahkan modal ke footer untuk meningkatkan kompatibilitas dengan tema
* Meningkatkan margin atas modal untuk tampilan yang lebih baik
* Perbaikan bug dan optimasi kode
* Menambahkan sistem lisensi untuk fitur premium

### 1.4.6
* Perbaikan paginasi agar berfungsi dengan benar
* Menambahkan parameter halaman ke URL untuk mempertahankan posisi halaman saat refresh
* Mengubah parameter paginasi menjadi 'halaman' untuk SEO yang lebih baik
* Perbaikan bug dan optimasi kode

### 1.4.5
* Menambahkan pengaturan sortir berdasarkan harga (mahal ke murah atau murah ke mahal)
* Menambahkan pengaturan kendaraan unggulan (auto slide, loop, speed, interval)
* Menambahkan parameter baru untuk shortcode kendaraan_unggulan
* Peningkatan performa slider kendaraan unggulan
* Perbaikan bug dan optimasi kode

### 1.4.4
* Menambahkan pengaturan posisi ikon filter di mobile (kanan bawah, kiri bawah, tengah kanan, tengah kiri)
* Memperbaiki padding/margin atas untuk modal agar tidak tertutup menu sticky
* Menyederhanakan tombol filter dengan tampilan yang lebih ringkas
* Perbaikan tampilan responsif untuk berbagai ukuran layar
* Optimasi CSS untuk performa yang lebih baik

### 1.4.3
* Perbaikan bug pada tampilan filter di perangkat mobile
* Peningkatan kompatibilitas dengan tema WordPress terbaru
* Optimasi performa dan perbaikan minor

### 1.4.2
* Perbaikan bug pada tampilan quick view
* Peningkatan kompatibilitas dengan WordPress 6.8
* Optimasi performa dan perbaikan minor

### 1.4.1
* Perbaikan tampilan quick view pada perangkat mobile
* Menambahkan fitur zoom pada galeri foto di quick view
* Menambahkan ikon zoom pada gambar utama
* Menambahkan efek hover pada gambar untuk menunjukkan bahwa gambar bisa di-zoom
* Perbaikan layout dan responsivitas quick view
* Menambahkan fitur pencarian dengan keyword menggunakan AJAX
* Menambahkan autocomplete pada kolom pencarian
* Mengubah layout menjadi 25/75 dengan filter di sebelah kiri
* Menambahkan tombol reset filter
* Menambahkan scroll lock pada sidebar filter
* Perbaikan tampilan filter yang lebih modern
* Perbaikan masalah pengaturan yang tidak tersimpan dengan benar

### 1.4.0
* Menambahkan fitur quick view untuk melihat detail kendaraan tanpa perlu membuka halaman baru
* Quick view menampilkan informasi penting seperti merk, transmisi, bahan bakar, dan tahun kendaraan
* Quick view menampilkan harga harian, mingguan, dan bulanan dengan fallback "Hubungi Kami" jika tidak diisi
* Quick view menampilkan galeri kendaraan dengan thumbnail yang bisa diklik
* Quick view responsif dan mobile-friendly
* Peningkatan pengalaman pengguna dengan memudahkan perbandingan kendaraan

### 1.3.2
* Peningkatan persyaratan versi WordPress menjadi 6.0
* Peningkatan persyaratan versi PHP menjadi 8.0
* Perbaikan kompatibilitas dengan PHP 8.0 dan WordPress 6.0+
* Optimasi performa dan keamanan

### 1.3.1
* Perbaikan penggunaan gambar dengan wp_get_attachment_image()
* Optimasi query database untuk performa yang lebih baik
* Pembaruan kompatibilitas dengan WordPress 6.8
* Perbaikan minor dan peningkatan kualitas kode

### 1.3.0
* Peningkatan tampilan detail kendaraan dengan layout yang lebih modern
* Menambahkan galeri kendaraan dengan integrasi Media Library WordPress
* Menambahkan modal galeri untuk melihat gambar dalam ukuran penuh
* Menambahkan floating booking button untuk tampilan mobile
* Menambahkan bagian kendaraan terkait berdasarkan tipe kendaraan
* Perbaikan tampilan harga dengan format kartu yang lebih menarik
* Peningkatan responsivitas untuk semua ukuran layar

### 1.2.0
* Menambahkan fitur harga sewa mingguan dan bulanan
* Menampilkan harga mingguan dan bulanan di halaman detail kendaraan
* Mengubah slug halaman detail dari /kendaraan/ menjadi /rental/
* Menambahkan floating filter button untuk tampilan mobile
* Perbaikan tampilan daftar kendaraan dengan layout fullwidth
* Perbaikan tampilan detail kendaraan untuk mobile

### 1.1.0
* Menambahkan fitur tahun kendaraan
* Menambahkan filter berdasarkan tahun kendaraan
* Perbaikan tampilan dan responsivitas

### 1.0.0
* Rilis pertama

## Upgrade Notice

### 1.4.9
Perbaikan filter sticky yang tidak berfungsi dan memperbaiki nama fungsi untuk menambahkan custom CSS.

### 1.4.8
Perbaikan tampilan harga mingguan dan bulanan pada card kendaraan unggulan dan perbaikan konflik nama fungsi dengan plugin lain.

### 1.4.7
Perbaikan posisi modal agar tidak tertutup oleh menu sticky, memindahkan modal ke footer untuk meningkatkan kompatibilitas dengan tema, dan menambahkan sistem lisensi untuk fitur premium.

### 1.4.6
Perbaikan paginasi agar berfungsi dengan benar dan menambahkan parameter halaman ke URL untuk mempertahankan posisi halaman saat refresh.

### 1.4.5
Menambahkan pengaturan sortir berdasarkan harga dan pengaturan kendaraan unggulan (auto slide, loop, speed, interval) untuk meningkatkan pengalaman pengguna.

### 1.4.4
Peningkatan UI dengan pengaturan posisi ikon filter yang dapat dikonfigurasi, perbaikan modal agar tidak tertutup menu sticky, dan tombol filter yang lebih ringkas.

### 1.4.3
Perbaikan bug pada tampilan filter di perangkat mobile dan peningkatan kompatibilitas dengan tema WordPress terbaru.

### 1.4.2
Perbaikan bug pada tampilan quick view dan peningkatan kompatibilitas dengan WordPress 6.8.

### 1.4.1
Perbaikan tampilan quick view pada perangkat mobile, menambahkan fitur zoom pada galeri foto, fitur pencarian dengan AJAX dan autocomplete, serta layout baru dengan filter di sebelah kiri.

### 1.4.0
Menambahkan fitur quick view untuk melihat detail kendaraan tanpa perlu membuka halaman baru, meningkatkan pengalaman pengguna dan memudahkan perbandingan kendaraan.

### 1.3.1
Perbaikan penting untuk kompatibilitas dengan WordPress 6.8, optimasi performa, dan peningkatan kualitas kode.

### 1.3.0
Peningkatan tampilan detail kendaraan, galeri dengan Media Library, floating booking button, dan kendaraan terkait.

### 1.2.0
Menambahkan fitur harga sewa mingguan dan bulanan, perbaikan tampilan mobile, dan mengubah slug halaman detail.

### 1.1.0
Menambahkan fitur tahun kendaraan dan filter berdasarkan tahun kendaraan.

### 1.0.0
Rilis pertama

## Lisensi

Plugin ini memerlukan lisensi untuk menggunakan semua fitur premium. Kode sumber plugin dilisensikan di bawah [GPL v2 atau yang lebih baru](https://www.gnu.org/licenses/gpl-2.0.html), tetapi fitur premium hanya tersedia dengan lisensi yang valid.

## Dukungan

Jika Anda memiliki pertanyaan atau membutuhkan bantuan, silakan hubungi kami melalui email dukungan yang disediakan saat pembelian lisensi.
