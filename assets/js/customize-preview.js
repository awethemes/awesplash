/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */



(function ($) {


    wp.customize('awesplash_custom_css', function (value) {
        value.bind(function (to) {
            $('#awesplash-fonts-inline-css').html(to);
        });
    });




    String.prototype.replaceAll = function (str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g, "\\$&"), (ignore ? "gi" : "g")), (typeof (str2) == "string") ? str2.replace(/\$/g, "$$$$") : str2);
    }


    var customizeTypography = function (name, selector) {
        wp.customize(name, function (value) {
            value.bind(function (to) {

                var data = JSON.parse(decodeURIComponent(to));
                var css = {};
                
                if (data.hasOwnProperty('font-family') && data['font-family'] != '') {
                    var id = data['font-family'].toLowerCase();
                    id.replaceAll(' ', '-');
                    if ($('#font_' + id).length == 0) {
                        $('head').append('<link id="font_' + id + '" href="https://fonts.googleapis.com/css?family=' + data['font-family'] + '" rel="stylesheet">');
                    }
                }else{
                    data['font-family'] = '';
                }

                $.each(data, function (key, value) {
                    if (key == 'variants') {
                        key = 'font-weight';
                    }
                    css[key] = value;
                });

                $(selector).css(css);
            });
        });
    }

    $(function () {
        
        /**
         * Background color
         */
        wp.customize('awesplash_background_color', function (value) {
            value.bind(function (to) {

                // Update custom color CSS.
                var style = $('#awesplash-style-inline-css'),
                        color = awesplash_var.background_color,
                        css = style.html();

                // Equivalent to css.replaceAll, with hue followed by comma to prevent values with units from being changed.

                css = css.replaceAll(color, to);
                awesplash_var.background_color = to;
                style.html(css);
            });
        });

        /**
         * Heading color
         */
        wp.customize('awesplash_heading_color', function (value) {
            value.bind(function (to) {
                $('.title__heading').css('color', to);
            });
        });

        /**
         * Heading typograpry
        
        customizeTypography('awesplash_heading_typo', '.title__heading');
 */

        /**
         * Content typograpry
        
        customizeTypography('awesplash_content_typo', '.title__description');
 */

        /**
         * Content color
         */
        wp.customize('awesplash_content_color', function (value) {
            value.bind(function (to) {

                // Update custom color CSS.
                var style = $('#awesplash-style-inline-css'),
                        color = awesplash_var.content_color,
                        css = style.html();

                // Equivalent to css.replaceAll, with hue followed by comma to prevent values with units from being changed.

                css = css.replaceAll(color, to);
                awesplash_var.content_color = to;
                style.html(css);
            });
        });

        /**
         * Button
         */

        /**
         * Text color
         */
        wp.customize('awesplash_button_color', function (value) {

            value.bind(function (to) {
                // Update custom color CSS.
                var style = $('#awesplash-style-inline-css'),
                        color = awesplash_var.button_color,
                        css = style.html();

                css = css.replaceAll(color, to);
                awesplash_var.button_color = to;
                style.html(css);
            });
        });

        /**
         * Text color hover
         */
        wp.customize('awesplash_button_color_hover', function (value) {
            value.bind(function (to) {
                // Update custom color CSS.
                var style = $('#awesplash-style-inline-css'),
                        color = awesplash_var.button_color_hover,
                        css = style.html();

                css = css.replaceAll(color, to);
                awesplash_var.button_color_hover = to;
                style.html(css);
            });
        });


        /**
         * Background color 
         */
        wp.customize('awesplash_button_bgcolor', function (value) {
            value.bind(function (to) {

                // Update custom color CSS.
                var style = $('#awesplash-style-inline-css'),
                        color = awesplash_var.button_bgcolor,
                        css = style.html();

                // Equivalent to css.replaceAll, with hue followed by comma to prevent values with units from being changed.

                css = css.replaceAll(color, to);
                awesplash_var.button_bgcolor = to;
                style.html(css);
                $('.title__action .btn').css('border-color', to);
            });
        });

        //Button bg hover
        wp.customize('awesplash_button_bgcolor_hover', function (value) {
            value.bind(function (to) {

                // Update custom color CSS.
                var style = $('#awesplash-style-inline-css'),
                        color = awesplash_var.button_bgcolor_hover,
                        css = style.html();

                if ($('#awesplash-style-inline-css_button').length == 0) {
                    $('#awesplash-style-inline-css').after('<style id="awesplash-style-inline-css_button"></style>');
                }

                $('#awesplash-style-inline-css_button').html('.title__action .btn:hover{border-color:' + to + ';}');
                // Equivalent to css.replaceAll, with hue followed by comma to prevent values with units from being changed.
                css = css.replaceAll(color, to);
                awesplash_var.button_bgcolor_hover = to;
                style.html(css);
            });
        });


        /**
         * Button typograpry
        
        customizeTypography('awesplash_button_typo', '.title__action .btn');
         */
    });

})(jQuery);
