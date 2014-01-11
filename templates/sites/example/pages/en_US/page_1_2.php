<?php
$common_content = require "__common_content__.php";

return array (
	"name" => "Homepage",
	"title" => "page-1-2 - title",
	"URL_fragment" => "page-1-2",
	"layout" => "default",
	"headers_suffix" => "Header suffix: en_US ",
	"body_prefix" => "Body prefix: en_US",
	"body_suffix" => "Body suffix: en_US",
	"meta_tags" => array(
			array(
				"attribute"   => "Meta1attribute",
				"attribute_value" => "Meta 1 attribute value",
				"content" => "Meta 1 content"
			),
			array(
				"attribute"   => "Meta2attribute",
				"attribute_value" => "Meta 2 attribute value",
				"content" => "Meta 2 content"
			),
			array(
				"attribute"   => "Meta3attribute",
				"attribute_value" => "Meta 3 attribute value",
				"content" => "Meta 3 content"
			),
	 ),
	"contents" => array_merge(
			$common_content,
			array(
				array(
					"module_name" => "JetExample\\TestModule",
					"controller_action" => "test_action2",
					"output_position" => "right",
					"output_position_required" => true,
					"output_position_order" => 1
				),
				array(
					"module_name" => "JetExample\\TestModule2",
					"controller_action" => "test_action1",
					"output_position" => "top",
					"output_position_required" => true,
					"output_position_order" => 1
				),
				array(
					"module_name" => "JetExample\\TestModule2",
					"controller_action" => "test_action2",
					"output_position" => "",
					"output_position_required" => true,
					"output_position_order" => 1
				)
			)
		)
);