<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package custom_theme
 */

?>

</div><!-- #content -->

<footer id="colophon" class="site-footer">

    <nav class="social-menu">
        <?php
        wp_nav_menu(array('theme_location' => 'social-media',)); ?>
    </nav><!-- .social-menu -->

    <div class="footer-columns">
        <nav class="footer-left-column">
            <?php
            wp_nav_menu(array('theme_location' => 'footer-left',)); ?>
        </nav><!-- .footer-left-column -->

        <nav class="footer-right-column">
            <?php
            wp_nav_menu(array('theme_location' => 'footer-right',)); ?>
        </nav><!-- .footer-right-column -->
    </div><!-- .footer-columns -->

    <div class="site-info">
        <a href="<?php echo esc_url(__('https://wordpress.org/', 'custom_theme')); ?>">
            <?php
            /* translators: %s: CMS name, i.e. WordPress. */
            printf(esc_html__('Proudly powered by %s', 'custom_theme'), 'WordPress');
            ?>
        </a>
        <span class="sep"> | </span>
        <?php
        /* translators: 1: Theme name, 2: Theme author. */
        printf(esc_html__('Theme: %1$s by %2$s.', 'custom_theme'), 'custom_theme', '<a href="http://underscores.me/">Underscores.me</a>');
        ?>
    </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
