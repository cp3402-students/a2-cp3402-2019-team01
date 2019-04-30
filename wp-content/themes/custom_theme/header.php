<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package custom_theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php include_once('favicon.php') ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'custom_theme'); ?></a>

    <div class="header-container">
        <header id="masthead" class="site-header">
            <div class="site-branding">
                <?php the_custom_logo(); ?>

                <div class="site-branding-text">
                    <?php
                    if (is_front_page() && is_home()) :
                        ?>
                        <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                                  rel="home"><?php bloginfo('name'); ?></a></h1>
                    <?php
                    else :
                        ?>
                        <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                                 rel="home"><?php bloginfo('name'); ?></a></p>
                    <?php
                    endif;
                    $custom_theme_description = get_bloginfo('description', 'display');
                    if ($custom_theme_description || is_customize_preview()) :
                        ?>
                        <p class="site-description"><?php echo $custom_theme_description; /* WPCS: xss ok. */ ?></p>
                    <?php endif; ?>
                </div><!-- .site-branding-text -->
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu"
                        aria-expanded="false"><i class="fas fa-bars"></i></button>
                <!-- Modified so we get the now standard "waffle" instead of PRIMARY MENU -->
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'menu-1',
                    'menu_id' => 'primary-menu',
                ));
                ?>
            </nav><!-- #site-navigation -->
            <!--&& is_front_page() -->

        </header><!-- #masthead -->
    </div>

    <?php if (get_header_image()) : ?>
        <figure class="header-image">
            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <img src="<?php header_image(); ?>" width="<?php echo absint(get_custom_header()->width); ?>"
                     height="<?php echo absint(get_custom_header()->height); ?>"
                     alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
            </a>
        </figure><!-- .header-image -->
    <?php endif; ?>

    <div id="content" class="site-content">
