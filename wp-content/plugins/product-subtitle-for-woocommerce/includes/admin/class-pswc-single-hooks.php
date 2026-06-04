<?php 

if ( ! defined( 'ABSPATH' ) ) :
    exit; // Exit if accessed directly
endif;

return apply_filters( 'pswc_get_single_hooks_option',
    array(

        'pswc_single_default_hooks'    => array(
            'disable-0'                                  => esc_html__( 'Disable Subtitle', 'product-subtitle-for-woocommerce' ),
            'woocommerce_before_single_product_summary-0'=> esc_html__( 'Top of Product Page', 'product-subtitle-for-woocommerce' ),
            'woocommerce_product_thumbnails-0'           => esc_html__( 'Below Product Slider (May Not Work with Some Themes)', 'product-subtitle-for-woocommerce' ),
            'woocommerce_single_product_summary-0'       => esc_html__( 'Before Product Title', 'product-subtitle-for-woocommerce' ),
            'woocommerce_single_product_summary-6'       => esc_html__( 'After Product Title', 'product-subtitle-for-woocommerce' ),
            'woocommerce_before_add_to_cart_form-10'     => esc_html__( 'After Short Description', 'product-subtitle-for-woocommerce' ),
            'woocommerce_before_add_to_cart_quantity-10' => esc_html__( 'Before Quantity Input Field', 'product-subtitle-for-woocommerce' ),
            'woocommerce_after_add_to_cart_quantity-10'  => esc_html__( 'After Quantity Input Field', 'product-subtitle-for-woocommerce' ),
            'woocommerce_before_add_to_cart_button-10'   => esc_html__( 'Before Add to Cart Button', 'product-subtitle-for-woocommerce' ),
            'woocommerce_after_add_to_cart_button-10'    => esc_html__( 'After Add to Cart Button', 'product-subtitle-for-woocommerce' ),
            'woocommerce_product_meta_end-10'            => esc_html__( 'After Product Meta Information', 'product-subtitle-for-woocommerce' ),
        ),
        'pswc_single_astra_hooks' => array(
            'disable-0'                              => esc_html__( 'Disable Subtitle', 'product-subtitle-for-woocommerce' ),
            'astra_woo_single_title_before-0'        => esc_html__( 'Before Title', 'product-subtitle-for-woocommerce' ),
            'astra_woo_single_title_after-0'         => esc_html__( 'After Title', 'product-subtitle-for-woocommerce' ),
            'astra_woo_single_price_before-0'        => esc_html__( 'Before Price', 'product-subtitle-for-woocommerce' ),
            'astra_woo_single_price_after-0'         => esc_html__( 'After Price', 'product-subtitle-for-woocommerce' ),
            'astra_woo_single_rating_before-0'       => esc_html__( 'Before Rating', 'product-subtitle-for-woocommerce' ),
            'astra_woo_single_rating_after-0'        => esc_html__( 'After Rating', 'product-subtitle-for-woocommerce' ),
            'astra_woo_single_add_to_cart_before-10'  => esc_html__( 'Before Add to Cart', 'product-subtitle-for-woocommerce' ),
            'astra_woo_single_add_to_cart_after-0'   => esc_html__( 'After Add to Cart', 'product-subtitle-for-woocommerce' ),
        ), 
    )
);