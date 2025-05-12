<?php
/**
 * Template untuk Slider Kendaraan
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

// Dapatkan parameter
$type = isset($type) ? $type : 'featured'; // 'featured' atau 'popular'
$limit = isset($limit) ? intval($limit) : 5;
$title = isset($title) ? $title : ($type === 'featured' ? __('Kendaraan Unggulan', 'rental-mobil-wp') : __('Paling Banyak Disewa', 'rental-mobil-wp'));

// Query kendaraan berdasarkan tipe
if ($type === 'featured') {
    $query = rental_mobil_get_featured_vehicles($limit);
} else {
    $query = rental_mobil_get_popular_vehicles($limit);
}

// Jika tidak ada kendaraan, keluar
if (!$query->have_posts()) {
    return;
}
?>

<div class="rental-mobil-slider-section">
    <h2 class="rental-mobil-slider-title"><?php echo esc_html($title); ?></h2>

    <div class="rental-mobil-slider">
        <div class="rental-mobil-slider-container">
            <?php while ($query->have_posts()) : $query->the_post();
                $post_id = get_the_ID();
                $harga_sewa = rental_mobil_get_harga_sewa($post_id);
                $harga_formatted = rental_mobil_format_rupiah($harga_sewa);

                // Dapatkan terms
                $merk_terms = get_the_terms($post_id, 'merk_kendaraan');
                $merk = !empty($merk_terms) ? $merk_terms[0]->name : '';

                $transmisi_terms = get_the_terms($post_id, 'transmisi');
                $transmisi = !empty($transmisi_terms) ? $transmisi_terms[0]->name : '';

                $tahun_terms = get_the_terms($post_id, 'tahun_kendaraan');
                $tahun = !empty($tahun_terms) ? $tahun_terms[0]->name : '';
            ?>
                <div class="rental-mobil-slider-item">
                    <div class="rental-mobil-card"
                         data-id="<?php echo esc_attr($post_id); ?>"
                         data-title="<?php the_title_attribute(); ?>"
                         data-permalink="<?php the_permalink(); ?>"
                         data-harga-harian="<?php echo esc_attr($harga_formatted); ?>"
                         data-harga-mingguan="<?php echo isset($harga_mingguan) ? esc_attr(rental_mobil_format_rupiah($harga_mingguan)) : __('Hubungi Kami', 'rental-mobil-wp'); ?>"
                         data-harga-bulanan="<?php echo isset($harga_bulanan) ? esc_attr(rental_mobil_format_rupiah($harga_bulanan)) : __('Hubungi Kami', 'rental-mobil-wp'); ?>"
                         data-merk="<?php echo esc_attr($merk); ?>"
                         data-transmisi="<?php echo esc_attr($transmisi); ?>"
                         data-bahan-bakar="<?php echo isset($bahan_bakar) ? esc_attr($bahan_bakar) : ''; ?>"
                         data-tahun="<?php echo esc_attr($tahun); ?>"
                         data-featured="<?php echo $type === 'featured' ? '1' : '0'; ?>"
                         data-popular="<?php echo $type === 'popular' ? '1' : '0'; ?>"
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

                            <?php if ($type === 'featured') : ?>
                                <div class="rental-mobil-badge rental-mobil-badge-featured"><?php _e('Unggulan', 'rental-mobil-wp'); ?></div>
                            <?php elseif ($type === 'popular') : ?>
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
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>

        <button class="rental-mobil-slider-prev">
            <span class="dashicons dashicons-arrow-left-alt2"></span>
        </button>
        <button class="rental-mobil-slider-next">
            <span class="dashicons dashicons-arrow-right-alt2"></span>
        </button>
    </div>
</div>
