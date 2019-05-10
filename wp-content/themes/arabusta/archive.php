<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package arabusta
 */

get_header();
?>

<?php if (have_posts()) : ?>

    <header class="page-header">
    <?php
    the_archive_title('<h1 class="page-title">', '</h1>');
    the_archive_description('<div class="archive-description">', '</div>');
    ?>

<?php endif; ?>
    </header><!-- .page-header -->

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php if (have_posts()) : ?>

                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part('template-parts/content', get_post_type());

                endwhile;

                // Variable controls the display of index navigation using either the older pagination method, or the new 'standard' navigation method.
                $index_pagination = true;

                if ($index_pagination) {
                    // Use pagination for the movement between newer and older pages.
                    the_posts_pagination(array(
                        'prev_text' => __('Newer', 'arabusta'),
                        'next_text' => __('Older', 'arabusta'),
                        'before_page_number' => '<span class="screen-reader-text">' . __('Page ', 'arabusta') . '</span>',
                    ));
                } else {
                    // Use the newer chunky buttons for navigation between posts.
                    the_posts_navigation();
                }

            else :

                get_template_part('template-parts/content', 'none');

            endif;
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer();
