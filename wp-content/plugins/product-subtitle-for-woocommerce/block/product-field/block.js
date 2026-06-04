const { __ } = wp.i18n;
import { useState } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import { useWooBlockProps } from '@woocommerce/block-templates';
const { TextControl } = wp.components;

registerBlockType( 'pswc/product-subtitle-form-field-block', {
    title: 'Product Subtitle form field',
    attributes: {
        pswc_subtitle: {
            type: "string",
            __experimentalRole: "content",
            source: "text",
        }
    },
    supports: {
        align: false,
        html: false,
        multiple: true,
        reusable: false,
        inserter: false,
        lock: false,
        __experimentalToolbar: false
    },
    edit: ({ attributes, context }) => {
        const [ value, setValue ] = window.wc.productEditor.__experimentalUseProductEntityProp(
            'meta_data.pswc_subtitle',
            {
                postType: context.postType,
                fallbackValue: '',
            }
        );

        return (
            <div {...attributes}>
                <TextControl
                    label={__('Product Subtitle', 'product-subtitle-for-woocommerce' )}
                    placeholder={__( 'e.g. Product by SpiderWares', 'product-subtitle-for-woocommerce' )}
                    value={value}
                    onChange={setValue }
                />
            </div>
        );
    },
} );
