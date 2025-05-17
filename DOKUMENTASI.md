# Dokumentasi Rental Mobil WP

Plugin WordPress untuk rental mobil dengan fitur menampilkan daftar kendaraan, detail, dan booking.

## Daftar Isi

- [Instalasi](#instalasi)
- [Penggunaan Shortcode](#penggunaan-shortcode)
- [Pengaturan Plugin](#pengaturan-plugin)
- [Menambahkan Kendaraan](#menambahkan-kendaraan)
- [Filter dan Pencarian](#filter-dan-pencarian)
- [Form Booking](#form-booking)
- [Integrasi WhatsApp](#integrasi-whatsapp)
- [Fitur Berbagi (Share)](#fitur-berbagi-share)
- [FAQ](#faq)

## Instalasi

1. Unggah folder `rental-mobil-wp` ke direktori `/wp-content/plugins/`
2. Aktifkan plugin melalui menu 'Plugins' di WordPress
3. Buat halaman baru dan gunakan shortcode `[daftar_kendaraan]` untuk menampilkan daftar kendaraan

## Penggunaan Shortcode

### [daftar_kendaraan]

Menampilkan daftar kendaraan dengan filter di sidebar.

```
[daftar_kendaraan jumlah="10" orderby="date" order="DESC" merk="" transmisi="" bahan_bakar="" tipe="" tahun=""]
```

**Parameter:**
- `jumlah` - Jumlah kendaraan yang ditampilkan (default: 10)
- `orderby` - Mengurutkan berdasarkan (date, title, meta_value_num)
- `order` - Urutan (ASC, DESC)
- `merk` - Filter berdasarkan merk (slug)
- `transmisi` - Filter berdasarkan transmisi (slug)
- `bahan_bakar` - Filter berdasarkan bahan bakar (slug)
- `tipe` - Filter berdasarkan tipe kendaraan (slug)
- `tahun` - Filter berdasarkan tahun kendaraan (slug)

### [detail_kendaraan]

Menampilkan detail kendaraan berdasarkan ID atau slug.

```
[detail_kendaraan id="123" slug="nama-kendaraan"]
```

**Parameter:**
- `id` - ID kendaraan
- `slug` - Slug kendaraan

### [kendaraan_unggulan]

Menampilkan slider kendaraan unggulan.

```
[kendaraan_unggulan jumlah="5" judul="Kendaraan Unggulan" auto_slide="true" loop="true" speed="300" interval="5000"]
```

**Parameter:**
- `jumlah` - Jumlah kendaraan yang ditampilkan (default: 5)
- `judul` - Judul slider (default: Kendaraan Unggulan)
- `auto_slide` - Aktifkan auto slide (true/false, default: sesuai pengaturan)
- `loop` - Aktifkan loop slider (true/false, default: sesuai pengaturan)
- `speed` - Kecepatan transisi dalam milidetik (default: sesuai pengaturan)
- `interval` - Interval waktu antar slide dalam milidetik (default: sesuai pengaturan)

### [kendaraan_pilihan]

Menampilkan kendaraan pilihan yang telah Anda pilih di pengaturan.

```
[kendaraan_pilihan judul="Kendaraan Pilihan" jumlah="6"]
```

**Parameter:**
- `judul` - Judul section (default: "Kendaraan Pilihan")
- `jumlah` - Jumlah kendaraan yang ditampilkan (default: semua kendaraan pilihan)

## Pengaturan Plugin

### Tab Dokumentasi
Berisi dokumentasi singkat tentang penggunaan plugin dan shortcode.

### Tab WhatsApp
Mengatur nomor WhatsApp dan template pesan untuk booking kendaraan.

### Tab Tampilan
Mengatur warna, bentuk, dan tampilan elemen-elemen plugin seperti button dan card.

### Tab Filter
Mengatur opsi filter yang ditampilkan di frontend dan admin, serta pengaturan urutan daftar kendaraan.

### Tab Share
Mengatur platform share yang ditampilkan dan template pesan share.

### Tab Homepage
Mengatur kendaraan pilihan untuk homepage dan pengaturan slider kendaraan unggulan.

## Menambahkan Kendaraan

1. Buka menu "Rental Mobil WP" > "Tambah Kendaraan"
2. Isi informasi kendaraan seperti judul, deskripsi, harga, dan spesifikasi
3. Unggah gambar kendaraan
4. Pilih kategori, merk, transmisi, bahan bakar, dan tipe kendaraan
5. Klik "Terbitkan"

## Filter dan Pencarian

Plugin ini menyediakan filter berdasarkan:
- Merk Kendaraan
- Transmisi
- Bahan Bakar
- Tipe Kendaraan
- Tahun Kendaraan
- Urutan Berdasarkan
- Arah Urutan

## Form Booking

Form booking dapat dikustomisasi melalui Form Builder. Anda dapat menambahkan, mengedit, atau menghapus field sesuai kebutuhan.

## Integrasi WhatsApp

Plugin ini terintegrasi dengan WhatsApp untuk mengirim pesan booking. Anda dapat mengatur nomor WhatsApp dan template pesan di tab WhatsApp.

## Fitur Berbagi (Share)

Plugin ini menyediakan fitur berbagi ke berbagai platform seperti WhatsApp, Facebook, Twitter, Telegram, dan Email. Anda dapat mengatur platform yang ditampilkan dan template pesan di tab Share.

## FAQ

### Bagaimana cara menambahkan kendaraan?
Buka menu "Rental Mobil WP" > "Tambah Kendaraan" dan isi informasi kendaraan.

### Bagaimana cara menampilkan daftar kendaraan di halaman?
Gunakan shortcode `[daftar_kendaraan]` di halaman yang diinginkan.

### Bagaimana cara mengubah warna button?
Buka menu "Rental Mobil WP" > "Pengaturan" > tab "Tampilan" dan ubah warna button.

### Bagaimana cara mengubah template pesan WhatsApp?
Buka menu "Rental Mobil WP" > "Pengaturan" > tab "WhatsApp" dan ubah template pesan.

### Bagaimana cara menampilkan kendaraan unggulan di homepage?
Gunakan shortcode `[kendaraan_unggulan]` di halaman homepage.

### Bagaimana cara menampilkan kendaraan pilihan di homepage?
Pilih kendaraan di menu "Rental Mobil WP" > "Pengaturan" > tab "Homepage" dan gunakan shortcode `[kendaraan_pilihan]` di halaman homepage.
