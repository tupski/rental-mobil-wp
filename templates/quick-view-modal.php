<?php
/**
 * Template untuk Quick View Modal
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div id="rental-mobil-quick-view-modal" class="rental-mobil-modal rental-mobil-quick-view-modal">
    <div class="rental-mobil-modal-content rental-mobil-quick-view-content">
        <span class="rental-mobil-modal-close">&times;</span>

        <div class="rental-mobil-quick-view-container">
            <div class="rental-mobil-quick-view-gallery">
                <!-- Gambar utama -->
                <div class="rental-mobil-quick-view-main-image">
                    <img src="" alt="" class="rental-mobil-quick-view-featured-image">
                </div>

                <!-- Thumbnail galeri dengan navigasi -->
                <div class="rental-mobil-quick-view-thumbnails-wrapper">
                    <button class="rental-mobil-quick-view-nav rental-mobil-quick-view-prev">
                        <span class="dashicons dashicons-arrow-left-alt2"></span>
                    </button>

                    <div class="rental-mobil-quick-view-thumbnails">
                        <!-- Thumbnails akan diisi oleh JavaScript -->
                    </div>

                    <button class="rental-mobil-quick-view-nav rental-mobil-quick-view-next">
                        <span class="dashicons dashicons-arrow-right-alt2"></span>
                    </button>
                </div>

                <?php
                // Dapatkan platform share dari pengaturan
                $share_platforms = rental_mobil_get_share_platforms();

                if (!empty($share_platforms)) :
                ?>
                <div class="rental-mobil-quick-view-share">
                    <span class="rental-mobil-quick-view-share-label"><?php _e('Bagikan:', 'rental-mobil-wp'); ?></span>
                    <div class="rental-mobil-quick-view-share-buttons">
                        <?php foreach ($share_platforms as $platform) :
                            // Tentukan ikon yang sesuai untuk setiap platform
                            $icon_class = 'dashicons-share';
                            $platform_name = ucfirst($platform);

                            if ($platform === 'whatsapp') {
                                $icon_class = 'dashicons-whatsapp';
                                $platform_name = 'WhatsApp';
                            } elseif ($platform === 'facebook') {
                                $icon_class = 'dashicons-facebook';
                                $platform_name = 'Facebook';
                            } elseif ($platform === 'twitter') {
                                $icon_class = 'dashicons-twitter';
                                $platform_name = 'Twitter';
                            } elseif ($platform === 'telegram') {
                                $icon_class = 'dashicons-format-chat';
                                $platform_name = 'Telegram';
                            } elseif ($platform === 'email') {
                                $icon_class = 'dashicons-email';
                                $platform_name = 'Email';
                            }
                        ?>
                            <button class="rental-mobil-share-button rental-mobil-share-<?php echo esc_attr($platform); ?>" data-platform="<?php echo esc_attr($platform); ?>" title="<?php echo esc_attr($platform_name); ?>">
                                <span class="dashicons <?php echo esc_attr($icon_class); ?>"></span>
                                <span class="rental-mobil-tooltip"><?php echo esc_html($platform_name); ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="rental-mobil-quick-view-details">
                <h2 class="rental-mobil-quick-view-title"></h2>

                <div class="rental-mobil-quick-view-badges">
                    <!-- Badge akan diisi oleh JavaScript -->
                </div>

                <div class="rental-mobil-quick-view-meta">
                    <!-- Meta akan diisi oleh JavaScript -->
                </div>

                <div class="rental-mobil-quick-view-prices">
                    <div class="rental-mobil-quick-view-price-item">
                        <span class="rental-mobil-quick-view-price-label"><?php _e('Harga per Hari', 'rental-mobil-wp'); ?></span>
                        <span class="rental-mobil-quick-view-price-value rental-mobil-quick-view-price-daily"></span>
                    </div>

                    <div class="rental-mobil-quick-view-price-item">
                        <span class="rental-mobil-quick-view-price-label"><?php _e('Harga per Minggu', 'rental-mobil-wp'); ?></span>
                        <span class="rental-mobil-quick-view-price-value rental-mobil-quick-view-price-weekly"></span>
                    </div>

                    <div class="rental-mobil-quick-view-price-item">
                        <span class="rental-mobil-quick-view-price-label"><?php _e('Harga per Bulan', 'rental-mobil-wp'); ?></span>
                        <span class="rental-mobil-quick-view-price-value rental-mobil-quick-view-price-monthly"></span>
                    </div>
                </div>

                <div class="rental-mobil-quick-view-actions">
                    <button class="rental-mobil-button rental-mobil-button-booking rental-mobil-quick-view-booking" data-id="">
                        <?php _e('Booking Sekarang', 'rental-mobil-wp'); ?>
                    </button>


                </div>
            </div>
        </div>
    </div>
</div>
