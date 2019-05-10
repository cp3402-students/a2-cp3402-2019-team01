<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package arabusta
 */

get_header();
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            if (have_posts()) :

                if (is_home() && !is_front_page()) :
                    ?>
                    <header>
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                <?php
                endif;

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
