jQuery(function ($) {
    let uwpmc_ajx_url = uwpmc_data.ajax_url;
    let uwpmc_is_cart = (uwpmc_data.is_cart == '1');
    let uwpmc_is_checkout = (uwpmc_data.is_checkout == '1');
    let uwpmc_has_cart_block = (uwpmc_data.has_cart_block == '1');
    let uwpmc_has_checkout_block = (uwpmc_data.has_checkout_block == '1');
    let uwpmc_messages = uwpmc_data.messages || [];
    let uwpmc_auto_open_slider = (uwpmc_data.auto_open_slider == '1');
    let uwpmc_nonce = uwpmc_data.nonce || '';

    window.uwpmc_spinner = {
        // show spinner
        show: function (section) {
            if (typeof section === 'string') {
                section = $(section).first();
            }
            if (section.block) {
                $(section).block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
            }
        },

        // hide spinner
        hide: function (section) {
            if (typeof section === 'string') {
                section = $(section).first();
            }
            if (section.unblock) {
                $(section).unblock();
            }
        }
    }

    window.uwpmc_actions = {

        // to update page fragments.
        update_fragments: function () {
            if (uwpmc_is_cart) {
                jQuery(document.body).trigger('wc_update_cart');
            } else if (uwpmc_is_checkout) {
                jQuery(document.body).trigger('update_checkout');
            } else {
                jQuery(document.body).trigger('wc_fragment_refresh');
            }

            if (uwpmc_has_cart_block || uwpmc_has_checkout_block) {
                setTimeout(function () {
                    jQuery(document.body).trigger('added_to_cart', {});
                }, 0);
            }
        },

        // to show notification message.
        notify: function (message, status) {
            let classname = status == 'error' ? 'uwpmc-error' : 'uwpmc-success';
            $('#uwpmc-message').html(message);
            $('#uwpmc-message').addClass(classname);
            $('#uwpmc-notification').show();
            setTimeout(function () {
                $('#uwpmc-message').removeClass(classname);
                $('#uwpmc-notification').hide();
            }, 3000);
        },

        // to add product to cart.
        add_product_to_cart: function (product, content) {
            let product_id = product.data('product_id');
            if (!product_id) {
                return;
            }

            $.ajax({
                type: 'post',
                url: uwpmc_ajx_url,
                data: {
                    action: 'uwpmc_ajax',
                    method: 'add_product_to_cart',
                    product_id: product_id,
                    nonce: uwpmc_nonce || '',
                },
                beforeSend: function () {
                    uwpmc_spinner.show('.uwpmc-cart-contents');
                    uwpmc_spinner.show('.uwpmc-cart-totals');
                },
                success: function (response) {
                    if (response.data.status == 'success' && response.data.added && response.data.cart_body != '') {
                        uwpmc_actions.update_fragments();
                        content.html(response.data.cart_body);
                        $('.uwpmc-widget-container').find('.uwpmc-widget-qty').text(response.data.cart_items_qty);
                    } else {
                        uwpmc_actions.notify(uwpmc_messages['error'], response.data.status);
                    }
                    uwpmc_actions.slide_banners();
                },
                complete: function () {
                    uwpmc_spinner.hide('.uwpmc-cart-contents');
                    uwpmc_spinner.hide('.uwpmc-cart-totals');
                },
            });
        },

        // remove item from cart.
        remove_item_from_cart: function (product, content) {
            let cart_item_key = product.data('cart_item_key');
            if (!cart_item_key) {
                return;
            }

            $.ajax({
                type: 'post',
                url: uwpmc_ajx_url,
                data: {
                    action: 'uwpmc_ajax',
                    method: 'remove_item_from_cart',
                    cart_item_key: cart_item_key,
                    nonce: uwpmc_nonce || '',
                },
                beforeSend: function () {
                    uwpmc_spinner.show('.uwpmc-cart-contents');
                    uwpmc_spinner.show('.uwpmc-cart-totals');
                },
                success: function (response) {
                    if (response.data.status == 'success' && response.data.removed && response.data.cart_body != '') {
                        uwpmc_actions.update_fragments();
                        content.html(response.data.cart_body);
                        $('.uwpmc-widget-container').find('.uwpmc-widget-qty').text(response.data.cart_items_qty);
                    } else {
                        uwpmc_actions.notify(uwpmc_messages['error'], response.data.status);
                    }
                    uwpmc_actions.slide_banners();
                },
                complete: function () {
                    uwpmc_spinner.hide('.uwpmc-cart-contents');
                    uwpmc_spinner.hide('.uwpmc-cart-totals');
                },
            });
        },

        // to update item quantity.
        update_item_quantity: function (product, content, action) {
            let cart_item_key = product.data('cart_item_key');
            let current_quantity = parseInt(product.find('.uwpmc-quantity-input').val());
            if (!cart_item_key || isNaN(current_quantity)) {
                return;
            }

            $.ajax({
                type: 'post',
                url: uwpmc_ajx_url,
                data: {
                    action: 'uwpmc_ajax',
                    method: 'update_item_quantity',
                    cart_item_key: cart_item_key,
                    current_quantity: current_quantity,
                    quantity_action: action,
                    nonce: uwpmc_nonce || '',
                },
                beforeSend: function () {
                    uwpmc_spinner.show('.uwpmc-cart-contents');
                    uwpmc_spinner.show('.uwpmc-cart-totals');
                },
                success: function (response) {
                    if (response.data.status == 'success' && response.data.quantity_updated && response.data.cart_body != '') {
                        uwpmc_actions.update_fragments();
                        content.html(response.data.cart_body);
                        $('.uwpmc-widget-container').find('.uwpmc-widget-qty').text(response.data.cart_items_qty);
                    } else if (!response.data.quantity_updated && response.data.message) {
                        uwpmc_actions.notify(response.data.message, response.data.status);
                    } else {
                        uwpmc_actions.notify(uwpmc_messages['error'], response.data.status);
                    }
                    uwpmc_actions.slide_banners();
                },
                complete: function () {
                    uwpmc_spinner.hide('.uwpmc-cart-contents');
                    uwpmc_spinner.hide('.uwpmc-cart-totals');
                },
            });
        },

        // to apply coupon.
        apply_coupon: function (content) {
            const coupon_code = content.find('#uwpmc-coupon-input').val();
            if (!coupon_code) {
                return;
            }

            $.ajax({
                type: 'post',
                url: uwpmc_ajx_url,
                data: {
                    action: 'uwpmc_ajax',
                    method: 'apply_coupon',
                    coupon_code: coupon_code,
                    nonce: uwpmc_nonce || '',
                },
                beforeSend: function () {
                    uwpmc_spinner.show('.uwpmc-cart-totals');
                },
                success: function (response) {
                    if (response.data.status == 'success' && response.data.applied && response.data.cart_body != '') {
                        uwpmc_actions.update_fragments();
                        content.html(response.data.cart_body);
                        if (response.data.message) {
                            uwpmc_actions.notify(response.data.message, response.data.status);
                        }
                    } else if (!response.data.applied && response.data.message) {
                        uwpmc_actions.notify(response.data.message, response.data.status);
                    } else {
                        uwpmc_actions.notify(uwpmc_messages['fallback'], response.data.status);
                    }

                    // to display coupon section
                    $('#uwpmc-cart-sidebar #uwpmc-hide-coupons').trigger('click');

                    uwpmc_actions.slide_banners();
                },
                complete: function () {
                    uwpmc_spinner.hide('.uwpmc-cart-totals');
                },
            });
        },

        // to remove coupon.
        remove_coupon: function (section, content) {
            const coupon_code = section.data('coupon');
            if (!coupon_code) {
                return;
            }

            $.ajax({
                type: 'post',
                url: uwpmc_ajx_url,
                data: {
                    action: 'uwpmc_ajax',
                    method: 'remove_coupon',
                    coupon_code: coupon_code,
                    nonce: uwpmc_nonce || '',
                },
                beforeSend: function () {
                    uwpmc_spinner.show('.uwpmc-cart-totals');
                },
                success: function (response) {
                    if (response.data) {
                        if (response.data.status == 'success' && response.data.removed && response.data.cart_body != '') {
                            uwpmc_actions.update_fragments();
                            content.html(response.data.cart_body);
                            if (response.data.message) {
                                uwpmc_actions.notify(response.data.message, response.data.status);
                            }
                        } else if (!response.data.removed && response.data.message) {
                            uwpmc_actions.notify(response.data.message, response.data.status);
                        } else {
                            uwpmc_actions.notify(uwpmc_messages['fallback'], response.data.status);
                        }

                        // to display coupon section
                        $('#uwpmc-cart-sidebar #uwpmc-hide-coupons').trigger('click');

                        uwpmc_actions.slide_banners();
                    }
                },
                complete: function () {
                    uwpmc_spinner.hide('.uwpmc-cart-totals');
                },
            });
        },

        // to refresh cart.
        refresh_fragments: function (show_slider = false, process_notice = false) {
            $.ajax({
                type: 'post',
                url: uwpmc_ajx_url,
                data: {
                    action: 'uwpmc_ajax',
                    method: 'get_sidebar_fragments',
                    process_notice: process_notice,
                    nonce: uwpmc_nonce || '',
                },
                success: function (response) {
                    if (response.data.cart_body != '') {
                        $('#uwpmc-cart-sidebar .uwpmc-sidebar .uwpmc-body').html(response.data.cart_body);
                        $('.uwpmc-widget-container').find('.uwpmc-widget-qty').text(response.data.cart_items_qty);
                        if ($('#uwpmc-cart-button').hasClass('uwpmc-active-page')) {
                            $('.uwpmc-cart-block').show();
                            $('.uwpmc-offers-block').hide();
                        } else if ($('#uwpmc-offers-button').hasClass('uwpmc-active-page')) {
                            $('.uwpmc-offers-block').show();
                            $('.uwpmc-cart-block').hide();
                        }

                        if (show_slider && uwpmc_auto_open_slider) {
                            $('.uwpmc-widget-container').trigger('click');
                        }

                        if (response.data.message) {
                            uwpmc_actions.notify(response.data.message, response.data.status || '');
                        }
                        uwpmc_actions.slide_banners();
                    }
                },
            });
        },

        // sidebar slider function.
        sidecart_slider: function (value) {
            let sidebar = $('.uwpmc-sidebar');
            if (sidebar.hasClass('sidebar-left')) {
                sidebar.animate({
                    left: value,
                    behaviour: 'smooth',
                }, 'slow');
            } else {
                sidebar.animate({
                    right: value,
                    behaviour: 'smooth',
                }, 'slow');
            }
            let sidebar_width = (value === '0px') ? '100%' : 'auto';
            setTimeout(function () {
                $('#uwpmc-cart-sidebar').css('width', sidebar_width);
            }, 300);
        },

        // run banners
        slide_banners: function () {
            let leftWidth = 0;
            let slider = $('.uwpmc-banners');
            if (slider.length > 0 && !slider.data('sliding')) {
                slider.data('sliding', true);
                setInterval(function () {
                    leftWidth += slider[0].clientWidth;
                    if ((leftWidth + 10) < slider[0].scrollWidth) {
                        slider.animate({
                            scrollLeft: '+=' + slider[0].clientWidth,
                            behaviour: 'smooth'
                        }, 100);
                    } else {
                        slider.animate({
                            scrollLeft: '=' + 0,
                            behaviour: 'smooth'
                        });
                        leftWidth = 0;
                    }
                }, 3000);
            }
        }
    }

    // add product  to cart.
    $(document).on('click', '.uwpmc-recommendation .uwpmc-related-product-row .uwpmc-add-recommended-item ', function () {
        uwpmc_actions.add_product_to_cart($(this).closest('.uwpmc-related-product-row'), $(this).closest('#uwpmc-cart-sidebar .uwpmc-sidebar .uwpmc-body'));
    });

    // to remove product from cart.
    $(document).on('click', '.uwpmc-items .uwpmc-item .uwpmc-remove-item ', function () {
        uwpmc_actions.remove_item_from_cart($(this).closest('.uwpmc-item'), $(this).closest('#uwpmc-cart-sidebar .uwpmc-sidebar .uwpmc-body'));
    });

    // to add quantity (add).
    $(document).on('click', '.uwpmc-items .uwpmc-item .uwpmc-quantity-container .uwpmc-quantity-plus', function (event) {
        uwpmc_actions.update_item_quantity($(this).closest('.uwpmc-item'), $(this).closest('#uwpmc-cart-sidebar .uwpmc-sidebar .uwpmc-body'), 'plus');
    });

    // to remove quantity (minus).
    $(document).on('click', '.uwpmc-items .uwpmc-item .uwpmc-quantity-container .uwpmc-quantity-minus', function () {
        uwpmc_actions.update_item_quantity($(this).closest('.uwpmc-item'), $(this).closest('#uwpmc-cart-sidebar .uwpmc-sidebar .uwpmc-body'), 'minus');
    });

    // preform action when change quantity by input.
    $(document).on('change', '.uwpmc-items .uwpmc-item .uwpmc-quantity-container .uwpmc-quantity-input', function () {
        if (isNaN(parseInt($(this).attr('max'), 10)) || parseInt($(this).attr('max'), 10) >= parseInt($(this).val())) {
            uwpmc_actions.update_item_quantity($(this).closest('.uwpmc-item'), $(this).closest('#uwpmc-cart-sidebar .uwpmc-sidebar .uwpmc-body'), 'custom');
        } else {
            uwpmc_actions.notify(uwpmc_messages['out_of_stock'], 'error');
        }
    });

    // to apply coupon.
    $(document).on('click', '#uwpmc-cart-sidebar #uwpmc-apply-coupon', function () {
        uwpmc_actions.apply_coupon($(this).closest('#uwpmc-cart-sidebar .uwpmc-sidebar .uwpmc-body'));
    });

    // to remove coupon.
    $(document).on('click', '#uwpmc-cart-sidebar #uwpmc-remove-coupon', function () {
        uwpmc_actions.remove_coupon($(this), $(this).closest('#uwpmc-cart-sidebar .uwpmc-sidebar .uwpmc-body'));
    });

    // to open sidebar.
    $('#uwpmc-cart-sidebar').click(function (event) {
        let side = $(this).hasClass('sidebar-left') ? 'left' : 'right';
        if ($(this).css(side) === '0px' && event.target.closest('.uwpmc-sidebar') === null) {
            uwpmc_actions.sidecart_slider('-1000px')
        }
    });

    // to refresh mini-cart when product added.
    $(document.body).on('added_to_cart', function (event, fragments, cart_hash, button) {
        if (button && button.length) {
            uwpmc_actions.refresh_fragments(true);
        }
    });

    // to refresh mini-cart when cart updated.
    $(document.body).on('updated_cart_totals', function () {
        uwpmc_actions.refresh_fragments();
    });


    // to refresh mini-cart when checkout page updated.
    $(document.body).on('updated_checkout', function () {
        uwpmc_actions.refresh_fragments();
    });

    // to refresh mini-cart when UpsellWP fragment refreshed.
    $(document.body).on('cuw_fragment_refreshed', function () {
        uwpmc_actions.refresh_fragments(false, true);
    });

    // to toggle sidebar.
    $(document).on('click', '.uwpmc-widget-container, #uwpmc-close-cart', function () {
        let side = $('.uwpmc-sidebar').hasClass('sidebar-left') ? 'left' : 'right';
        let value = $('.uwpmc-sidebar').css(side) === '0px' ? '-1000px' : '0px';
        uwpmc_actions.sidecart_slider(value);
        $('#uwpmc-cart-sidebar #uwpmc-hide-coupons').trigger('click');
    });

    // toggle tab.
    $(document).on('click', '.uwpmc-tab-button', function () {
        if (!$(this).hasClass('uwpmc-active-page')) {
            $('.uwpmc-cart-block').toggle();
            $('.uwpmc-offers-block').toggle();
            $('#uwpmc-cart-button, #uwpmc-offers-button').toggleClass('uwpmc-active-page');
        }
    });

    // to slide banners.
    $(document).ready(function () {
        uwpmc_actions.slide_banners();
    });
});