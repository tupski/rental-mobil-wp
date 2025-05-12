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
                
                <!-- Thumbnail galeri -->
                <div class="rental-mobil-quick-view-thumbnails">
                    <!-- Thumbnails akan diisi oleh JavaScript -->
                </div>
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
                    <a href="" class="rental-mobil-button rental-mobil-button-detail rental-mobil-quick-view-detail-link">
                        <?php _e('Lihat Detail Lengkap', 'rental-mobil-wp'); ?>
                    </a>
                    <button class="rental-mobil-button rental-mobil-button-booking rental-mobil-quick-view-booking" data-id="">
                        <?php _e('Booking', 'rental-mobil-wp'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
