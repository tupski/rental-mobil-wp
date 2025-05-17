<?php
/**
 * Template untuk Zoom Modal
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div id="rental-mobil-zoom-modal" class="rental-mobil-modal rental-mobil-zoom-modal">
    <div class="rental-mobil-modal-content rental-mobil-zoom-modal-content">
        <span class="rental-mobil-modal-close">&times;</span>

        <div class="rental-mobil-zoom-container">
            <img src="" alt="" class="rental-mobil-zoom-image">
        </div>

        <div class="rental-mobil-zoom-controls">
            <div class="rental-mobil-zoom-thumbnails-wrapper">
                <button class="rental-mobil-zoom-nav rental-mobil-zoom-prev">
                    <span class="dashicons dashicons-arrow-left-alt2"></span>
                </button>

                <div class="rental-mobil-zoom-thumbnails">
                    <!-- Thumbnails akan diisi oleh JavaScript -->
                </div>

                <button class="rental-mobil-zoom-nav rental-mobil-zoom-next">
                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                </button>
            </div>

            <div class="rental-mobil-zoom-actions">
                <?php
                // Dapatkan platform share dari pengaturan
                $share_platforms = rental_mobil_get_share_platforms();

                if (!empty($share_platforms)) :
                ?>
                <div class="rental-mobil-zoom-share-container">
                    <span class="rental-mobil-zoom-share-label"><?php _e('Bagikan:', 'rental-mobil-wp'); ?></span>
                    <div class="rental-mobil-zoom-share-buttons">
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
                            <button class="rental-mobil-zoom-share-button rental-mobil-zoom-share-<?php echo esc_attr($platform); ?>" data-platform="<?php echo esc_attr($platform); ?>" title="<?php echo esc_attr($platform_name); ?>">
                                <span class="dashicons <?php echo esc_attr($icon_class); ?>"></span>
                                <span class="rental-mobil-tooltip"><?php echo esc_html($platform_name); ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
