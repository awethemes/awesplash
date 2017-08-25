/**
 * File customize-control.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */



(function ($) {

    $(function () {

        wp.customize('awesplash_enable', function (value) {
            value.bind(function (to) {
                var confirmText = awesplash_var.confirm_off;
                if (to) {
                    confirmText = awesplash_var.confirm_on;
                }

                var r = confirm(confirmText);

                if (!r)
                    return;

                var enable = to ? 1 : 0;
                
                document.cookie = "awesplash=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";

                $.ajax({
                    type: 'POST',
                    data: {action: 'awesplash_enable', enable: enable, nonce: awesplash_var.nonce, wp_customize: 'on'},
                    url: awesplash_var.ajaxurl,
                    success: function (res) {
                        wp.customize.state('saved').set(true);
                        location.reload();
                    }
                });

            });
        });


    });

})(jQuery);
