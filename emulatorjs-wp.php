<?php
/*
 * Plugin Name: EmulatorJS WP
 * Description: Nhúng EmulatorJS để chơi game retro trên WordPress.
 * Version: 1.0
 * Author: Grok 3 (xAI)
 * License: GPL2
 */

// Ngăn truy cập trực tiếp
if (!defined('ABSPATH')) {
    exit;
}

// Đăng ký style (nếu có)
function emulatorjs_wp_enqueue_scripts() {
    if (has_shortcode(get_post()->post_content, 'emulatorjs_game')) {
        wp_enqueue_style('emulatorjs-custom-style', plugin_dir_url(__FILE__) . 'css/emulatorjs-custom.css');
    }
}
add_action('wp_enqueue_scripts', 'emulatorjs_wp_enqueue_scripts');

// Shortcode nhúng game
function emulatorjs_wp_shortcode($atts) {
    $atts = shortcode_atts([
        'rom_url' => '',
        'system'  => 'nes',
        'width'   => '600px',
        'height'  => '400px',
        'speedrun' => 'false',
        'game_name' => '',
    ], $atts, 'emulatorjs_game');

    // Lọc dữ liệu đầu vào
    $rom_url = esc_url($atts['rom_url']);
    $system = sanitize_text_field($atts['system']);
    $width = esc_attr($atts['width']);
    $height = esc_attr($atts['height']);
    $speedrun = ($atts['speedrun'] === 'true') ? 'true' : 'false';
    $game_name = sanitize_text_field($atts['game_name']);

    $unique_id = 'game_' . uniqid();

    $output = '<div id="' . esc_attr($unique_id) . '" style="width: ' . $width . '; height: ' . $height . '; margin: 0 auto; position: relative;"></div>';
    $output .= '<script type="text/javascript">';
    $output .= 'document.addEventListener("DOMContentLoaded", function() {';
    $output .= 'EJS_player = "#' . esc_attr($unique_id) . '";';
    $output .= 'EJS_gameUrl = "' . $rom_url . '";';
    $output .= 'EJS_core = "' . $system . '";';
    $output .= 'EJS_pathtodata = "' . plugin_dir_url(__FILE__) . 'js/data/";';
    $output .= 'EJS_startOnLoaded = true;';
    $output .= 'EJS_speedrun = ' . $speedrun . ';';
    $output .= 'EJS_gameName = "' . $game_name . '";';
    $output .= '});';
    $output .= '</script>';
    $output .= '<script src="' . plugin_dir_url(__FILE__) . 'js/data/loader.js"></script>';

    return $output;
}
add_shortcode('emulatorjs_game', 'emulatorjs_wp_shortcode');