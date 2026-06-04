<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'PSWC_Guternuburg_Block' ) ) :

class PSWC_Guternuburg_Block {

    public function __construct() {
        add_action( 'init', [ $this, 'register_block' ] );
        add_filter( 'rest_pre_insert_post', [ $this, 'sanitize_block_on_save' ], 10, 2 );
    }

    /**
     * Registers the block and enqueues scripts.
     */
    public function register_block() {

        wp_register_script(
            'pswc-product-subtitle-block-editor',
            PSWC_URL . '/build/block.js',
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wc-product-editor' ),
            PSWC_VERSION,
            true
        );

        register_block_type( 'pswc/product-subtitle-block', array(
            'editor_script'   => 'pswc-product-subtitle-block-editor',
            'render_callback' => [ $this, 'render_block' ],
            'attributes'      => $this->get_block_attributes(),
        ) );
    }

    /**
     * Defines block attributes.
     */
    private function get_block_attributes() {
        return array(
            'htmlTag' => array(
                'type'    => 'string',
                'default' => 'div',
            ),
            'fallbackText' => array(
                'type'    => 'string',
                'default' => 'Default Subtitle',
            ),
            'textColor' => array(
                'type'    => 'string',
                'default' => 'inherit',
            ),
            'backgroundColor' => array(
                'type'    => 'string',
                'default' => 'inherit',
            ),
        );
    }

    /**
     * Renders the block output on the frontend.
     */
    public function render_block( $attributes ) {
        $allowed_tags = array( 'div', 'p', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
        $tag = isset( $attributes['htmlTag'] ) && in_array( $attributes['htmlTag'], $allowed_tags, true )
            ? esc_html( $attributes['htmlTag'] )
            : 'div';

        $fallback_text      = isset( $attributes['fallbackText'] ) ? esc_html( $attributes['fallbackText'] ) : 'Default Subtitle';
        $text_color         = isset( $attributes['textColor'] ) ? esc_attr( $attributes['textColor'] ) : 'inherit';
        $background_color   = isset( $attributes['backgroundColor'] ) ? esc_attr( $attributes['backgroundColor'] ) : 'transparent';

        $product_subtitle = $this->get_product_subtitle();
        $text = ! empty( $product_subtitle ) ? $product_subtitle : $fallback_text;

        return sprintf(
            '<%1$s class="pswc-product-subtitle" style="color: %2$s; background-color: %3$s;">%4$s</%1$s>',
            esc_attr( $tag ),
            esc_attr( $text_color ),
            esc_attr( $background_color ),
            wp_kses_post( $text )
        );
    }

    /**
     * Intercepts and sanitizes post content before save (REST API).
     */
    public function sanitize_block_on_save( $prepared_post, $request ) {
        if ( isset( $prepared_post->post_content ) && str_contains( $prepared_post->post_content, 'wp:pswc/product-subtitle-block' ) ) :
            $prepared_post->post_content = $this->sanitize_block_content( $prepared_post->post_content );
        endif;
        return $prepared_post;
    }

    /**
     * Parses and sanitizes pswc block attributes to prevent saving disallowed htmlTag values.
     */
    private function sanitize_block_content( $content ) {
        $allowed_tags = array( 'div', 'p', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
        $blocks = parse_blocks( $content );

        $sanitized_blocks = array_map( function( $block ) use ( $allowed_tags ) {
            if ( $block['blockName'] === 'pswc/product-subtitle-block' && isset( $block['attrs']['htmlTag'] ) ) :
                if ( ! in_array( $block['attrs']['htmlTag'], $allowed_tags, true ) ) :
                    $block['attrs']['htmlTag'] = 'div';
                endif;
            endif;
            return $block;
        }, $blocks );

        return serialize_blocks( $sanitized_blocks );
    }

    /**
     * Gets the WooCommerce product subtitle.
     */
    private function get_product_subtitle() {
        if ( class_exists( 'WooCommerce' ) && is_product() ) :
            global $product;
            if ( $product instanceof WC_Product ) :
                return get_post_meta( $product->get_id(), 'pswc_subtitle', true );
            endif;
        endif;
        return null;
    }
}

new PSWC_Guternuburg_Block();
endif;