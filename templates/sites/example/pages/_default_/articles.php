<?php
$common_content = require "__common_content__.php";

return array (
	"name" => "Articles",
	"title" => "Articles",
	"URL_fragment" => "articles",
	"layout" => "default",
	"headers_suffix" => "",
	"body_prefix" => "",
	"body_suffix" => "",
	"meta_tags" => array(
		array(
			"attribute"   => "name",
			"attribute_value" => "description",
			"content" => "Articles"
		),
	),
	"contents" => array_merge(
		array(
			array(
				"module_name" => "JetExample\\Articles",
				"controller_action" => "default",
				"output_position" => "",
				"output_position_required" => true,
				"output_position_order" => 1
			)
		),
		$common_content
	)
);