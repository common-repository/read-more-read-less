jQuery(document).ready(function () {
    jQuery(".rmrl_read_more").click(function () {
        var redirectTo = jQuery(this).siblings('.redirectTo').text();
        if (redirectTo) {
            window.location.assign(redirectTo);
            return;
        }
        jQuery(this).siblings('.rmrl_post_data').show();
        jQuery(this).siblings('.rmrl_read_less').show();
        jQuery(this).hide();
    });
    jQuery(".rmrl_read_less").click(function () {
        jQuery(this).siblings('.rmrl_post_data').hide();
        jQuery(this).siblings('.rmrl_read_more').show();
        jQuery(this).hide();
    });
});