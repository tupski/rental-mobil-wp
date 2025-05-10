<?php
/**
 * Template untuk Detail Kendaraan
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

// Dapatkan data kendaraan
$post_id = $kendaraan->ID;
$harga_sewa = rental_mobil_get_harga_sewa($post_id);
$harga_formatted = rental_mobil_format_rupiah($harga_sewa);

// Dapatkan galeri dan video
$galeri_images = rental_mobil_get_galeri($post_id);
$youtube_url = rental_mobil_get_youtube_url($post_id);
$youtube_id = rental_mobil_get_youtube_id($youtube_url);

// Dapatkan terms
$merk_terms = get_the_terms($post_id, 'merk_kendaraan');
$merk = !empty($merk_terms) ? $merk_terms[0]->name : '';

$transmisi_terms = get_the_terms($post_id, 'transmisi');
$transmisi = !empty($transmisi_terms) ? $transmisi_terms[0]->name : '';

$bahan_bakar_terms = get_the_terms($post_id, 'bahan_bakar');
$bahan_bakar = !empty($bahan_bakar_terms) ? $bahan_bakar_terms[0]->name : '';

$tipe_terms = get_the_terms($post_id, 'tipe_kendaraan');
$tipe = !empty($tipe_terms) ? $tipe_terms[0]->name : '';

$tahun_terms = get_the_terms($post_id, 'tahun_kendaraan');
$tahun = !empty($tahun_terms) ? $tahun_terms[0]->name : '';

// Dapatkan pengaturan WhatsApp
$whatsapp_number = rental_mobil_get_whatsapp_number();
$whatsapp_message = rental_mobil_get_whatsapp_message();
?>

<div class="rental-mobil-container">
<div class="rental-mobil-detail">
    <div class="rental-mobil-detail-header">
        <h1 class="rental-mobil-detail-title"><?php echo esc_html($kendaraan->post_title); ?></h1>

        <?php if (!empty($merk)) : ?>
            <div class="rental-mobil-detail-merk">
                <span class="rental-mobil-detail-merk-label"><?php _e('Merk:', 'rental-mobil-wp'); ?></span>
                <span class="rental-mobil-detail-merk-value"><?php echo esc_html($merk); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="rental-mobil-detail-content">
        <div class="rental-mobil-detail-media">
            <!-- Featured Image -->
            <div class="rental-mobil-detail-gallery">
                <?php if (has_post_thumbnail($post_id)) : ?>
                    <div class="rental-mobil-detail-featured-image">
                        <?php echo get_the_post_thumbnail($post_id, 'large'); ?>
                    </div>
                <?php else : ?>
                    <div class="rental-mobil-detail-featured-image">
                        <img src="<?php echo RENTAL_MOBIL_PLUGIN_URL; ?>assets/img/no-image.svg" alt="<?php echo esc_attr($kendaraan->post_title); ?>">
                    </div>
                <?php endif; ?>

                <!-- Galeri Kendaraan -->
                <?php if (!empty($galeri_images)) : ?>
                    <div class="rental-mobil-detail-gallery-thumbnails">
                        <?php foreach ($galeri_images as $image_url) : ?>
                            <div class="rental-mobil-detail-gallery-thumbnail">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($kendaraan->post_title); ?>" class="rental-mobil-gallery-image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Video YouTube -->
            <?php if (!empty($youtube_id)) : ?>
                <div class="rental-mobil-detail-video">
                    <h3 class="rental-mobil-detail-video-title"><?php _e('Video Kendaraan', 'rental-mobil-wp'); ?></h3>
                    <div class="rental-mobil-detail-video-container">
                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?php echo esc_attr($youtube_id); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="rental-mobil-detail-info">
            <div class="rental-mobil-detail-price">
                <span class="rental-mobil-detail-price-label"><?php _e('Harga Sewa:', 'rental-mobil-wp'); ?></span>
                <span class="rental-mobil-detail-price-value"><?php echo esc_html($harga_formatted); ?> / <?php _e('hari', 'rental-mobil-wp'); ?></span>
            </div>

            <div class="rental-mobil-detail-specs">
                <h3 class="rental-mobil-detail-specs-title"><?php _e('Spesifikasi', 'rental-mobil-wp'); ?></h3>

                <div class="rental-mobil-detail-specs-grid">
                    <?php if (!empty($transmisi)) : ?>
                        <div class="rental-mobil-detail-spec-item">
                            <span class="rental-mobil-detail-spec-label"><?php _e('Transmisi', 'rental-mobil-wp'); ?></span>
                            <span class="rental-mobil-detail-spec-value"><?php echo esc_html($transmisi); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($bahan_bakar)) : ?>
                        <div class="rental-mobil-detail-spec-item">
                            <span class="rental-mobil-detail-spec-label"><?php _e('Bahan Bakar', 'rental-mobil-wp'); ?></span>
                            <span class="rental-mobil-detail-spec-value"><?php echo esc_html($bahan_bakar); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($tipe)) : ?>
                        <div class="rental-mobil-detail-spec-item">
                            <span class="rental-mobil-detail-spec-label"><?php _e('Tipe Kendaraan', 'rental-mobil-wp'); ?></span>
                            <span class="rental-mobil-detail-spec-value"><?php echo esc_html($tipe); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($tahun)) : ?>
                        <div class="rental-mobil-detail-spec-item">
                            <span class="rental-mobil-detail-spec-label"><?php _e('Tahun', 'rental-mobil-wp'); ?></span>
                            <span class="rental-mobil-detail-spec-value"><?php echo esc_html($tahun); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rental-mobil-detail-description">
                <h3 class="rental-mobil-detail-description-title"><?php _e('Deskripsi', 'rental-mobil-wp'); ?></h3>
                <div class="rental-mobil-detail-description-content">
                    <?php echo wpautop($kendaraan->post_content); ?>
                </div>
            </div>

            <div class="rental-mobil-detail-actions">
                <button class="rental-mobil-button rental-mobil-button-booking" data-id="<?php echo esc_attr($post_id); ?>" data-title="<?php echo esc_attr($kendaraan->post_title); ?>">
                    <?php _e('Booking via WhatsApp', 'rental-mobil-wp'); ?>
                </button>
            </div>

            <!-- Form Booking Langsung -->
            <div class="rental-mobil-detail-booking-form">
                <h3 class="rental-mobil-detail-booking-title"><?php _e('Form Booking', 'rental-mobil-wp'); ?></h3>

                <form id="rental-mobil-inline-booking-form" class="rental-mobil-booking-form">
                    <input type="hidden" id="rental-mobil-inline-booking-kendaraan-id" name="kendaraan_id" value="<?php echo esc_attr($post_id); ?>">
                    <input type="hidden" id="rental-mobil-inline-booking-kendaraan-title" name="kendaraan_title" value="<?php echo esc_attr($kendaraan->post_title); ?>">

                    <div class="rental-mobil-form-group">
                        <label for="rental-mobil-inline-booking-nama"><?php _e('Nama', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                        <input type="text" id="rental-mobil-inline-booking-nama" name="nama" required>
                    </div>

                    <div class="rental-mobil-form-group">
                        <label for="rental-mobil-inline-booking-domisili"><?php _e('Domisili', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                        <input type="text" id="rental-mobil-inline-booking-domisili" name="domisili" required>
                    </div>

                    <div class="rental-mobil-form-row">
                        <div class="rental-mobil-form-group rental-mobil-form-col">
                            <label for="rental-mobil-inline-booking-tanggal"><?php _e('Tanggal Sewa', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                            <input type="date" id="rental-mobil-inline-booking-tanggal" name="tanggal_sewa" required>
                        </div>

                        <div class="rental-mobil-form-group rental-mobil-form-col">
                            <label for="rental-mobil-inline-booking-jam"><?php _e('Jam Sewa', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                            <input type="time" id="rental-mobil-inline-booking-jam" name="jam_sewa" required>
                        </div>
                    </div>

                    <div class="rental-mobil-form-row">
                        <div class="rental-mobil-form-group rental-mobil-form-col">
                            <label for="rental-mobil-inline-booking-durasi"><?php _e('Durasi Sewa', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                            <input type="number" id="rental-mobil-inline-booking-durasi" name="durasi_sewa" min="1" value="1" required>
                        </div>

                        <div class="rental-mobil-form-group rental-mobil-form-col">
                            <label for="rental-mobil-inline-booking-satuan"><?php _e('Satuan Durasi', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                            <select id="rental-mobil-inline-booking-satuan" name="satuan_durasi" required>
                                <option value="hari"><?php _e('Hari', 'rental-mobil-wp'); ?></option>
                                <option value="minggu"><?php _e('Minggu', 'rental-mobil-wp'); ?></option>
                                <option value="bulan"><?php _e('Bulan', 'rental-mobil-wp'); ?></option>
                                <option value="tahun"><?php _e('Tahun', 'rental-mobil-wp'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="rental-mobil-form-actions">
                        <button type="submit" class="rental-mobil-button rental-mobil-button-submit">
                            <?php _e('Booking Sekarang', 'rental-mobil-wp'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
