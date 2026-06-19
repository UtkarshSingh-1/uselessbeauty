(function($) {
    $(document).ready(function() {
        var currentPath = window.location.pathname;
        
        // If we are already in the India region, don't show any modals or redirect
        if (currentPath.match(/^\/in(\/|$)/)) {
            return;
        }

        // Helper to get cookie
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();
        }

        // Helper to set cookie
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        var redirectChoice = getCookie('ulb_redirect_choice');
        var visitorCountry = getCookie('ulb_visitor_country');

        // If they chose to stay, respect their choice
        if (redirectChoice === 'stay') {
            return;
        }

        // If they previously chose to switch, redirect them directly
        if (redirectChoice === 'switch') {
            var newUrl = window.location.origin + '/in' + currentPath + window.location.search + window.location.hash;
            window.location.href = newUrl;
            return;
        }

        // Helper to setup and open the modal
        function showModal() {
            var $modal = $('#ulb-country-modal');
            if ($modal.length) {
                // Animate showing the modal by adding the active class
                setTimeout(function() {
                    $modal.addClass('active');
                }, 500);
                
                // Bind click handlers
                $('#ulb-btn-switch').on('click', function() {
                    setCookie('ulb_redirect_choice', 'switch', 30);
                    setCookie('ulb_visitor_country', 'IN', 30);
                    var newUrl = window.location.origin + '/in' + currentPath + window.location.search + window.location.hash;
                    window.location.href = newUrl;
                });
                
                $('#ulb-btn-stay, #ulb-modal-close-btn').on('click', function() {
                    setCookie('ulb_redirect_choice', 'stay', 30);
                    $modal.removeClass('active');
                });

                $modal.on('click', function(e) {
                    if ($(e.target).hasClass('ulb-modal-overlay')) {
                        setCookie('ulb_redirect_choice', 'stay', 30);
                        $modal.removeClass('active');
                    }
                });
            }
        }

        // Geolocation Check
        if (visitorCountry) {
            if (visitorCountry === 'IN') {
                showModal();
            }
        } else {
            // Call the WooCommerce AJAX geolocation endpoint
            $.ajax({
                url: ulb_geo_opt.ajax_url,
                type: 'POST',
                data: {
                    action: 'ulb_geolocate_user'
                },
                success: function(response) {
                    if (response.success && response.data && response.data.country) {
                        var country = response.data.country;
                        setCookie('ulb_visitor_country', country, 30); // Cache for 30 days
                        if (country === 'IN') {
                            showModal();
                        }
                    }
                }
            });
        }
    });
})(jQuery);
