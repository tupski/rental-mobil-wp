=== Rental Mobil WP ===
Contributors: artupski  
Donate link: https://tupski.web.id/donate  
Tags: car rental, rental, booking, whatsapp, mobil, kendaraan  
Requires at least: 5.0  
Tested up to: 6.4  
Stable tag: 1.1.0  
Requires PHP: 7.2  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

Plugin WordPress untuk rental mobil dengan fitur menampilkan daftar kendaraan, detail, dan booking via WhatsApp.

== Description ==

Rental Mobil WP adalah plugin WordPress yang dirancang khusus untuk bisnis rental mobil. Plugin ini memungkinkan Anda menampilkan daftar kendaraan, detail kendaraan, dan sistem booking yang terintegrasi dengan WhatsApp.

= Fitur Utama =

* **Custom Post Type untuk Kendaraan** - Mengelola kendaraan dengan mudah
* **Tampilan Responsif** - Kompatibel dengan semua tema WordPress, termasuk Divi
* **Filter Kendaraan** - Filter berdasarkan merk, transmisi, bahan bakar, dan tipe kendaraan
* **Shortcode** - Tampilkan daftar kendaraan dan detail kendaraan di mana saja
* **Booking via WhatsApp** - Form booking yang mengirim pesan langsung ke WhatsApp
* **Pengaturan Kustom** - Konfigurasi nomor WhatsApp dan template pesan

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

= 1.1.0 =
* Menambahkan fitur tahun kendaraan
* Menambahkan filter berdasarkan tahun kendaraan
* Perbaikan tampilan dan responsivitas

= 1.0.0 =
* Rilis pertama

== Upgrade Notice ==

= 1.1.0 =
Menambahkan fitur tahun kendaraan dan filter berdasarkan tahun kendaraan.

= 1.0.0 =
Rilis pertama

== Arbitrary section ==

Plugin ini dikembangkan dengan pendekatan modular agar mudah dikustomisasi dan dikembangkan lebih lanjut. Cocok untuk bisnis rental mobil yang menginginkan sistem sederhana namun efektif untuk mengelola armada kendaraan dan memudahkan calon pelanggan melakukan pemesanan.

Kontribusi, saran, dan laporan bug sangat dihargai. Silakan kunjungi halaman [donasi dan dukungan](https://tupski.web.id/donate) untuk membantu pengembangan plugin ini ke versi yang lebih baik lagi.

Jika Anda adalah pengembang dan ingin memperluas fitur plugin, Anda dapat memodifikasi file di folder `includes/` untuk menambahkan meta box, shortcode, atau logika tambahan lainnya sesuai kebutuhan proyek Anda.
