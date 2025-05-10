<?php
/**
 * Template untuk Filter Kendaraan
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

// Dapatkan semua terms untuk filter
$merk_terms = get_terms(array(
    'taxonomy' => 'merk_kendaraan',
    'hide_empty' => true,
));

$transmisi_terms = get_terms(array(
    'taxonomy' => 'transmisi',
    'hide_empty' => true,
));

$bahan_bakar_terms = get_terms(array(
    'taxonomy' => 'bahan_bakar',
    'hide_empty' => true,
));

$tipe_terms = get_terms(array(
    'taxonomy' => 'tipe_kendaraan',
    'hide_empty' => true,
));

$tahun_terms = get_terms(array(
    'taxonomy' => 'tahun_kendaraan',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'DESC',
));
?>

<!-- Floating Filter Button untuk Mobile -->
<div id="rental-mobil-filter-toggle" class="rental-mobil-filter-toggle">
    <i class="dashicons dashicons-filter"></i>
</div>

<div class="rental-mobil-filter">
    <h3 class="rental-mobil-filter-title"><?php _e('Filter Kendaraan', 'rental-mobil-wp'); ?></h3>

    <form id="rental-mobil-filter-form" class="rental-mobil-filter-form">
        <?php wp_nonce_field('rental_mobil_nonce', 'rental_mobil_filter_nonce'); ?>

        <div class="rental-mobil-filter-row">
            <?php if (!empty($merk_terms) && !is_wp_error($merk_terms)) : ?>
                <div class="rental-mobil-filter-col">
                    <label for="rental-mobil-filter-merk"><?php _e('Merk', 'rental-mobil-wp'); ?></label>
                    <select id="rental-mobil-filter-merk" name="merk" class="rental-mobil-filter-select">
                        <option value=""><?php _e('Semua Merk', 'rental-mobil-wp'); ?></option>
                        <?php foreach ($merk_terms as $term) : ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (!empty($transmisi_terms) && !is_wp_error($transmisi_terms)) : ?>
                <div class="rental-mobil-filter-col">
                    <label for="rental-mobil-filter-transmisi"><?php _e('Transmisi', 'rental-mobil-wp'); ?></label>
                    <select id="rental-mobil-filter-transmisi" name="transmisi" class="rental-mobil-filter-select">
                        <option value=""><?php _e('Semua Transmisi', 'rental-mobil-wp'); ?></option>
                        <?php foreach ($transmisi_terms as $term) : ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (!empty($bahan_bakar_terms) && !is_wp_error($bahan_bakar_terms)) : ?>
                <div class="rental-mobil-filter-col">
                    <label for="rental-mobil-filter-bahan-bakar"><?php _e('Bahan Bakar', 'rental-mobil-wp'); ?></label>
                    <select id="rental-mobil-filter-bahan-bakar" name="bahan_bakar" class="rental-mobil-filter-select">
                        <option value=""><?php _e('Semua Bahan Bakar', 'rental-mobil-wp'); ?></option>
                        <?php foreach ($bahan_bakar_terms as $term) : ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (!empty($tipe_terms) && !is_wp_error($tipe_terms)) : ?>
                <div class="rental-mobil-filter-col">
                    <label for="rental-mobil-filter-tipe"><?php _e('Tipe Kendaraan', 'rental-mobil-wp'); ?></label>
                    <select id="rental-mobil-filter-tipe" name="tipe" class="rental-mobil-filter-select">
                        <option value=""><?php _e('Semua Tipe', 'rental-mobil-wp'); ?></option>
                        <?php foreach ($tipe_terms as $term) : ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (!empty($tahun_terms) && !is_wp_error($tahun_terms)) : ?>
                <div class="rental-mobil-filter-col">
                    <label for="rental-mobil-filter-tahun"><?php _e('Tahun', 'rental-mobil-wp'); ?></label>
                    <select id="rental-mobil-filter-tahun" name="tahun" class="rental-mobil-filter-select">
                        <option value=""><?php _e('Semua Tahun', 'rental-mobil-wp'); ?></option>
                        <?php foreach ($tahun_terms as $term) : ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="rental-mobil-filter-col">
                <label for="rental-mobil-filter-orderby"><?php _e('Urutkan', 'rental-mobil-wp'); ?></label>
                <select id="rental-mobil-filter-orderby" name="orderby" class="rental-mobil-filter-select">
                    <option value="date"><?php _e('Terbaru', 'rental-mobil-wp'); ?></option>
                    <option value="title"><?php _e('Nama', 'rental-mobil-wp'); ?></option>
                    <option value="meta_value_num" data-meta-key="_rental_mobil_harga_sewa"><?php _e('Harga', 'rental-mobil-wp'); ?></option>
                </select>
            </div>

            <div class="rental-mobil-filter-col">
                <label for="rental-mobil-filter-order"><?php _e('Urutan', 'rental-mobil-wp'); ?></label>
                <select id="rental-mobil-filter-order" name="order" class="rental-mobil-filter-select">
                    <option value="DESC"><?php _e('Menurun', 'rental-mobil-wp'); ?></option>
                    <option value="ASC"><?php _e('Menaik', 'rental-mobil-wp'); ?></option>
                </select>
            </div>
        </div>

        <div class="rental-mobil-filter-actions">
            <button type="submit" class="rental-mobil-button rental-mobil-button-filter">
                <?php _e('Filter', 'rental-mobil-wp'); ?>
            </button>
            <button type="reset" class="rental-mobil-button rental-mobil-button-reset">
                <?php _e('Reset', 'rental-mobil-wp'); ?>
            </button>
        </div>
    </form>
</div>

<div id="rental-mobil-results" class="rental-mobil-results">
    <!-- Hasil filter akan ditampilkan di sini -->
</div>
