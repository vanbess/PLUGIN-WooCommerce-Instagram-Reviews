<?php

/**
 * Plugin Name: SBWC Instagram Reviews
 * Description: Manaully retrieves Intagram post JSON data and parses said data into Instagram Review
 * Version: 1.0.0
 * Author: WC Bessinger
 */

if (!defined("ABSPATH")) :
    exit();
endif;

// constants
define('SBWCIR_PATH', plugin_dir_path(__FILE__));
define('SBWCIR_URL', plugin_dir_url(__FILE__));

function sbwcir_load() {
    // admin functions
    require_once(SBWCIR_PATH . 'functions/back/admin-settings.php');
    require_once(SBWCIR_PATH . 'functions/back/cpt.php');
    require_once(SBWCIR_PATH . 'functions/back/cpt-meta.php');
    require_once(SBWCIR_PATH . 'functions/back/insert-subbed-links.php');
    require_once(SBWCIR_PATH . 'functions/back/register-pll-strings.php');
    require_once(SBWCIR_PATH . 'functions/back/purge-reviews.php');
    require_once(SBWCIR_PATH . 'functions/back/post-update-action.php');

    // frontend functions
    require_once(SBWCIR_PATH . 'functions/front/shortcode.php');
    require_once(SBWCIR_PATH . 'functions/front/fetch-ig-lightbox.php');

    // css and js
    function sbwcir_scripts() {
        //wp_enqueue_script('sbwcir-glide', SBWCIR_URL . 'js/glide.min.js', [], '3.4.1', false);
        //wp_enqueue_style('sbwcir-glide-core', SBWCIR_URL . 'css/glide.core.min.css');
        //wp_enqueue_style('sbwcir-glide-theme', SBWCIR_URL . 'css/glide.theme.min.css');
        wp_enqueue_style('sbwcir-front', SBWCIR_URL . 'css/front.css');
        //wp_enqueue_script('sbwcir-front', SBWCIR_URL . 'js/front.js', ['jquery'], '1.0.0');
    }

    add_action('wp_enqueue_scripts', 'sbwcir_scripts');
}

// init/load
add_action('plugins_loaded', 'sbwcir_load');
