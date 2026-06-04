<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	"name" => esc_html__("Project Categories", "salient-core"),
	"base" => "nectar_project_categories",
	"icon" => "icon-wpb-text-with-icon",
	"category" => __('Content', 'salient-core'),
	"params" => array(
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Style", "salient-core"),
			"param_name" => "style",
			"admin_label" => true,
			"value" => array(
				"Default" => "default",
				"Outline" => "outline"
			),
			'save_always' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Category Display', 'salient-core' ),
            'param_name' => 'category_display',
            'value' => array(
              esc_html__('Default (Display All)', 'salient-core') => 'default',
              esc_html__('Parent Categories Only', 'salient-core') => 'parent_only'
            ),
            'save_always' => true,
        ),
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Link Categories to Archives", "salient-portfolio"),
			"param_name" => "link_categories",
			"value" => Array(esc_html__("Yes, please", "salient-portfolio") => 'true'),
			'edit_field_class' => 'vc_col-xs-12 salient-fancy-checkbox',
		),
		array(
			"type" => "dropdown",
			"heading" => '<span class="group-title">' . esc_html__("Alignment", "salient-core") . "</span>",
			"param_name" => "alignment_desktop",
			"value" => array(
				esc_html__('Left', 'salient-core') => 'left',
				esc_html__('Center', 'salient-core') => 'center',
				esc_html__('Right', 'salient-core') => 'right',
			),
			'save_always' => true,
			"edit_field_class" => "desktop alignment-device-group",
		),
	
		array(
			"type" => "dropdown",
			"heading" => '',
			"param_name" => "alignment_tablet",
			"value" => array(
				esc_html__('Default', 'salient-core') => 'default',
				esc_html__('Left', 'salient-core') => 'left',
				esc_html__('Center', 'salient-core') => 'center',
				esc_html__('Right', 'salient-core') => 'right',
			),
			'save_always' => true,
			"edit_field_class" => "tablet alignment-device-group",
		),
		array(
			"type" => "dropdown",
			"heading" => '',
			"param_name" => "alignment_phone",
			"value" => array(
				esc_html__('Default', 'salient-core') => 'default',
				esc_html__('Left', 'salient-core') => 'left',
				esc_html__('Center', 'salient-core') => 'center',
				esc_html__('Right', 'salient-core') => 'right',
			),
			'save_always' => true,
			"edit_field_class" => "phone alignment-device-group",
		),	
		array(
			"type" => "dropdown",
			"class" => "",
			'save_always' => true,
			"heading" => "Text Font Style",
			"param_name" => "font_style",
			"value" => array(
				"Paragraph" => "p",
				"Label" => "label",
				"H1" => "h1",
				"H2" => "h2",
				"H3" => "h3",
				"H4" => "h4",
				"H5" => "h5",
				"H6" => "h6",
			)
		),
    )
);
