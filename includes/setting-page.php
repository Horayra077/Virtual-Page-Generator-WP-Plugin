<?php
function vpg_add_admin_menu() {
    // Main menu for the plugin
    add_menu_page(
        'Page Generator',          // Page title
        'Page Generator',          // Menu title
        'manage_options',          // Capability needed
        'vpg-plugin',              // Menu slug
        'vpg_admin_page',          // Function to display the page
        'dashicons-screenoptions',        // Icon for the menu
        26                         // Position in menu
    );

    // Register the submenu for the custom post type under this plugin menu
    add_submenu_page(
        'vpg-plugin',              // Parent slug
        'All VPG Templates',           // Page title
        'All VPG Templates',           // Menu title
        'manage_options',          // Capability
        'edit.php?post_type=vpg_template' // Link to the custom post type's listing
    );
}
add_action( 'admin_menu', 'vpg_add_admin_menu' );