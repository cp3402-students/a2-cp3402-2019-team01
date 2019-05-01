<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
 * <?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package custom_theme
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses custom_theme_header_style()
 */
function custom_theme_custom_header_setup()
{
    add_theme_support('custom-header', apply_filters('custom_theme_custom_header_args', array(
        'default-image' => '',
        'header-text' => false,
        'default-text-color' => '000000',
        'width' => 2000,
        'height' => 850,
        'flex-height' => true,
        'wp-head-callback' => 'custom_theme_header_style',
    )));
}

add_action('after_setup_theme', 'custom_theme_custom_header_setup');