<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Elementor Widget for Product Subtitle with Custom HTML Tag and Styling
 */
if ( ! class_exists( 'PSWC_Product_Subtitle_Widget' ) ) {

    class PSWC_Product_Subtitle_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'pswc_product_subtitle_widget';
        }

        public function get_title() {
            return __( 'Product Subtitle', 'product-subtitle-for-woocommerce' );
        }

        public function get_icon() {
            return 'eicon-product-title'; // Elementor icon for the widget.
        }

        public function get_categories() {
            return [ 'basic' ]; // You can change the category based on your need.
        }

        public function get_keywords() {
            return [ 'html', 'tag', 'style', 'subtitle', 'custom' ];
        }

        protected function register_controls() {  // Changed to register_controls

            // Start Section for Content
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __( 'Content', 'product-subtitle-for-woocommerce' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            // Add control for HTML tag selection from filtered list.
            $this->add_control(
                'html_tag',
                [
                    'label' => __( 'HTML Tag', 'product-subtitle-for-woocommerce' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => apply_filters( 'pswc_supported_html_tags',		
                        array(
                            'i'      => esc_html( '<i>' ),
                            'p'      => esc_html( '<p>' ),
                            'mark'   => esc_html( '<mark>' ),
                            'span'   => esc_html( '<span>' ),
                            'small'  => esc_html( '<small>' ),
                            'strong' => esc_html( '<strong>' ),
                            'div'    => esc_html( '<div>' ),
                            'h1'     => esc_html( '<h1>' ),
                            'h2'     => esc_html( '<h2>' ),
                            'h3'     => esc_html( '<h3>' ),
                            'h4'     => esc_html( '<h4>' ),
                            'h5'     => esc_html( '<h5>' ),
                            'h6'     => esc_html( '<h6>' ),
                        )
                    ),
                    'default' => 'div',
                ]
            );

            // Add a fallback text input in case the product subtitle isn't available.
            $this->add_control(
                'fallback_text',
                [
                    'label' => __( 'Fallback Subtitle Text', 'product-subtitle-for-woocommerce' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __( 'Default Subtitle', 'product-subtitle-for-woocommerce' ),
                    'placeholder' => __( 'Enter your fallback subtitle', 'product-subtitle-for-woocommerce' ),
                ]
            );

            // Add a fallback text input in case the product subtitle isn't available.
            $this->add_control(
                'product_id',
                [
                    'label' => __( 'Product ID', 'product-subtitle-for-woocommerce' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'description' => __( 'Enter a product ID to retrieve its subtitle; if it\'s empty, the current product subtitle will be render. This works on archive product pages, including loops or queries, based on global product variables.', 'product-subtitle-for-woocommerce' ),
                ]
            );

            $this->end_controls_section();

            // Start Style Section
            $this->start_controls_section(
                'style_section',
                [
                    'label' => __( 'Style', 'product-subtitle-for-woocommerce' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            // Add typography control
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'typography',
                    'label' => __( 'Typography', 'product-subtitle-for-woocommerce' ),
                    'selector' => '{{WRAPPER}} .product-subtitle', // Targeting the .product-subtitle class instead of CURRENT_TAG
                ]
            );

            // Add color control
            $this->add_control(
                'text_color',
                [
                    'label' => __( 'Text Color', 'product-subtitle-for-woocommerce' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .product-subtitle' => 'color: {{VALUE}}', // Apply text color to the .product-subtitle class
                    ],
                ]
            );

            // Add background color control
            $this->add_control(
                'background_color',
                [
                    'label' => __( 'Background Color', 'product-subtitle-for-woocommerce' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .product-subtitle' => 'background-color: {{VALUE}}', // Apply background color to the .product-subtitle class
                    ],
                ]
            );

            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();
        
            // Get settings and defaults
            $tag        = ! empty( $settings['html_tag'] ) ? $settings['html_tag'] : 'div';
            $product_id = ! empty( $settings['product_id'] ) && $settings['product_id'] ? $settings['product_id'] : $this->get_product_id();
            $text       = $this->get_product_subtitle($product_id) ?: $settings['fallback_text'];
        
            // Render HTML
            printf(
                '<%1$s class="product-subtitle product-subtitle-%3$d">%2$s</%1$s>',
                esc_html($tag),
                esc_html($text),
                esc_html($product_id)
            );
        }
        
        /**
         * Retrieve current product ID
         */
        private function get_product_id() {
            global $product;
            return (is_product() && $product instanceof WC_Product)
                ? $product->get_id()
                : null;
        }

        /**
         * Retrieve product subtitle from metadata
         */
        private function get_product_subtitle($product_id = 0) {
            return $product_id ? get_post_meta($product_id, 'pswc_subtitle', true) : null;
        }
        
        protected function _content_template() {
            ?>
            <#
            var tag = settings.html_tag;
            var fallbackText = settings.fallback_text;
            var text = fallbackText; // Placeholder, dynamic rendering will handle product subtitle.
            var textColor = settings.text_color;
            var backgroundColor = settings.background_color;
            #>
            <{{{ tag }}}>
                {{{ text }}}
            </{{{ tag }}}>
            <?php
        }
    }

}