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

// Dapatkan harga mingguan dan bulanan
$harga_mingguan = rental_mobil_get_harga_mingguan($post_id);
$harga_mingguan_formatted = !empty($harga_mingguan) ? rental_mobil_format_rupiah($harga_mingguan) : __('Hubungi Kami', 'rental-mobil-wp');
$harga_bulanan = rental_mobil_get_harga_bulanan($post_id);
$harga_bulanan_formatted = !empty($harga_bulanan) ? rental_mobil_format_rupiah($harga_bulanan) : __('Hubungi Kami', 'rental-mobil-wp');

// Cek status kendaraan
$is_featured = rental_mobil_is_featured($post_id);
$is_popular = rental_mobil_is_popular($post_id);

// Dapatkan terms
$merk_terms = get_the_terms($post_id, 'merk_kendaraan');
$merk = !empty($merk_terms) ? $merk_terms[0]->name : '';

$transmisi_terms = get_the_terms($post_id, 'transmisi');
$transmisi = !empty($transmisi_terms) ? $transmisi_terms[0]->name : '';

$bahan_bakar_terms = get_the_terms($post_id, 'bahan_bakar');
$bahan_bakar = !empty($bahan_bakar_terms) ? $bahan_bakar_terms[0]->name : '';

$tahun_terms = get_the_terms($post_id, 'tahun_kendaraan');
$tahun = !empty($tahun_terms) ? $tahun_terms[0]->name : '';

// Dapatkan galeri kendaraan
$galeri_images = rental_mobil_get_galeri($post_id);
?>

<div class="rental-mobil-card"
     data-id="<?php echo esc_attr($post_id); ?>"
     data-title="<?php the_title_attribute(); ?>"
     data-permalink="<?php the_permalink(); ?>"
     data-harga-harian="<?php echo esc_attr($harga_formatted); ?>"
     data-harga-mingguan="<?php echo esc_attr($harga_mingguan_formatted); ?>"
     data-harga-bulanan="<?php echo esc_attr($harga_bulanan_formatted); ?>"
     data-merk="<?php echo esc_attr($merk); ?>"
     data-transmisi="<?php echo esc_attr($transmisi); ?>"
     data-bahan-bakar="<?php echo esc_attr($bahan_bakar); ?>"
     data-tahun="<?php echo esc_attr($tahun); ?>"
     data-featured="<?php echo $is_featured ? '1' : '0'; ?>"
     data-popular="<?php echo $is_popular ? '1' : '0'; ?>"
     data-quick-view="1">
    <div class="rental-mobil-card-image rental-mobil-quick-view-trigger" data-id="<?php echo esc_attr($post_id); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium'); ?>
        <?php else : ?>
            <?php
            $no_image_id = attachment_url_to_postid(RENTAL_MOBIL_PLUGIN_URL . 'assets/img/no-image.svg');
            if ($no_image_id) {
                echo wp_get_attachment_image($no_image_id, 'medium', false, array('alt' => get_the_title()));
            } else {
                // Fallback jika gambar tidak terdaftar di media library
                echo '<img src="' . esc_url(RENTAL_MOBIL_PLUGIN_URL . 'assets/img/no-image.svg') . '" alt="' . esc_attr(get_the_title()) . '">';
            }
            ?>
        <?php endif; ?>

        <!-- Overlay dengan ikon mata saat hover -->
        <div class="rental-mobil-card-image-overlay">
            <span class="rental-mobil-card-image-icon dashicons dashicons-visibility"></span>
        </div>

        <?php if ($is_featured) : ?>
            <div class="rental-mobil-badge rental-mobil-badge-featured"><?php _e('Unggulan', 'rental-mobil-wp'); ?></div>
        <?php elseif ($is_popular) : ?>
            <div class="rental-mobil-badge rental-mobil-badge-popular"><?php _e('Paling Banyak Disewa', 'rental-mobil-wp'); ?></div>
        <?php endif; ?>
    </div>
    <div class="rental-mobil-card-content">
        <h3 class="rental-mobil-card-title rental-mobil-quick-view-trigger" data-id="<?php echo esc_attr($post_id); ?>"><?php the_title(); ?></h3>

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

            <?php if (!empty($tahun)) : ?>
                <div class="rental-mobil-meta-item">
                    <span class="rental-mobil-meta-label"><?php _e('Tahun:', 'rental-mobil-wp'); ?></span>
                    <span class="rental-mobil-meta-value"><?php echo esc_html($tahun); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="rental-mobil-card-price">
            <span class="rental-mobil-price-label"><?php _e('Mulai dari', 'rental-mobil-wp'); ?></span>
            <span class="rental-mobil-price-value"><?php echo esc_html($harga_formatted); ?> / <?php _e('hari', 'rental-mobil-wp'); ?></span>
        </div>

        <div class="rental-mobil-card-actions">
            <button class="rental-mobil-button rental-mobil-button-detail rental-mobil-quick-view-trigger" data-id="<?php echo esc_attr($post_id); ?>">
                <?php _e('Lihat Detail', 'rental-mobil-wp'); ?>
            </button>
            <button class="rental-mobil-button rental-mobil-button-booking" data-id="<?php echo esc_attr($post_id); ?>" data-title="<?php the_title_attribute(); ?>">
                <?php _e('Booking', 'rental-mobil-wp'); ?>
            </button>
        </div>
    </div>
</div>
