<?php 
function vpg_register_custom_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'VPG Templates',
            'singular_name' => 'VPG Templates',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New VPG Templates',
            'edit_item' => 'Edit VPG Templates',
            'new_item' => 'New VPG Templates',
            'view_item' => 'View VPG Templates',
            'search_items' => 'Search VPG Templates',
            'not_found' => 'No VPG Templates found',
            'not_found_in_trash' => 'No VPG Templates found in Trash',
            'all_items' => 'All VPG Templates',  
        ),
        'public' => true,
        'show_ui' => true,         
        'show_in_menu' => false,    
        'show_in_rest' => true,       
        'menu_position' => 25,
        'menu_icon' => 'dashicons-admin-page',
        'supports' => array( 'title', 'editor', 'elementor' ),
        'rewrite' => array( 'slug' => 'vpg-template' ),
    );

    register_post_type( 'vpg_template', $args );
}
add_action( 'init', 'vpg_register_custom_post_type' );
