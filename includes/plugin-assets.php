<?php
function vpg_enqueue_scripts() {

    if (!is_admin()) {
        wp_enqueue_style( 'vpg-bootstrap', VPG_PLUGIN_URL . 'assets/css/bootstrap.min.css' );
        wp_enqueue_style( 'vpg-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' );
        wp_enqueue_style( 'vpg-owl-carousel', VPG_PLUGIN_URL . 'assets/css/owl.carousel.min.css' );
        wp_enqueue_style( 'vpg-owl-theme', VPG_PLUGIN_URL . 'assets/css/owl.theme.default.min.css' );
    }

    wp_enqueue_style( 'vpg-styles', VPG_PLUGIN_URL . 'assets/css/style.css' );
    
    if (!is_admin()) {
        wp_enqueue_script( 'vpg-jquery-scripts', VPG_PLUGIN_URL . 'assets/js/jquery.min.js', array(), '1.0', true );
        wp_enqueue_script( 'vpg-bootstrap-scripts', VPG_PLUGIN_URL . 'assets/js/bootstrap.bundle.min.js', array(), null, true );
        wp_enqueue_script( 'vpg-owl-scripts', VPG_PLUGIN_URL . 'assets/js/owl.carousel.min.js', array(), '1.0', true );
    }

    wp_enqueue_script( 'vpg-scripts', VPG_PLUGIN_URL . 'assets/js/script.js', array(), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'vpg_enqueue_scripts' );
