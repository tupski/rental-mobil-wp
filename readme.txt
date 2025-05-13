=== Rental Mobil WP ===
Contributors: artupski
Donate link: https://trakteer.id/tupski/tip
Tags: car rental, rental, booking, whatsapp, mobil
Requires at least: 6.0
Tested up to: 6.8
Stable tag: 1.4.4
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin WordPress untuk rental mobil dengan fitur menampilkan daftar kendaraan, detail, dan booking via WhatsApp.

== Description ==

Rental Mobil WP adalah plugin WordPress yang dirancang khusus untuk bisnis rental mobil. Plugin ini memungkinkan Anda menampilkan daftar kendaraan, detail kendaraan, dan sistem booking yang terintegrasi dengan WhatsApp.

= Fitur Utama =

* **Custom Post Type untuk Kendaraan** - Mengelola kendaraan dengan mudah
* **Tampilan Responsif** - Kompatibel dengan semua tema WordPress, termasuk Divi
* **Filter Kendaraan** - Filter berdasarkan merk, transmisi, bahan bakar, tipe, dan tahun kendaraan
* **Shortcode** - Tampilkan daftar kendaraan dan detail kendaraan di mana saja
* **Booking via WhatsApp** - Form booking yang mengirim pesan langsung ke WhatsApp
* **Pengaturan Kustom** - Konfigurasi nomor WhatsApp dan template pesan
* **Harga Fleksibel** - Atur harga sewa harian, mingguan, dan bulanan
* **Floating Filter Button** - Tombol filter yang responsif untuk tampilan mobile
* **Quick View** - Fitur quick view untuk melihat detail kendaraan tanpa perlu membuka halaman baru

= Shortcode =

Plugin ini menyediakan dua shortcode utama:

1. `[daftar_kendaraan]` - Menampilkan daftar kendaraan dengan filter
   * Parameter: `jumlah`, `merk`, `transmisi`, `bahan_bakar`, `tipe`, `tahun`, `orderby`, `order`
   * Contoh: `[daftar_kendaraan jumlah="6" tahun="2022" orderby="meta_value_num" order="ASC"]`

2. `[detail_kendaraan id="ID"]` - Menampilkan detail kendaraan
   * Parameter: `id` (ID kendaraan)
   * Contoh: `[detail_kendaraan id="123"]`

= Tampilan Responsif =

Plugin ini dirancang dengan tampilan responsif yang kompatibel dengan semua tema WordPress, termasuk Divi. Tampilan grid untuk daftar kendaraan dan tampilan detail untuk halaman kendaraan memastikan pengalaman pengguna yang optimal di semua perangkat.

= Integrasi WhatsApp =

Form booking terintegrasi dengan WhatsApp, memungkinkan pengunjung untuk mengirim pesan booking langsung ke nomor WhatsApp Anda. Template pesan dapat dikonfigurasi melalui pengaturan plugin.

= Dukungan Multilingual =

Plugin ini siap untuk diterjemahkan ke berbagai bahasa. File `.pot` tersedia untuk memudahkan penerjemahan.

== Installation ==

1. Unggah folder `rental-mobil-wp` ke direktori `/wp-content/plugins/`
2. Aktifkan plugin melalui menu 'Plugins' di WordPress
3. Buka menu 'Rental Mobil' untuk mengatur nomor WhatsApp dan template pesan
4. Tambahkan kendaraan melalui menu 'Kendaraan'
5. Gunakan shortcode untuk menampilkan daftar kendaraan dan detail kendaraan

== Frequently Asked Questions ==

= Apakah plugin ini kompatibel dengan tema saya? =
Ya, plugin ini dirancang untuk kompatibel dengan semua tema WordPress, termasuk Divi. Tampilan responsif memastikan plugin terlihat baik di semua perangkat.

= Bagaimana cara menambahkan filter kendaraan? =
Filter kendaraan otomatis ditampilkan saat Anda menggunakan shortcode `[daftar_kendaraan]`. Anda dapat menambahkan merk, transmisi, bahan bakar, dan tipe kendaraan melalui menu 'Kendaraan'.

= Apakah saya bisa mengubah template pesan WhatsApp? =
Ya, Anda dapat mengubah template pesan WhatsApp melalui menu 'Rental Mobil' di dashboard admin.

= Apakah plugin ini mendukung pembayaran online? =
Tidak, plugin ini hanya menyediakan form booking yang mengirim pesan ke WhatsApp. Untuk pembayaran online, Anda perlu mengintegrasikan dengan plugin pembayaran terpisah.

= Bisakah saya menambahkan field kustom lainnya? =
Ya, Anda dapat menambahkan field kustom lainnya dengan memodifikasi file `includes/meta-boxes.php`.

== Screenshots ==

1. Daftar Kendaraan
2. Detail Kendaraan
3. Form Booking
4. Pengaturan Plugin

== Changelog ==

= 1.4.4 =
* Menambahkan pengaturan posisi ikon filter di mobile (kanan bawah, kiri bawah, tengah kanan, tengah kiri)
* Memperbaiki padding/margin atas untuk modal agar tidak tertutup menu sticky
* Menyederhanakan tombol filter dengan tampilan yang lebih ringkas
* Perbaikan tampilan responsif untuk berbagai ukuran layar
* Optimasi CSS untuk performa yang lebih baik

= 1.4.3 =
* Perbaikan bug pada tampilan filter di perangkat mobile
* Peningkatan kompatibilitas dengan tema WordPress terbaru
* Optimasi performa dan perbaikan minor

= 1.4.2 =
* Perbaikan bug pada tampilan quick view
* Peningkatan kompatibilitas dengan WordPress 6.8
* Optimasi performa dan perbaikan minor

