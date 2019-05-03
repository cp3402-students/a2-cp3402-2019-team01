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

    // Header text colour.
    wp.customize('header_textcolor', function (value) {
        value.bind(function (to) {
            if ('blank' === to) {
                $('.site-title, .site-description').css({
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                });
            } else {
                $('.site-title, .site-description').css({
                    'clip': 'auto',
                    'position': 'relative'
                });
                $('.site-title a, .site-description').css({
                    'color': to
                });
            }
        });
    });

    // Header background colour.
    wp.customize('theme_header_color', function (value) {
        value.bind(function (to) {
            $('.header-container').css({
                'background-color': to
            });
        });
    });

    // Footer background colour.
    wp.customize('theme_footer_color', function (value) {
        value.bind(function (to) {
            $('.site-footer').css({
                'background-color': to
            });
        });
    });

    // Category links colour.
    wp.customize('category_link_color', function (value) {
        value.bind(function (to) {
            $('.cat-links a').css({
                'color': to
            });
        });
    });

    // Theme highlight colour.
    wp.customize('theme_highlight_color', function (value) {
        value.bind(function (to) {
            $('.header-container, .site-footer, .entry-meta, .footer-left-column').css({
                'border-color': to
            });
        });
    });

    // Hyperlink hover/focus colour.
    wp.customize('hover_focus_color', function (value) {
        value.bind(function (to) {
            $('a:hover, a:focus').css({
                'color': to
            });
        });
    });

})(jQuery);
