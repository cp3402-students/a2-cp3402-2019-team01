<?php
/**
 * The sidebar containing the footer widget area
 *
 * @package arabusta
 */

if (!is_active_sidebar('footer-widgets')) {
    return;
}
?>

<aside id="footer-widget-area" class="widget-area footer-widgets">
    <?php dynamic_sidebar('footer-widgets'); ?>
</aside><!-- #secondary -->
