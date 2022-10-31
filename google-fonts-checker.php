<?php

/*

 * Plugin Name: Google Font Checker 

 * Description: Google Font Checker. Shortcode [google_font_checker]

 * Author: Besticoder

 * Author URI: https://www.besticoder.com/

 * Version: 1.0

 *  Domain Path: /languages

 */


function google_font_plugin_scripts() {
	wp_enqueue_style( 'custom-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );


}

add_action( 'wp_enqueue_scripts', 'google_font_plugin_scripts' );


require_once plugin_dir_path( __FILE__ ) . 'public/shortcode.php';
