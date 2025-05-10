# Rental Mobil WP

Plugin WordPress untuk rental mobil dengan fitur menampilkan daftar kendaraan, detail, dan booking via WhatsApp.

## Deskripsi

Rental Mobil WP adalah plugin WordPress yang dirancang khusus untuk bisnis rental mobil. Plugin ini memungkinkan Anda menampilkan daftar kendaraan, detail kendaraan, dan sistem booking yang terintegrasi dengan WhatsApp.

### Fitur Utama

* **Custom Post Type untuk Kendaraan** - Mengelola kendaraan dengan mudah
* **Tampilan Responsif** - Kompatibel dengan semua tema WordPress, termasuk Divi
* **Filter Kendaraan** - Filter berdasarkan merk, transmisi, bahan bakar, tipe, dan tahun kendaraan
* **Shortcode** - Tampilkan daftar kendaraan dan detail kendaraan di mana saja
* **Booking via WhatsApp** - Form booking yang mengirim pesan langsung ke WhatsApp
* **Pengaturan Kustom** - Konfigurasi nomor WhatsApp dan template pesan
* **Harga Fleksibel** - Atur harga sewa harian, mingguan, dan bulanan
* **Floating Filter Button** - Tombol filter yang responsif untuk tampilan mobile

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
3. Buka menu 'Rental Mobil' untuk mengatur nomor WhatsApp dan template pesan
4. Tambahkan kendaraan melalui menu 'Kendaraan'
5. Gunakan shortcode untuk menampilkan daftar kendaraan dan detail kendaraan

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

### Bagaimana cara menambahkan filter kendaraan?

Filter kendaraan otomatis ditampilkan saat Anda menggunakan shortcode `[daftar_kendaraan]`. Anda dapat menambahkan merk, transmisi, bahan bakar, tipe, dan tahun kendaraan melalui menu 'Kendaraan'.

### Apakah saya bisa mengubah template pesan WhatsApp?

Ya, Anda dapat mengubah template pesan WhatsApp melalui menu 'Rental Mobil' di dashboard admin.

## Screenshots

1. Daftar Kendaraan
2. Detail Kendaraan
3. Form Booking
4. Pengaturan Plugin

## Changelog

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

### 1.2.0
Menambahkan fitur harga sewa mingguan dan bulanan, perbaikan tampilan mobile, dan mengubah slug halaman detail.

### 1.1.0
Menambahkan fitur tahun kendaraan dan filter berdasarkan tahun kendaraan.

### 1.0.0
Rilis pertama

## Kontribusi

Kontribusi sangat diterima! Jika Anda ingin berkontribusi, silakan fork repository dan buat pull request.

## License

Plugin ini dilisensikan di bawah [GPL v2 atau yang lebih baru](https://www.gnu.org/licenses/gpl-2.0.html).
