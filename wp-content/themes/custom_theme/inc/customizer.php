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
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

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

    // Hover and Focus Link color setting.
    $wp_customize->add_setting('hover_focus_color',
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
            'hover_focus_color', array(
                'label' => __('Hover and Focus Link Color', 'custom_theme'),
                'section' => 'colors',
                'settings' => 'hover_focus_color'
            )
        )
    ); // Hover and Focus Link color setting.

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
        $header_text_color = get_header_textcolor();
        $header_background_color = get_theme_mod('theme_header_color');
        $footer_background_color = get_theme_mod('theme_footer_color');
        $category_link_color = get_theme_mod('category_link_color');
        $theme_highlight_color = get_theme_mod('theme_highlight_color');
        $hover_focus_color = get_theme_mod('hover_focus_color');
        /*
         * If no custom options for text are set, let's bail.
         * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
         */
        if (get_theme_support('custom-header', 'default-text-color') != $header_text_color) {
            //            return;
            //        }

            // If we get this far, we have custom styles. Let's do this.
            ?>
            <style type="text/css">
                <?php
                // Has the text been hidden?
                if ( ! display_header_text() ) :
                    ?>
                .site-title,
                .site-description {
                    position: absolute;
                    clip: rect(1px, 1px, 1px, 1px);
                }

                <?php
                // If the user has set a custom color for the text use that.
                else :
                    ?>
                .site-title a,
                .site-description {
                    color: #<?php echo esc_attr( $header_text_color ); ?>;
                }

                <?php endif; ?>
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

        if ('#30323d' != $footer_background_color) { ?>
            <style type="text/css">
                .site-footer {
                    background-color: <?php echo esc_attr( $footer_background_color); ?>
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
                .header-container, .site-footer, .entry-meta, .footer-left-colum {
                    border-color: <?php echo esc_attr( $theme_highlight_color); ?>
                }
            </style>
            <?php
        }

        if ('#e8c547' != $hover_focus_color) { ?>
            <style type="text/css">
                .a:hover, a:focus { /*----------------NEEDS TO BE COMPLETED ------------------------------------------*/
                    border-color: <?php echo esc_attr( $hover_focus_color); ?>
                }
            </style>
            <?php
        }
    }
endif;