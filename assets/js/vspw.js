(function($) {
    'use strict';

    $(document).ready(function() {

        /**
         * Runs when user submits the "add website" form
         */
        $('.trial-form form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: FormData.endpoint,
                type: "POST",
                dataType: "json",
                timeout: FormData.restTimeout,
                data: {
                    "name": $(this).find('input[name="name"]').val(),
                    "url":  $(this).find('input[name="url"]').val(),
                    "_wpnonce": FormData.wp_rest
                },
            }).done(function(response) {
                alert('Success!');
            }).fail(function(response) {
                if (typeof response.responseJSON === "string") {
                    alert(response.responseJSON);
                } else {
                    alert('Something went wrong :(');
                }
            });
        });

    });

})(jQuery);
