jQuery(document).ready(function () {
    jQuery("#my-app").hide();
    jQuery("#settings").show();

    jQuery(document).on('click', '#validate-rytme', function (e) {

        e.preventDefault();

        jQuery.post(api_object.ajax_url, {

            action: 'validate_data',
            security: api_object.security

        }, function (response) {
            console.log(response);

            if (response.success) {

                if (response.data.success) {
                    jQuery('.vsz_recaptcha_setup_msg').append('<div id="message" class="notice  is-dismissible"><p>' + response.data.message + '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
                    return;
                } else {
                    jQuery('.vsz_recaptcha_setup_msg').append('<div id="message" class="notice error is-dismissible"><p>' + response.data.message + '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
                    return;
                }

            }

        });

    });
    jQuery(document).on('click', '.notice-dismiss', function () {
        jQuery(this).parent().remove();
    });

    jQuery('.rytme-head a').click(function (e) {
        jQuery('.rytme-head a').removeClass('active');
        jQuery(this).addClass('active');
        jQuery("#my-app").hide();
        jQuery("#settings").hide();
        jQuery('#' + jQuery(this).data("id")).show();
    });

    jQuery(document).on('click', '.notice-dismiss', function () {
        jQuery(this).parent().remove();
    });
});
