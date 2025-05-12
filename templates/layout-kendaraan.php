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

        <!-- Active Filters -->
        <div class="rental-mobil-content-header">
            <div id="rental-mobil-active-filters" class="rental-mobil-active-filters"></div>
        </div>

        <!-- Results -->
        <div id="rental-mobil-results" class="rental-mobil-results">
            <?php
            // Dapatkan halaman saat ini
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            // Query kendaraan
            $args = array(
                'post_type'      => 'kendaraan',
                'posts_per_page' => 9, // Tetapkan 9 kendaraan per halaman
                'orderby'        => $atts['orderby'],
                'order'          => $atts['order'],
                'paged'          => $paged,
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

                // Tambahkan paginasi
                echo '<div class="rental-mobil-pagination">';
                echo '<div class="rental-mobil-pagination-info">';
                echo sprintf(
                    __('Menampilkan %1$s dari %2$s kendaraan', 'rental-mobil-wp'),
                    min($query->post_count, $query->found_posts),
                    $query->found_posts
                );
                echo '</div>';

                echo '<div class="rental-mobil-pagination-links" data-max-pages="' . $query->max_num_pages . '" data-current-page="' . $paged . '">';

                // Tombol Previous
                if ($paged > 1) {
                    echo '<a href="#" class="rental-mobil-pagination-prev" data-page="' . ($paged - 1) . '">' . __('« Sebelumnya', 'rental-mobil-wp') . '</a>';
                } else {
                    echo '<span class="rental-mobil-pagination-prev disabled">' . __('« Sebelumnya', 'rental-mobil-wp') . '</span>';
                }

                // Nomor halaman
                $start_page = max(1, $paged - 2);
                $end_page = min($query->max_num_pages, $paged + 2);

                if ($start_page > 1) {
                    echo '<a href="#" class="rental-mobil-pagination-number" data-page="1">1</a>';
                    if ($start_page > 2) {
                        echo '<span class="rental-mobil-pagination-dots">...</span>';
                    }
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    if ($i == $paged) {
                        echo '<span class="rental-mobil-pagination-number current">' . $i . '</span>';
                    } else {
                        echo '<a href="#" class="rental-mobil-pagination-number" data-page="' . $i . '">' . $i . '</a>';
                    }
                }

                if ($end_page < $query->max_num_pages) {
                    if ($end_page < $query->max_num_pages - 1) {
                        echo '<span class="rental-mobil-pagination-dots">...</span>';
                    }
                    echo '<a href="#" class="rental-mobil-pagination-number" data-page="' . $query->max_num_pages . '">' . $query->max_num_pages . '</a>';
                }

                // Tombol Next
                if ($paged < $query->max_num_pages) {
                    echo '<a href="#" class="rental-mobil-pagination-next" data-page="' . ($paged + 1) . '">' . __('Selanjutnya »', 'rental-mobil-wp') . '</a>';
                } else {
                    echo '<span class="rental-mobil-pagination-next disabled">' . __('Selanjutnya »', 'rental-mobil-wp') . '</span>';
                }

                echo '</div>'; // .rental-mobil-pagination-links
                echo '</div>'; // .rental-mobil-pagination
            } else {
                echo '<p>' . __('Tidak ada kendaraan yang ditemukan.', 'rental-mobil-wp') . '</p>';
            }

            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>
