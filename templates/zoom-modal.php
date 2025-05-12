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
                <button class="rental-mobil-zoom-share">
                    <span class="dashicons dashicons-share"></span>
                    <span class="rental-mobil-zoom-action-text">Bagikan</span>
                </button>
            </div>
        </div>
    </div>
</div>
