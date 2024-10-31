(function ($, window, document) {
    'use strict';
    $(document).ready(function () {
        $('#accordation').accordion({
            active: false,
            collapsible: true
        });

        $('#image-generator-sumit').on('click', function () {
            var serializedData = $('.generator-inputs').find('input:not(input[type=button]), select').serialize();

            $.post(
                woo_image_object.admin_url,
                {
                    action: 'wp_generator_ajax_change',               // POST data, action
                    inputFieldsValues: serializedData,
                    productId: woo_image_object.product_id
                }, function (data) {
                    $('#image-generatored').attr('src', woo_image_object.site_url + data);

                }
            );
        });

        // validate only numbers
        $('.numbers').on('keypress', function(ev) {
            var keyCode = window.event ? ev.keyCode : ev.which;
            //codes for 0-9
            if (keyCode < 48 || keyCode > 57) {
                //codes for backspace, delete, enter
                if (keyCode != 0 && keyCode != 8 && keyCode != 13 && !ev.ctrlKey) {
                    ev.preventDefault();
                    alert('only numbers allowed!')
                }
            }
        });
    });
}(jQuery, window, document));