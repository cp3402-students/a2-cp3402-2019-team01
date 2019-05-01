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

        if ('post' === get_post_type()) :
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

    <?php custom_theme_post_thumbnail(); ?>

    <div class="entry-content">
        <?php

        //Change this if we decide we only want to show an excerpt for each post on the index page.
        $show_post_as_excerpt = true;

        if ($show_post_as_excerpt === true) {
            the_excerpt();
        } else {
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
        }
        ?>
    </div><!-- .entry-content -->

    <div class="read-more-wrapper">
        <div class="read-more">
            <?php
            $read_more_link = sprintf(
                wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Read more<span class="screen-reader-text"> "%s"</span>', 'custom_theme'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            );
            ?>
            <a href="<?php echo esc_url(get_permalink()) ?>" rel="bookmark">
                <?php echo $read_more_link; ?>
            </a>
        </div><!-- .read-more -->
    </div><!-- .read-more-wrapper -->

    <footer class="entry-footer">
        <!--  <?php //custom_theme_entry_footer(); ?> Content is disabled as we have everything in the header. -->
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
