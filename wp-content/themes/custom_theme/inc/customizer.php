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

    // Add a new section for general changes, starting with boolean option for title display on Page.php pages.
    $wp_customize->add_section('theme_options', array(
            'title' => __('Text Options', 'custom_theme'),
            'priority' => 95,
            'capability' => 'edit_theme_options',
            'description' => __('Toggle the display of title on page.php pages')
        )
    );

    $wp_customize->add_setting('page_title_visibility',
        array(
            'default' => 'inline-block',
            'type' => 'theme_mod',
            'sanitize_callback' => 'custom_theme_sanitize_page_title_visibility_control',
            'transport' => 'postMessage'

        )
    );

    $wp_customize->add_control('custom_theme_page_title_visibility_control',
        array(
            'type' => 'radio',
            'label' => __('Display Title on "Page" type pages', 'custom_theme'),
            'section' => 'theme_options',
            'choices' => array(
                'inline-block' => __('Visible (default)', 'custom_theme'),
                'none' => __('Hidden', 'custom_theme')
            ),
            'settings' => 'page_title_visibility'
        )
    );

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
    );

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
    );

    // Widgets footer color setting.
    $wp_customize->add_setting('theme_widgets_footer_color', array(
            'default' => '#484a51',
            'transport' => 'postMessage',
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control (
            $wp_customize, 'theme_widgets_footer_color', array(
                'label' => __('Widget Footer Background Color', 'custom_theme'),
                'section' => 'colors',
                'settings' => 'theme_widgets_footer_color'
            )
        )
    );

    // Widgets footer text color setting.
    $wp_customize->add_setting('theme_widgets_footer_text_color', array(
            'default' => '#ffffff',
            'transport' => 'postMessage',
            'type' => 'theme_widgets_mod',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control (
            $wp_customize, 'theme_widgets_footer_text_color', array(
                'label' => __('Widget Footer Text Color', 'custom_theme'),
                'section' => 'colors',
                'settings' => 'theme_widgets_footer_text_color'
            )
        )
    );

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
    );

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
    );

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
    );

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
    );

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
 * Sanitize length options:
 * If something goes wrong and one of the two specified options are not used,
 * apply the default (excerpt).
 */

function custom_theme_sanitize_page_title_visibility_control($value)
{
    if (!in_array($value, array('inline-block', 'none'))) {
        $value = 'inline-block';
    }
    return $value;
}

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

        $page_title_visibility = get_theme_mod('page_title_visibility');

        $header_background_color = get_theme_mod('theme_header_color');
        $theme_header_text_color = get_theme_mod('theme_header_text_color');

        $footer_background_color = get_theme_mod('theme_footer_color');
        $theme_footer_text_color = get_theme_mod('theme_footer_text_color');

        $widgets_footer_background_color = get_theme_mod('theme_widgets_footer_color');
        $theme_widgets_footer_text_color = get_theme_mod('theme_widgets_footer_text_color');

        $category_link_color = get_theme_mod('category_link_color');
        $theme_highlight_color = get_theme_mod('theme_highlight_color');

        if ('inline-block' != $page_title_visibility) { ?>
            <style type="text/css">
                .page .entry-title {
                    display: <?php echo esc_attr( $page_title_visibility); ?>
                }
            </style>
            <?php
        }

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

        if ('#484a51' != $widgets_footer_background_color) { ?>
            <style type="text/css">
                .footer-widgets {
                    background-color: <?php echo esc_attr( $widgets_footer_background_color); ?>
                }
            </style>
            <?php
        }

        if ('#ffffff' != $theme_widgets_footer_text_color) { ?>
            <style type="text/css">
                .footer-widgets, .footer-widgets a, .footer-widgets .widget-title {
                    color: <?php echo esc_attr( $theme_widgets_footer_text_color); ?>
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