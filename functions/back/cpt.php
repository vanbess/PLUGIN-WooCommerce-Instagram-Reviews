<?php

/**
 * Register custom post type
 */

/**
 * Add CPT data to init
 */
add_action('init', 'sbwcir_cpt');

/**
 * CPT data
 */
function sbwcir_cpt() {
    $labels = array(
        'name'                  => _x('Instagram Reviews', 'Post Type General Name'),
        'singular_name'         => _x('Instagram Review', 'Post Type Singular Name'),
        'menu_name'             => _x('Instagram Reviews', 'Admin Menu text'),
        'name_admin_bar'        => _x('Instagram Review', 'Add New on Toolbar'),
        'archives'              => __('Instagram Review Archives'),
        'attributes'            => __('Instagram Review Attributes'),
        'parent_item_colon'     => __('Parent Instagram Review:'),
        'all_items'             => __('All Instagram Reviews'),
        'add_new_item'          => __('Add New Instagram Review'),
        'add_new'               => __('Add New'),
        'new_item'              => __('New Instagram Review'),
        'edit_item'             => __('Edit Instagram Review'),
        'update_item'           => __('Update Instagram Review'),
        'view_item'             => __('View Instagram Review'),
        'view_items'            => __('View Instagram Reviews'),
        'search_items'          => __('Search Instagram Review'),
        'not_found'             => __('Not found'),
        'not_found_in_trash'    => __('Not found in Trash'),
        'featured_image'        => __('Featured Image'),
        'set_featured_image'    => __('Set featured image'),
        'remove_featured_image' => __('Remove featured image'),
        'use_featured_image'    => __('Use as featured image'),
        'insert_into_item'      => __('Insert into Instagram Review'),
        'uploaded_to_this_item' => __('Uploaded to this Instagram Review'),
        'items_list'            => __('Instagram Reviews list'),
        'items_list_navigation' => __('Instagram Reviews list navigation'),
        'filter_items_list'     => __('Filter Instagram Reviews list'),
    );
    $args = array(
        'label'               => __('Instagram Review'),
        'description'         => __('Contains review data retrieved from parsed Instagram post JSON'),
        'labels'              => $labels,
        'menu_icon'           => 'dashicons-format-chat',
        'supports'            => array('revisions'),
        'taxonomies'          => array(),
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'show_in_admin_bar'   => false,
        'show_in_nav_menus'   => false,
        'can_export'          => true,
        'has_archive'         => false,
        'hierarchical'        => false,
        'exclude_from_search' => true,
        'show_in_rest'        => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
    );
    register_post_type('instagram_review', $args);
}
