<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package custom_theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">

        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;

        if (is_active_sidebar('sidebar-1')) :
            ?>
            <div class="entry-meta">
                <?php
                custom_theme_posted_on();
                custom_theme_display_category_list();
                ?>
                <div class="comment-link-container">
                    <?php custom_theme_display_comment_link(); ?>
                </div><!-- .comment-link-container -->

                <div class="edit-post-link-container">
                    <?php custom_theme_display_edit_post_link() ?>
                </div><!-- .edit-post-link-container -->

            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php
    if (has_post_thumbnail()) { ?>
        <figure class="featured-image full-bleed">
            <?php custom_theme_post_thumbnail('custom_theme-full-bleed'); ?>
        </figure><!-- .featured-image full-bleed -->
    <?php } ?>

    <section class="post-content">

        <?php
        if (!is_active_sidebar('sidebar-1')) : ?>

        <div class="post-content-wrapper">
            <div class="entry-meta">
                <?php
                custom_theme_posted_on();
                custom_theme_display_category_list();
                ?>
                <div class="comment-link-container">
                    <?php custom_theme_display_comment_link(); ?>
                </div><!-- .comment-link-container -->

                <div class="edit-post-link-container">
                    <?php custom_theme_display_edit_post_link() ?>
                </div><!-- .edit-post-link-container -->

            </div><!-- .entry-meta -->

            <div class="post-content-body">
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content(sprintf(
                        wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                            __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'custom_theme'),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        get_the_title()
                    ));

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'custom_theme'),
                        'after' => '</div>',
                    ));
                    ?>
                </div><!-- .entry-content -->

                <?php
                if (!is_active_sidebar('sidebar-1')) : ?>
            </div><!-- .post-content-body -->
        </div> <!-- .post-content-wrapper -->
    <?php endif; ?>

        <footer class="entry-footer">
            <!--  <?php //custom_theme_entry_footer(); ?> Content is disabled as we have everything in the header. -->
        </footer><!-- .entry-footer -->

        <?php
        // Navigation links moved up above the comments section, no one likes comments. ;)
        custom_theme_post_navigation();

        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif; ?>

    </section><!-- .post-content -->

    <?php get_sidebar(); ?>

</article><!-- #post-<?php the_ID(); ?> -->
