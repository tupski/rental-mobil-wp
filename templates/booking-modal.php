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

<<<<<<< HEAD
            <div class="rental-mobil-form-group">
                <label for="rental-mobil-booking-nama"><?php _e('Nama', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                <input type="text" id="rental-mobil-booking-nama" name="nama" required>
            </div>

            <div class="rental-mobil-form-group">
                <label for="rental-mobil-booking-domisili"><?php _e('Domisili', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                <input type="text" id="rental-mobil-booking-domisili" name="domisili" required>
            </div>

            <div class="rental-mobil-form-row">
                <div class="rental-mobil-form-group rental-mobil-form-col">
                    <label for="rental-mobil-booking-tanggal"><?php _e('Tanggal Sewa', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                    <input type="date" id="rental-mobil-booking-tanggal" name="tanggal_sewa" required>
                </div>

                <div class="rental-mobil-form-group rental-mobil-form-col">
                    <label for="rental-mobil-booking-jam"><?php _e('Jam Sewa', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                    <div class="rental-mobil-time-dropdown-container">
                        <select id="rental-mobil-booking-jam-hour" class="rental-mobil-time-dropdown rental-mobil-hour-dropdown" required>
                            <option value=""><?php _e('Jam', 'rental-mobil-wp'); ?></option>
                            <?php for ($i = 0; $i < 24; $i++) : ?>
                                <option value="<?php echo sprintf('%02d', $i); ?>"><?php echo sprintf('%02d', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                        <span class="rental-mobil-time-separator">:</span>
                        <select id="rental-mobil-booking-jam-minute" class="rental-mobil-time-dropdown rental-mobil-minute-dropdown" required>
                            <option value=""><?php _e('Menit', 'rental-mobil-wp'); ?></option>
                            <option value="00">00</option>
                            <option value="30">30</option>
                        </select>
                        <input type="hidden" id="rental-mobil-booking-jam" name="jam_sewa" required>
                    </div>
                </div>
            </div>

            <div class="rental-mobil-form-row">
                <div class="rental-mobil-form-group rental-mobil-form-col">
                    <label for="rental-mobil-booking-durasi"><?php _e('Durasi Sewa', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                    <input type="number" id="rental-mobil-booking-durasi" name="durasi_sewa" min="1" value="1" required>
                </div>

                <div class="rental-mobil-form-group rental-mobil-form-col">
                    <label for="rental-mobil-booking-satuan"><?php _e('Satuan Durasi', 'rental-mobil-wp'); ?> <span class="required">*</span></label>
                    <select id="rental-mobil-booking-satuan" name="satuan_durasi" required>
                        <option value="hari"><?php _e('Hari', 'rental-mobil-wp'); ?></option>
                        <option value="minggu"><?php _e('Minggu', 'rental-mobil-wp'); ?></option>
                        <option value="bulan"><?php _e('Bulan', 'rental-mobil-wp'); ?></option>
                        <option value="tahun"><?php _e('Tahun', 'rental-mobil-wp'); ?></option>
                    </select>
                </div>
            </div>

            <div class="rental-mobil-form-actions">
                <button type="submit" class="rental-mobil-button rental-mobil-button-submit">
                    <?php _e('Booking via WhatsApp', 'rental-mobil-wp'); ?>
                </button>
            </div>
        </form>
=======
        // Include template booking-form.php
        include RENTAL_MOBIL_PLUGIN_DIR . 'templates/booking-form.php';
        ?>
>>>>>>> c10b851801dc71997f8b2ec8278168e907cee452
    </div>
</div>
