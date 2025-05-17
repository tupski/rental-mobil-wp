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
            <!-- Judul (akan muncul pertama di mobile) -->
            <h2 class="rental-mobil-quick-view-title"></h2>

            <!-- Galeri (akan muncul kedua di mobile) -->
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
            </div>

            <!-- Detail (akan muncul ketiga di mobile) -->
            <div class="rental-mobil-quick-view-details">
                <div class="rental-mobil-quick-view-badges">
                    <!-- Badge akan diisi oleh JavaScript -->
                </div>

                <div class="rental-mobil-quick-view-meta">
                    <!-- Meta akan diisi oleh JavaScript -->
                </div>

                <!-- Harga (akan muncul keempat di mobile) -->
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

                <!-- Tombol Booking dan Bagikan (akan muncul kelima di mobile) -->
                <div class="rental-mobil-quick-view-actions">
                    <button class="rental-mobil-button rental-mobil-button-booking rental-mobil-quick-view-booking" data-id="">
                        <?php _e('Booking Sekarang', 'rental-mobil-wp'); ?>
                    </button>

                    <?php
                    // Dapatkan platform share dari pengaturan
                    $share_platforms = rental_mobil_get_share_platforms();
                    $is_copy_enabled = rental_mobil_is_copy_url_enabled();

                    if (!empty($share_platforms)) :
                    ?>
                    <!-- Share buttons untuk desktop -->
                    <div class="rental-mobil-quick-view-share rental-mobil-desktop-share">
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
                                } elseif ($platform === 'copy') {
                                    $icon_class = 'dashicons-clipboard';
                                    $platform_name = 'Salin URL';
                                }
                            ?>
                                <button class="rental-mobil-share-button rental-mobil-share-<?php echo esc_attr($platform); ?>" data-platform="<?php echo esc_attr($platform); ?>" title="<?php echo esc_attr($platform_name); ?>">
                                    <span class="dashicons <?php echo esc_attr($icon_class); ?>"></span>
                                    <span class="rental-mobil-tooltip"><?php echo esc_html($platform_name); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Tombol Bagikan untuk mobile -->
                    <div class="rental-mobil-quick-view-share rental-mobil-mobile-share">
                        <button class="rental-mobil-button rental-mobil-mobile-share-button">
                            <span class="dashicons dashicons-share-alt"></span>
                            <?php _e('Bagikan', 'rental-mobil-wp'); ?>
                        </button>
                    </div>

                    <!-- Modal Share untuk mobile -->
                    <div class="rental-mobil-mobile-share-modal">
                        <div class="rental-mobil-mobile-share-content">
                            <div class="rental-mobil-mobile-share-header">
                                <h3><?php _e('Bagikan', 'rental-mobil-wp'); ?></h3>
                                <span class="rental-mobil-mobile-share-close">&times;</span>
                            </div>
                            <div class="rental-mobil-mobile-share-buttons">
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
                                    } elseif ($platform === 'copy') {
                                        $icon_class = 'dashicons-clipboard';
                                        $platform_name = 'Salin URL';
                                    }
                                ?>
                                    <button class="rental-mobil-mobile-share-item rental-mobil-mobile-share-<?php echo esc_attr($platform); ?>" data-platform="<?php echo esc_attr($platform); ?>">
                                        <span class="dashicons <?php echo esc_attr($icon_class); ?>"></span>
                                        <span class="rental-mobil-mobile-share-item-name"><?php echo esc_html($platform_name); ?></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
