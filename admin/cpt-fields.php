<?php

// Register fields for the CPT: Video Game, using ACF.

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_video-details',
		'title' => 'Video details',
		'fields' => array (
			array (
				'key' => 'field_58607fcb79ee8',
				'label' => 'Video description',
				'name' => 'video_description',
				'type' => 'wysiwyg',
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'br',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'vimeo-video',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
