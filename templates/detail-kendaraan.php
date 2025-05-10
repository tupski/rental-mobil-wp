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

// Dapatkan terms
$merk_terms = get_the_terms($post_id, 'merk_kendaraan');
$merk = !empty($merk_terms) ? $merk_terms[0]->name : '';

$transmisi_terms = get_the_terms($post_id, 'transmisi');
$transmisi = !empty($transmisi_terms) ? $transmisi_terms[0]->name : '';

$bahan_bakar_terms = get_the_terms($post_id, 'bahan_bakar');
$bahan_bakar = !empty($bahan_bakar_terms) ? $bahan_bakar_terms[0]->name : '';

$tipe_terms = get_the_terms($post_id, 'tipe_kendaraan');
$tipe = !empty($tipe_terms) ? $tipe_terms[0]->name : '';
?>

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
                    <?php _e('Booking Sekarang', 'rental-mobil-wp'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
