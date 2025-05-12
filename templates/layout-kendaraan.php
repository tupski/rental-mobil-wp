<?php
/**
 * Template untuk Layout Kendaraan dengan Filter di Kiri dan Card di Kanan
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div class="rental-mobil-layout">
    <!-- Sidebar Filter (25%) -->
    <div class="rental-mobil-sidebar">
        <div class="rental-mobil-sidebar-inner">

            <?php include RENTAL_MOBIL_PLUGIN_DIR . 'templates/filter-kendaraan.php'; ?>
        </div>
    </div>

    <!-- Content (75%) -->
    <div class="rental-mobil-content">
        <!-- Floating Filter Button untuk Mobile -->
        <div id="rental-mobil-filter-toggle" class="rental-mobil-filter-toggle">
            <i class="dashicons dashicons-filter"></i>
        </div>

        <!-- Overlay untuk filter sidebar -->
        <div id="rental-mobil-filter-overlay" class="rental-mobil-filter-overlay"></div>

        <!-- Title and Active Filters -->
        <div class="rental-mobil-content-header">
            <h2 class="rental-mobil-content-title"><?php _e('Daftar Kendaraan Rental', 'rental-mobil-wp'); ?></h2>
            <div id="rental-mobil-active-filters" class="rental-mobil-active-filters"></div>
        </div>

        <!-- Results -->
        <div id="rental-mobil-results" class="rental-mobil-results">
            <?php
            // Query kendaraan
            $args = array(
                'post_type'      => 'kendaraan',
                'posts_per_page' => $atts['jumlah'],
                'orderby'        => $atts['orderby'],
                'order'          => $atts['order'],
            );

            // Tambahkan filter berdasarkan parameter
            if (!empty($atts['merk'])) {
                $merk_terms = get_terms(array(
                    'taxonomy' => 'merk_kendaraan',
                    'slug' => explode(',', $atts['merk']),
                    'fields' => 'ids',
                ));

                if (!empty($merk_terms) && !is_wp_error($merk_terms)) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'merk_kendaraan',
                        'field'    => 'term_id',
                        'terms'    => $merk_terms,
                        'operator' => 'IN',
                    );
                }
            }

            if (!empty($atts['transmisi'])) {
                $transmisi_terms = get_terms(array(
                    'taxonomy' => 'transmisi',
                    'slug' => explode(',', $atts['transmisi']),
                    'fields' => 'ids',
                ));

                if (!empty($transmisi_terms) && !is_wp_error($transmisi_terms)) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'transmisi',
                        'field'    => 'term_id',
                        'terms'    => $transmisi_terms,
                        'operator' => 'IN',
                    );
                }
            }

            if (!empty($atts['bahan_bakar'])) {
                $bahan_bakar_terms = get_terms(array(
                    'taxonomy' => 'bahan_bakar',
                    'slug' => explode(',', $atts['bahan_bakar']),
                    'fields' => 'ids',
                ));

                if (!empty($bahan_bakar_terms) && !is_wp_error($bahan_bakar_terms)) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'bahan_bakar',
                        'field'    => 'term_id',
                        'terms'    => $bahan_bakar_terms,
                        'operator' => 'IN',
                    );
                }
            }

            if (!empty($atts['tipe'])) {
                $tipe_terms = get_terms(array(
                    'taxonomy' => 'tipe_kendaraan',
                    'slug' => explode(',', $atts['tipe']),
                    'fields' => 'ids',
                ));

                if (!empty($tipe_terms) && !is_wp_error($tipe_terms)) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'tipe_kendaraan',
                        'field'    => 'term_id',
                        'terms'    => $tipe_terms,
                        'operator' => 'IN',
                    );
                }
            }

            if (!empty($atts['tahun'])) {
                $tahun_terms = get_terms(array(
                    'taxonomy' => 'tahun_kendaraan',
                    'slug' => explode(',', $atts['tahun']),
                    'fields' => 'ids',
                ));

                if (!empty($tahun_terms) && !is_wp_error($tahun_terms)) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'tahun_kendaraan',
                        'field'    => 'term_id',
                        'terms'    => $tahun_terms,
                        'operator' => 'IN',
                    );
                }
            }

            // Jika ada lebih dari satu tax_query, tambahkan relation AND
            if (isset($args['tax_query']) && count($args['tax_query']) > 1) {
                $args['tax_query']['relation'] = 'AND';
            }

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                echo '<div class="rental-mobil-grid">';
                while ($query->have_posts()) {
                    $query->the_post();
                    include RENTAL_MOBIL_PLUGIN_DIR . 'templates/card-kendaraan.php';
                }
                echo '</div>';
            } else {
                echo '<p>' . __('Tidak ada kendaraan yang ditemukan.', 'rental-mobil-wp') . '</p>';
            }

            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>
