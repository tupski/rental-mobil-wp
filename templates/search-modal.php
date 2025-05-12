<?php
/**
 * Template untuk Modal Pencarian
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div id="rental-mobil-search-modal" class="rental-mobil-modal rental-mobil-search-modal">
    <div class="rental-mobil-modal-content rental-mobil-search-modal-content">
        <span class="rental-mobil-modal-close">&times;</span>
        
        <div class="rental-mobil-search-modal-header">
            <h3><?php _e('Hasil Pencarian', 'rental-mobil-wp'); ?></h3>
        </div>
        
        <div class="rental-mobil-search-modal-body">
            <div id="rental-mobil-search-modal-results" class="rental-mobil-search-modal-results">
                <div class="rental-mobil-search-loading">
                    <div class="rental-mobil-spinner"></div>
                    <p><?php _e('Mencari kendaraan...', 'rental-mobil-wp'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
