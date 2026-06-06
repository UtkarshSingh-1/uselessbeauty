(function($) {
    $(document).ready(function() {
        var currentPath = window.location.pathname;
        
        // Prevent redirect loop
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

        var visitorCountry = getCookie('ulb_visitor_country');

        if (visitorCountry) {
            if (visitorCountry === 'IN') {
                var newUrl = window.location.origin + '/in' + currentPath + window.location.search + window.location.hash;
                window.location.href = newUrl;
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
                        setCookie('ulb_visitor_country', country, 1); // Save for 1 day
                        if (country === 'IN') {
                            var newUrl = window.location.origin + '/in' + currentPath + window.location.search + window.location.hash;
                            window.location.href = newUrl;
                        }
                    }
                }
            });
        }
    });
})(jQuery);
