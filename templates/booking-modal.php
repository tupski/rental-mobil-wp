<?php
/**
 * Template untuk Modal Booking
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div id="rental-mobil-booking-modal" class="rental-mobil-modal">
    <div class="rental-mobil-modal-content">
        <span class="rental-mobil-modal-close">&times;</span>

        <div class="rental-mobil-modal-header">
            <h2 class="rental-mobil-modal-title"><?php _e('Booking', 'rental-mobil-wp'); ?> <span class="rental-mobil-modal-title-kendaraan"></span> <?php _e('Sekarang', 'rental-mobil-wp'); ?></h2>
            <p class="rental-mobil-modal-subtitle"><?php _e('Silakan isi form di bawah ini untuk booking', 'rental-mobil-wp'); ?> <span class="rental-mobil-modal-subtitle-kendaraan"></span></p>
        </div>

        <?php
        // Set kendaraan_id untuk template booking-form.php
        $kendaraan_id = '';

        // Include template booking-form.php
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/booking-form.php';
        ?>
    </div>
</div>
