<?php
/**
 * Register Custom Post Types dan Taxonomies
 */

// Jika file ini dipanggil langsung, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Register Custom Post Type untuk Kendaraan
 */
function rental_mobil_register_post_types() {
    // Custom Post Type: Kendaraan
    $labels = array(
        'name'                  => _x('Kendaraan', 'Post type general name', 'rental-mobil-wp'),
        'singular_name'         => _x('Kendaraan', 'Post type singular name', 'rental-mobil-wp'),
        'menu_name'             => _x('Kendaraan', 'Admin Menu text', 'rental-mobil-wp'),
        'name_admin_bar'        => _x('Kendaraan', 'Add New on Toolbar', 'rental-mobil-wp'),
        'add_new'               => __('Tambah Baru', 'rental-mobil-wp'),
        'add_new_item'          => __('Tambah Kendaraan Baru', 'rental-mobil-wp'),
        'new_item'              => __('Kendaraan Baru', 'rental-mobil-wp'),
        'edit_item'             => __('Edit Kendaraan', 'rental-mobil-wp'),
        'view_item'             => __('Lihat Kendaraan', 'rental-mobil-wp'),
        'all_items'             => __('Semua Kendaraan', 'rental-mobil-wp'),
        'search_items'          => __('Cari Kendaraan', 'rental-mobil-wp'),
        'parent_item_colon'     => __('Kendaraan Induk:', 'rental-mobil-wp'),
        'not_found'             => __('Kendaraan tidak ditemukan.', 'rental-mobil-wp'),
        'not_found_in_trash'    => __('Kendaraan tidak ditemukan di sampah.', 'rental-mobil-wp'),
        'featured_image'        => __('Foto Kendaraan', 'rental-mobil-wp'),
        'set_featured_image'    => __('Atur foto kendaraan', 'rental-mobil-wp'),
        'remove_featured_image' => __('Hapus foto kendaraan', 'rental-mobil-wp'),
        'use_featured_image'    => __('Gunakan sebagai foto kendaraan', 'rental-mobil-wp'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'kendaraan'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'          => 'dashicons-car',
    );

    register_post_type('kendaraan', $args);

    // Taxonomy: Merk Kendaraan
    $labels = array(
        'name'              => _x('Merk Kendaraan', 'taxonomy general name', 'rental-mobil-wp'),
        'singular_name'     => _x('Merk Kendaraan', 'taxonomy singular name', 'rental-mobil-wp'),
        'search_items'      => __('Cari Merk Kendaraan', 'rental-mobil-wp'),
        'all_items'         => __('Semua Merk Kendaraan', 'rental-mobil-wp'),
        'parent_item'       => __('Merk Kendaraan Induk', 'rental-mobil-wp'),
        'parent_item_colon' => __('Merk Kendaraan Induk:', 'rental-mobil-wp'),
        'edit_item'         => __('Edit Merk Kendaraan', 'rental-mobil-wp'),
        'update_item'       => __('Update Merk Kendaraan', 'rental-mobil-wp'),
        'add_new_item'      => __('Tambah Merk Kendaraan Baru', 'rental-mobil-wp'),
        'new_item_name'     => __('Nama Merk Kendaraan Baru', 'rental-mobil-wp'),
        'menu_name'         => __('Merk Kendaraan', 'rental-mobil-wp'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'merk-kendaraan'),
    );

    register_taxonomy('merk_kendaraan', array('kendaraan'), $args);

    // Taxonomy: Transmisi
    $labels = array(
        'name'              => _x('Transmisi', 'taxonomy general name', 'rental-mobil-wp'),
        'singular_name'     => _x('Transmisi', 'taxonomy singular name', 'rental-mobil-wp'),
        'search_items'      => __('Cari Transmisi', 'rental-mobil-wp'),
        'all_items'         => __('Semua Transmisi', 'rental-mobil-wp'),
        'parent_item'       => __('Transmisi Induk', 'rental-mobil-wp'),
        'parent_item_colon' => __('Transmisi Induk:', 'rental-mobil-wp'),
        'edit_item'         => __('Edit Transmisi', 'rental-mobil-wp'),
        'update_item'       => __('Update Transmisi', 'rental-mobil-wp'),
        'add_new_item'      => __('Tambah Transmisi Baru', 'rental-mobil-wp'),
        'new_item_name'     => __('Nama Transmisi Baru', 'rental-mobil-wp'),
        'menu_name'         => __('Transmisi', 'rental-mobil-wp'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'transmisi'),
    );

    register_taxonomy('transmisi', array('kendaraan'), $args);

    // Taxonomy: Jenis Bahan Bakar
    $labels = array(
        'name'              => _x('Bahan Bakar', 'taxonomy general name', 'rental-mobil-wp'),
        'singular_name'     => _x('Bahan Bakar', 'taxonomy singular name', 'rental-mobil-wp'),
        'search_items'      => __('Cari Bahan Bakar', 'rental-mobil-wp'),
        'all_items'         => __('Semua Bahan Bakar', 'rental-mobil-wp'),
        'parent_item'       => __('Bahan Bakar Induk', 'rental-mobil-wp'),
        'parent_item_colon' => __('Bahan Bakar Induk:', 'rental-mobil-wp'),
        'edit_item'         => __('Edit Bahan Bakar', 'rental-mobil-wp'),
        'update_item'       => __('Update Bahan Bakar', 'rental-mobil-wp'),
        'add_new_item'      => __('Tambah Bahan Bakar Baru', 'rental-mobil-wp'),
        'new_item_name'     => __('Nama Bahan Bakar Baru', 'rental-mobil-wp'),
        'menu_name'         => __('Bahan Bakar', 'rental-mobil-wp'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'bahan-bakar'),
    );

    register_taxonomy('bahan_bakar', array('kendaraan'), $args);

    // Taxonomy: Tipe Kendaraan
    $labels = array(
        'name'              => _x('Tipe Kendaraan', 'taxonomy general name', 'rental-mobil-wp'),
        'singular_name'     => _x('Tipe Kendaraan', 'taxonomy singular name', 'rental-mobil-wp'),
        'search_items'      => __('Cari Tipe Kendaraan', 'rental-mobil-wp'),
        'all_items'         => __('Semua Tipe Kendaraan', 'rental-mobil-wp'),
        'parent_item'       => __('Tipe Kendaraan Induk', 'rental-mobil-wp'),
        'parent_item_colon' => __('Tipe Kendaraan Induk:', 'rental-mobil-wp'),
        'edit_item'         => __('Edit Tipe Kendaraan', 'rental-mobil-wp'),
        'update_item'       => __('Update Tipe Kendaraan', 'rental-mobil-wp'),
        'add_new_item'      => __('Tambah Tipe Kendaraan Baru', 'rental-mobil-wp'),
        'new_item_name'     => __('Nama Tipe Kendaraan Baru', 'rental-mobil-wp'),
        'menu_name'         => __('Tipe Kendaraan', 'rental-mobil-wp'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'tipe-kendaraan'),
    );

    register_taxonomy('tipe_kendaraan', array('kendaraan'), $args);
}
