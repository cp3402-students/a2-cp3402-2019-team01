/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function ($) {

    // Site title and description.
    wp.customize('blogname', function (value) {
        value.bind(function (to) {
            $('.site-title a').text(to);
        });
    });
    wp.customize('blogdescription', function (value) {
        value.bind(function (to) {
            $('.site-description').text(to);
        });
    });

    // Header background color.
    wp.customize('theme_header_color', function (value) {
        value.bind(function (to) {
            $('.header-container').css({
                'background-color': to
            });
        });
    });

    // Header text color.
    wp.customize('theme_header_text_color', function (value) {
        value.bind(function (to) {
            $('.header-container, .main-navigation a, .menu-toggle, .site-title a').css({
                'color': to
            });
        });
    });

    // Footer background color.
    wp.customize('theme_footer_color', function (value) {
        value.bind(function (to) {
            $('.site-footer').css({
                'background-color': to
            });
        });
    });

    // Footer text color.
    wp.customize('theme_footer_text_color', function (value) {
        value.bind(function (to) {
            $('.site-footer, .social-menu a, .footer-left-column a, .footer-right-column a, .site-info a, .site-info').css({
                'color': to
            });
        });
    });

    // Category links color.
    wp.customize('category_link_color', function (value) {
        value.bind(function (to) {
            $('.cat-links a').css({
                'color': to
            });
        });
    });

    // Theme highlight color.
    wp.customize('theme_highlight_color', function (value) {
        value.bind(function (to) {
            $('.header-container, .site-footer, .entry-meta, .footer-left-column, .widget-title, th, button:hover, button:focus, input[type="button"]:hover, input[type="button"]:focus, input[type="reset"]:hover, input[type="reset"]:focus, input[type="submit"]:hover input[type="submit"]:focus, textarea:active, textarea:hover, textarea:focus, .comment-navigation a:hover, .comment-navigation a:focus, .posts-navigation a:hover, .posts-navigation a:focus, .post-navigation a:hover, .post-navigation a:focus, .paging-navigation a:hover, .paging-navigation a:focus, .pagination a:hover, .pagination a:focus, .read-more a:hover, .read-more a:focus, .site-footer, .footer-left-column, .header-container, .by-author .avatar, .reply a:hover, .reply a:focus, .comment-form .form-submit input:hover, .comment-form .form-submit input:focus, .entry-meta, .footer-widgets').css({
                'border-color': to
            });
            $('.logged-in-as a:hover, .logged-in-as a:hover:focus, .pagination .current, .page-content a:hover, .page-content a:focus, .entry-content a:hover, .entry-content a:focus, .entry-summary a:hover, .entry-summary a:focus, .comment-content a:hover, .comment-content a:focus, .footer-left-column a:hover, .footer-left-column a:focus, .footer-right-column a:hover, .footer-right-column a:focus, .site-info a:hover, .site-info a:focus, .main-navigation a:hover, .main-navigation a:focus, .entry-title a:hover, .entry-title a:focus, .page-title a:hover, .page-title a:focus, .posted-on a:hover, .posted-on a:focus, .cat-links a:hover, .cat-links a:focus, .comments-link a:hover, .comments-link a:focus, .edit-link a:hover, .edit-link a:focus, .widget a:hover, .widget a:focus, .fa-home:hover, .fa-home:focus .menu-toggle button:hover').css({
                'color': to
            });
        });
    });

})(jQuery);
