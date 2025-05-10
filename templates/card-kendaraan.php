<?php
/**
 * Template untuk Card Kendaraan
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

// Dapatkan data kendaraan
$post_id = get_the_ID();
$harga_sewa = rental_mobil_get_harga_sewa($post_id);
$harga_formatted = rental_mobil_format_rupiah($harga_sewa);

// Dapatkan terms
$merk_terms = get_the_terms($post_id, 'merk_kendaraan');
$merk = !empty($merk_terms) ? $merk_terms[0]->name : '';

$transmisi_terms = get_the_terms($post_id, 'transmisi');
$transmisi = !empty($transmisi_terms) ? $transmisi_terms[0]->name : '';

$bahan_bakar_terms = get_the_terms($post_id, 'bahan_bakar');
$bahan_bakar = !empty($bahan_bakar_terms) ? $bahan_bakar_terms[0]->name : '';
?>

<div class="rental-mobil-card" data-id="<?php echo esc_attr($post_id); ?>">
    <div class="rental-mobil-card-image">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium'); ?>
        <?php else : ?>
            <img src="<?php echo RENTAL_MOBIL_PLUGIN_URL; ?>assets/img/no-image.svg" alt="<?php the_title_attribute(); ?>">
        <?php endif; ?>
    </div>
    <div class="rental-mobil-card-content">
        <h3 class="rental-mobil-card-title"><?php the_title(); ?></h3>

        <div class="rental-mobil-card-meta">
            <?php if (!empty($merk)) : ?>
                <div class="rental-mobil-meta-item">
                    <span class="rental-mobil-meta-label"><?php _e('Merk:', 'rental-mobil-wp'); ?></span>
                    <span class="rental-mobil-meta-value"><?php echo esc_html($merk); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($transmisi)) : ?>
                <div class="rental-mobil-meta-item">
                    <span class="rental-mobil-meta-label"><?php _e('Transmisi:', 'rental-mobil-wp'); ?></span>
                    <span class="rental-mobil-meta-value"><?php echo esc_html($transmisi); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($bahan_bakar)) : ?>
                <div class="rental-mobil-meta-item">
                    <span class="rental-mobil-meta-label"><?php _e('Bahan Bakar:', 'rental-mobil-wp'); ?></span>
                    <span class="rental-mobil-meta-value"><?php echo esc_html($bahan_bakar); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="rental-mobil-card-price">
            <span class="rental-mobil-price-label"><?php _e('Mulai dari', 'rental-mobil-wp'); ?></span>
            <span class="rental-mobil-price-value"><?php echo esc_html($harga_formatted); ?> / <?php _e('hari', 'rental-mobil-wp'); ?></span>
        </div>

        <div class="rental-mobil-card-actions">
            <a href="<?php the_permalink(); ?>" class="rental-mobil-button rental-mobil-button-detail">
                <?php _e('Lihat Detail', 'rental-mobil-wp'); ?>
            </a>
            <button class="rental-mobil-button rental-mobil-button-booking" data-id="<?php echo esc_attr($post_id); ?>" data-title="<?php the_title_attribute(); ?>">
                <?php _e('Booking', 'rental-mobil-wp'); ?>
            </button>
        </div>
    </div>
</div>
