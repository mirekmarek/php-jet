<?php
$common_content = require "__common_content__.php";

return array (
	"name" => "Articles",
	"title" => "Články",
	"URL_fragment" => "clanky",
	"layout" => "default",
	"headers_suffix" => "",
	"body_prefix" => "",
	"body_suffix" => "",
	"meta_tags" => array(
		array(
			"attribute"   => "name",
			"attribute_value" => "description",
			"content" => "Články"
		),
	),
	"contents" => array_merge(
		array(
			array(
				"module_name" => "Jet\\Articles",
				"controller_action" => "default",
				"output_position" => "",
				"output_position_required" => true,
				"output_position_order" => 1
			)
		),
		$common_content
	)
);