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

// Cek status kendaraan
$is_featured = rental_mobil_is_featured($post_id);
$is_popular = rental_mobil_is_popular($post_id);

// Dapatkan harga mingguan dan bulanan
$harga_mingguan = rental_mobil_get_harga_mingguan($post_id);
$harga_mingguan_formatted = rental_mobil_format_rupiah($harga_mingguan);
$harga_bulanan = rental_mobil_get_harga_bulanan($post_id);
$harga_bulanan_formatted = rental_mobil_format_rupiah($harga_bulanan);

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
        <h1 class="rental-mobil-detail-title">
            <?php echo esc_html($kendaraan->post_title); ?>
            <?php if ($is_featured) : ?>
                <span class="rental-mobil-badge rental-mobil-badge-featured"><?php _e('Unggulan', 'rental-mobil-wp'); ?></span>
            <?php elseif ($is_popular) : ?>
                <span class="rental-mobil-badge rental-mobil-badge-popular"><?php _e('Paling Banyak Disewa', 'rental-mobil-wp'); ?></span>
            <?php endif; ?>
        </h1>

        <div class="rental-mobil-detail-meta">
            <?php if (!empty($merk)) : ?>
                <div class="rental-mobil-detail-meta-item">
                    <span class="rental-mobil-detail-meta-label"><?php _e('Merk:', 'rental-mobil-wp'); ?></span>
                    <span class="rental-mobil-detail-meta-value"><?php echo esc_html($merk); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($tahun)) : ?>
                <div class="rental-mobil-detail-meta-item">
                    <span class="rental-mobil-detail-meta-label"><?php _e('Tahun:', 'rental-mobil-wp'); ?></span>
                    <span class="rental-mobil-detail-meta-value"><?php echo esc_html($tahun); ?></span>
                </div>
            <?php endif; ?>
        </div>
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
                        <?php
                        $no_image_id = attachment_url_to_postid(RENTAL_MOBIL_PLUGIN_URL . 'assets/img/no-image.svg');
                        if ($no_image_id) {
                            echo wp_get_attachment_image($no_image_id, 'large', false, array('alt' => esc_attr($kendaraan->post_title)));
                        } else {
                            // Fallback jika gambar tidak terdaftar di media library
                            echo '<img src="' . esc_url(RENTAL_MOBIL_PLUGIN_URL . 'assets/img/no-image.svg') . '" alt="' . esc_attr($kendaraan->post_title) . '">';
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Galeri Kendaraan -->
                <?php if (!empty($galeri_images)) : ?>
                    <div class="rental-mobil-detail-gallery-thumbnails">
                        <?php foreach ($galeri_images as $attachment_id) : ?>
                            <div class="rental-mobil-detail-gallery-thumbnail">
                                <?php echo wp_get_attachment_image($attachment_id, 'thumbnail', false, array(
                                    'alt' => esc_attr($kendaraan->post_title),
                                    'class' => 'rental-mobil-gallery-image'
                                )); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Media Gallery Button -->
                <div class="rental-mobil-detail-gallery-actions">
                    <button class="rental-mobil-button rental-mobil-button-gallery" id="rental-mobil-open-gallery">
                        <?php _e('Lihat Semua Foto', 'rental-mobil-wp'); ?>
                    </button>
                </div>
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

            <div class="rental-mobil-detail-info-left">
                <div class="rental-mobil-detail-price">
                    <h3 class="rental-mobil-detail-price-title"><?php _e('Daftar Harga Sewa', 'rental-mobil-wp'); ?></h3>

                    <div class="rental-mobil-detail-price-grid">
                        <div class="rental-mobil-detail-price-card">
                            <div class="rental-mobil-detail-price-card-header">
                                <span class="rental-mobil-detail-price-period"><?php _e('Harian', 'rental-mobil-wp'); ?></span>
                            </div>
                            <div class="rental-mobil-detail-price-card-body">
                                <span class="rental-mobil-detail-price-value"><?php echo esc_html($harga_formatted); ?></span>
                                <span class="rental-mobil-detail-price-unit">/ <?php _e('hari', 'rental-mobil-wp'); ?></span>
                            </div>
                        </div>

                        <?php if (!empty($harga_mingguan)) : ?>
                        <div class="rental-mobil-detail-price-card">
                            <div class="rental-mobil-detail-price-card-header">
                                <span class="rental-mobil-detail-price-period"><?php _e('Mingguan', 'rental-mobil-wp'); ?></span>
                            </div>
                            <div class="rental-mobil-detail-price-card-body">
                                <span class="rental-mobil-detail-price-value"><?php echo esc_html($harga_mingguan_formatted); ?></span>
                                <span class="rental-mobil-detail-price-unit">/ <?php _e('minggu', 'rental-mobil-wp'); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($harga_bulanan)) : ?>
                        <div class="rental-mobil-detail-price-card">
                            <div class="rental-mobil-detail-price-card-header">
                                <span class="rental-mobil-detail-price-period"><?php _e('Bulanan', 'rental-mobil-wp'); ?></span>
                            </div>
                            <div class="rental-mobil-detail-price-card-body">
                                <span class="rental-mobil-detail-price-value"><?php echo esc_html($harga_bulanan_formatted); ?></span>
                                <span class="rental-mobil-detail-price-unit">/ <?php _e('bulan', 'rental-mobil-wp'); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
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
            </div>
        </div>

        <div class="rental-mobil-detail-sidebar">
            <!-- Floating Booking Button untuk Mobile -->
            <div class="rental-mobil-floating-booking">
                <button class="rental-mobil-button rental-mobil-button-booking" data-id="<?php echo esc_attr($post_id); ?>" data-title="<?php echo esc_attr($kendaraan->post_title); ?>">
                    <?php _e('Booking Sekarang', 'rental-mobil-wp'); ?>
                </button>
            </div>

            <!-- Form Booking Langsung -->
            <div class="rental-mobil-detail-booking-form">
                <h3 class="rental-mobil-detail-booking-title"><?php _e('Form Booking', 'rental-mobil-wp'); ?></h3>

                <?php
                // Set kendaraan_id untuk template inline-booking-form.php
                $kendaraan_id = $post_id;

                // Include template inline-booking-form.php
                include RENTAL_MOBIL_PLUGIN_DIR . 'templates/inline-booking-form.php';
                ?>
            </div>
        </div>
    </div>

    <!-- Kendaraan Terkait -->
    <?php
    // Dapatkan kendaraan terkait berdasarkan tipe kendaraan
    if (!empty($tipe_terms)) {
        $related_args = array(
            'post_type' => 'kendaraan',
            'posts_per_page' => 3,
            'post__not_in' => array($post_id),
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_rental_mobil_tipe',
                    'value' => $tipe,
                    'compare' => '=',
                ),
            ),
        );

        $related_query = new WP_Query($related_args);

        if ($related_query->have_posts()) :
    ?>
    <div class="rental-mobil-related">
        <h3 class="rental-mobil-related-title"><?php _e('Kendaraan Terkait', 'rental-mobil-wp'); ?></h3>
        <div class="rental-mobil-grid">
            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                <?php include RENTAL_MOBIL_PLUGIN_DIR . 'templates/card-kendaraan.php'; ?>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
        endif;
        wp_reset_postdata();
    }
    ?>
</div>
</div>

<!-- Gallery Modal -->
<div id="rental-mobil-gallery-modal" class="rental-mobil-modal rental-mobil-gallery-modal">
    <div class="rental-mobil-modal-content rental-mobil-gallery-modal-content">
        <span class="rental-mobil-modal-close">&times;</span>
        <div class="rental-mobil-gallery-modal-images">
            <?php if (has_post_thumbnail($post_id)) : ?>
                <div class="rental-mobil-gallery-modal-image">
                    <?php echo get_the_post_thumbnail($post_id, 'large'); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($galeri_images)) : ?>
                <?php foreach ($galeri_images as $attachment_id) : ?>
                    <div class="rental-mobil-gallery-modal-image">
                        <?php echo wp_get_attachment_image($attachment_id, 'large', false, array(
                            'alt' => esc_attr($kendaraan->post_title)
                        )); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="rental-mobil-gallery-modal-nav">
            <button class="rental-mobil-gallery-prev">&lt;</button>
            <button class="rental-mobil-gallery-next">&gt;</button>
        </div>
    </div>
</div>
