<?php
/**
 * custom_theme Theme Customizer
 *
 * @package custom_theme
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function custom_theme_customize_register($wp_customize)
{
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
//    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    // Header color setting.
    $wp_customize->add_setting('theme_header_color', array(
            'default' => '#30323d',
            'transport' => 'postMessage',
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control (
            $wp_customize, 'theme_header_color', array(
                'label' => __('Header Background Color', 'custom_theme'),
                'section' => 'colors',
                'settings' => 'theme_header_color'
            )
        )
    ); // End Header color setting.

    // Header text color setting.
    $wp_customize->add_setting('theme_header_text_color', array(
            'default' => '#ffffff',
            'transport' => 'postMessage',
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control (
            $wp_customize, 'theme_header_text_color', array(
                'label' => __('Header Text Color', 'custom_theme'),
                'section' => 'colors',
                'settings' => 'theme_header_text_color'
            )
        )
    ); // End Header text color setting.

    // Footer color setting.
    $wp_customize->add_setting('theme_footer_color', array(
            'default' => '#30323d',
            'transport' => 'postMessage',
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control (
            $wp_customize, 'theme_footer_color', array(
                'label' => __('Footer Background Color', 'custom_theme'),
                'section' => 'colors',
                'settings' => 'theme_footer_color'
            )
        )
    ); // End Footer color setting.

    // Footer text color setting.
    $wp_customize->add_setting('theme_footer_text_color', array(
            'default' => '#ffffff',
            'transport' => 'postMessage',
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control (
            $wp_customize, 'theme_footer_text_color', array(
                'label' => __('Footer Text Color', 'custom_theme'),
                'section' => 'colors',
                'settings' => 'theme_footer_text_color'
            )
        )
    ); // End Footer text color setting.

    // Category link color setting.
    $wp_customize->add_setting('category_link_color',
        array(
            'default' => '#404040',
            'transport' => 'postMessage',
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'category_link_color', array(
                'label' => __('Category Links Color', 'custom_theme'),
                'section' => 'colors',
                'settings' => 'category_link_color'
            )
        )
    ); // Category link color setting.

    // Theme Highlight color setting.
    $wp_customize->add_setting('theme_highlight_color',
        array(
            'default' => '#e8c547',
            'transport' => 'postMessage',
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'theme_highlight_color', array(
                'label' => __('Theme Highlight Color', 'custom_theme'),
                'section' => 'colors',
                'settings' => 'theme_highlight_color'
            )
        )
    ); // Theme Highlight color setting.

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector' => '.site-title a',
            'render_callback' => 'custom_theme_customize_partial_blogname',
        ));
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector' => '.site-description',
            'render_callback' => 'custom_theme_customize_partial_blogdescription',
        ));
    }
}

add_action('customize_register', 'custom_theme_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function custom_theme_customize_partial_blogname()
{
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function custom_theme_customize_partial_blogdescription()
{
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function custom_theme_customize_preview_js()
{
    wp_enqueue_script('custom_theme-customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), '20151215', true);
}

add_action('customize_preview_init', 'custom_theme_customize_preview_js');

if (!function_exists('custom_theme_header_style')) :
    /**
     * Styles the header image and text displayed on the blog.
     *
     * @see custom_theme_custom_header_setup().
     */
    function custom_theme_header_style()
    {
        $header_background_color = get_theme_mod('theme_header_color');
        $theme_header_text_color = get_theme_mod('theme_header_text_color');
        $footer_background_color = get_theme_mod('theme_footer_color');
        $theme_footer_text_color = get_theme_mod('theme_footer_text_color');
        $category_link_color = get_theme_mod('category_link_color');
        $theme_highlight_color = get_theme_mod('theme_highlight_color');

        if ('#30323d' != $header_background_color) { ?>
            <style type="text/css">
                .header-container {
                    background-color: <?php echo esc_attr( $header_background_color); ?>
                }
            </style>
            <?php
        }

        if ('#ffffff' != $theme_header_text_color) { ?>
            <style type="text/css">
                .header-container, .main-navigation a, .menu-toggle, .site-title a {
                    color: <?php echo esc_attr( $theme_header_text_color); ?>
                }
            </style>
            <?php
        }

        if ('#30323d' != $footer_background_color) { ?>
            <style type="text/css">
                .site-footer {
                    background-color: <?php echo esc_attr( $footer_background_color); ?>
                }
            </style>
            <?php
        }

        if ('#ffffff' != $theme_footer_text_color) { ?>
            <style type="text/css">
                .site-footer, .social-menu a, .footer-left-column a, .footer-right-column a, .site-info a, .site-info {
                    color: <?php echo esc_attr( $theme_footer_text_color); ?>
                }
            </style>
            <?php
        }


        if ('#404040' != $category_link_color) { ?>
            <style type="text/css">
                .cat-links a {
                    color: <?php echo esc_attr( $category_link_color); ?>
                }
            </style>
            <?php
        }

        if ('#e8c547' != $theme_highlight_color) { ?>
            <style type="text/css">
                .header-container, .site-footer, .entry-meta, .footer-left-colum, .widget-title, th, button:hover, button:focus, input[type="button"]:hover, input[type="button"]:focus, input[type="reset"]:hover, input[type="reset"]:focus, input[type="submit"]:hover input[type="submit"]:focus, textarea:active, textarea:hover, textarea:focus, .comment-navigation a:hover, .comment-navigation a:focus, .posts-navigation a:hover, .posts-navigation a:focus, .post-navigation a:hover, .post-navigation a:focus, .paging-navigation a:hover, .paging-navigation a:focus, .pagination a:hover, .pagination a:focus, .read-more a:hover, .read-more a:focus, .site-footer, .footer-left-column, .header-container, .by-author .avatar, .reply a:hover, .reply a:focus, .comment-form .form-submit input:hover, .comment-form .form-submit input:focus, .entry-meta, .footer-widgets {
                    border-color: <?php echo esc_attr( $theme_highlight_color); ?>
                }

                .logged-in-as a:hover, .logged-in-as a:hover:focus, .pagination .current, .page-content a:hover, .page-content a:focus, .entry-content a:hover, .entry-content a:focus, .entry-summary a:hover, .entry-summary a:focus, .comment-content a:hover, .comment-content a:focus, .footer-left-column a:hover, .footer-left-column a:focus, .footer-right-column a:hover, .footer-right-column a:focus, .site-info a:hover, .site-info a:focus, .main-navigation a:hover, .main-navigation a:focus, .entry-title a:hover, .entry-title a:focus, .page-title a:hover, .page-title a:focus, .posted-on a:hover, .posted-on a:focus, .cat-links a:hover, .cat-links a:focus, .comments-link a:hover, .comments-link a:focus, .edit-link a:hover, .edit-link a:focus, .widget a:hover, .widget a:focus, .fa-home:hover, .fa-home:focus, .menu-toggle button:hover {
                    color: <?php echo esc_attr( $theme_highlight_color); ?>
                }

                .menu-toggle button:hover {
                    background-color: <?php echo esc_attr( $theme_highlight_color); ?>
                }
            </style>
            <?php
        }
    }
endif;