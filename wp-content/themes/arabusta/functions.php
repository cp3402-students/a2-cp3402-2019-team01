<?php
/**
 * arabusta functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package arabusta
 */

if (!function_exists('arabusta_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function arabusta_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on arabusta, use a find and replace
         * to change 'arabusta' to the name of your theme in all the template files.
         */
        load_theme_textdomain('arabusta', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        add_image_size('arabusta-full-bleed', 2000, 1200, true);

        register_nav_menus(array(
            'menu-1' => esc_html__('Primary', 'arabusta'),
            'footer-left' => esc_html__('Footer Left Column', 'arabusta'),
            'footer-right' => esc_html__('Footer Right Column', 'arabusta'),
            'social-media' => esc_html__('Social Media', 'arabusta'), // Social media links
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('arabusta_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        add_theme_support('custom-header');

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support('custom-logo', array(
            'height' => 100,
            'width' => 100,
            'flex-width' => true,
            'flex-height' => true,
        ));
    }
endif;
add_action('after_setup_theme', 'arabusta_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function arabusta_content_width()
{
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('arabusta_content_width', 640);
}

add_action('after_setup_theme', 'arabusta_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function arabusta_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'arabusta'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'arabusta'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Widgets', 'arabusta'),
        'id' => 'footer-widgets',
        'description' => esc_html__('Add footer widgets here.', 'arabusta'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'arabusta_widgets_init');

/**
 * Register custom fonts.
 */
function arabusta_fonts_url()
{
    $fonts_url = '';

    /*
     * Translators: If there are characters in your language that are not
     * supported by Roboto and Open Sans, translate this to 'off'. Do not translate
     * into your own language.
     */
    $roboto = _x('on', 'Roboto font: on or off', 'arabusta');

    $open_sans = _x('on', 'Open Sans font: on or off', 'arabusta');

    $carter_one =  _x('on', 'Carter One font: on or off', 'arabusta');

    $font_families = array();

    if ('off' !== $roboto) {
        $font_families[] = 'Roboto:400,400i,700,700i';
    }
    if ('off' !== $open_sans) {
        $font_families[] = 'Open+Sans:400,400i,700,700i';
    }
    if ('off' !== $carter_one) {
        $font_families[] = 'Carter+One';
    }

    if (in_array('on', array($roboto, $open_sans, $carter_one))) {
        $query_args = array(
            'family' => urlencode(implode('|', $font_families)),
            'subset' => urlencode('latin,latin-ext'),
        );

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    }

    return esc_url_raw($fonts_url);
}

/**
 * Add preconnect for Google Fonts.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function arabusta_resource_hints($urls, $relation_type)
{
    if (wp_style_is('arabusta-fonts', 'queue') && 'preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}

add_filter('wp_resource_hints', 'arabusta_resource_hints', 10, 2);

/**
 * Enqueue scripts and styles.
 */
function arabusta_scripts()
{
    // Enqueue Google Fonts: Roboto and Open Sans.
    wp_enqueue_style('arabusta_fonts', arabusta_fonts_url());

    // Enqueue Font Awesome.
    wp_enqueue_style('fontawesome', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css');

    wp_enqueue_style('arabusta-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

    wp_enqueue_script('arabusta-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '20151215', true);

    wp_localize_script('arabusta-navigation', 'arabustaScreenReaderText', array(
        'expand' => __('Expand child menu', 'arabusta'),
        'collapse' => __('Collapse child menu', 'arabusta'),
    ));

    // Enqueue the js functions file.
    wp_enqueue_script('arabusta-functions', get_template_directory_uri() . '/js/functions.js', array('jquery'), '20190422', true);

    wp_enqueue_script('arabusta-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'arabusta_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}