= 1.4.1 =
* Perbaikan tampilan quick view pada perangkat mobile
* Menambahkan fitur zoom pada galeri foto di quick view
* Menambahkan ikon zoom pada gambar utama
* Menambahkan efek hover pada gambar untuk menunjukkan bahwa gambar bisa di-zoom
* Menambahkan modal zoom untuk melihat gambar dalam ukuran penuh
* Perbaikan layout dan responsivitas quick view
* Menambahkan fitur pencarian dengan keyword menggunakan AJAX
* Menambahkan autocomplete pada kolom pencarian
* Mengubah layout menjadi 25/75 dengan filter di sebelah kiri
* Menambahkan tombol reset filter
* Menambahkan scroll lock pada sidebar filter
* Perbaikan tampilan filter yang lebih modern
* Perbaikan masalah pengaturan yang tidak tersimpan dengan benar

= 1.4.0 =
* Menambahkan fitur quick view untuk melihat detail kendaraan tanpa perlu membuka halaman baru
* Quick view menampilkan informasi penting seperti merk, transmisi, bahan bakar, dan tahun kendaraan
* Quick view menampilkan harga harian, mingguan, dan bulanan dengan fallback "Hubungi Kami" jika tidak diisi
* Quick view menampilkan galeri kendaraan dengan thumbnail yang bisa diklik
* Quick view responsif dan mobile-friendly
* Peningkatan pengalaman pengguna dengan memudahkan perbandingan kendaraan

= 1.3.2 =
* Peningkatan persyaratan versi WordPress menjadi 6.0
* Peningkatan persyaratan versi PHP menjadi 8.0
* Perbaikan kompatibilitas dengan PHP 8.0 dan WordPress 6.0+
* Optimasi performa dan keamanan

= 1.3.1 =
* Perbaikan penggunaan gambar dengan wp_get_attachment_image()
* Optimasi query database untuk performa yang lebih baik
* Pembaruan kompatibilitas dengan WordPress 6.8
* Perbaikan minor dan peningkatan kualitas kode

= 1.3.0 =
* Peningkatan tampilan detail kendaraan dengan layout yang lebih modern
* Menambahkan galeri kendaraan dengan integrasi Media Library WordPress
* Menambahkan modal galeri untuk melihat gambar dalam ukuran penuh
* Menambahkan floating booking button untuk tampilan mobile
* Menambahkan bagian kendaraan terkait berdasarkan tipe kendaraan
* Perbaikan tampilan harga dengan format kartu yang lebih menarik
* Peningkatan responsivitas untuk semua ukuran layar

= 1.2.0 =
* Menambahkan fitur harga sewa mingguan dan bulanan
* Menampilkan harga mingguan dan bulanan di halaman detail kendaraan
* Mengubah slug halaman detail dari /kendaraan/ menjadi /rental/
* Menambahkan floating filter button untuk tampilan mobile
* Perbaikan tampilan daftar kendaraan dengan layout fullwidth
* Perbaikan tampilan detail kendaraan untuk mobile

= 1.1.0 =
* Menambahkan fitur tahun kendaraan
* Menambahkan filter berdasarkan tahun kendaraan
* Perbaikan tampilan dan responsivitas

= 1.0.0 =
* Rilis pertama

== Upgrade Notice ==

= 1.4.4 =
Peningkatan UI dengan pengaturan posisi ikon filter yang dapat dikonfigurasi, perbaikan modal agar tidak tertutup menu sticky, dan tombol filter yang lebih ringkas.

= 1.4.3 =
Perbaikan bug pada tampilan filter di perangkat mobile dan peningkatan kompatibilitas dengan tema WordPress terbaru.

= 1.4.2 =
Perbaikan bug pada tampilan quick view dan peningkatan kompatibilitas dengan WordPress 6.8.

= 1.4.1 =
Perbaikan tampilan quick view pada perangkat mobile, menambahkan fitur zoom pada galeri foto, fitur pencarian dengan AJAX dan autocomplete, serta layout baru dengan filter di sebelah kiri.

= 1.4.0 =
Menambahkan fitur quick view untuk melihat detail kendaraan tanpa perlu membuka halaman baru, meningkatkan pengalaman pengguna dan memudahkan perbandingan kendaraan.

= 1.3.2 =
Peningkatan persyaratan versi WordPress menjadi 6.0 dan PHP menjadi 8.0. Perbaikan kompatibilitas, optimasi performa, dan peningkatan keamanan.

= 1.3.1 =
Perbaikan penting untuk kompatibilitas dengan WordPress 6.8, optimasi performa, dan peningkatan kualitas kode.

= 1.3.0 =
Peningkatan tampilan detail kendaraan, galeri dengan Media Library, floating booking button, dan kendaraan terkait.

= 1.2.0 =
Menambahkan fitur harga sewa mingguan dan bulanan, perbaikan tampilan mobile, dan mengubah slug halaman detail.

= 1.1.0 =
Menambahkan fitur tahun kendaraan dan filter berdasarkan tahun kendaraan.

= 1.0.0 =
Rilis pertama

== Arbitrary section ==

Plugin ini dikembangkan dengan pendekatan modular agar mudah dikustomisasi dan dikembangkan lebih lanjut. Cocok untuk bisnis rental mobil yang menginginkan sistem sederhana namun efektif untuk mengelola armada kendaraan dan memudahkan calon pelanggan melakukan pemesanan.

Kontribusi, saran, dan laporan bug sangat dihargai. Silakan kunjungi halaman [Trakteer](https://trakteer.id/tupski/tip) untuk traktir saya kopi jika Anda terbantu dengan plugin ini.

Jika Anda adalah pengembang dan ingin memperluas fitur plugin, Anda dapat memodifikasi file di folder `includes/` untuk menambahkan meta box, shortcode, atau logika tambahan lainnya sesuai kebutuhan proyek Anda.
