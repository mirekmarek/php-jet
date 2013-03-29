<?php
$common_content = require "__common_admin_content__.php";

return array (
	"name" => "Admin - roles",
	"title" => "Administration Interface - Roles",
	"URL_fragment" => "roles",
	"layout" => "default",
	"is_admin_UI" => true,
	"meta_tags" => array(),
	"contents" => array_merge(
			array(
				array(
					"module_name" => "Jet\\AdminRoles",
					"controller_action" => "default",
					"output_position" => "",
					"output_position_required" => true,
					"output_position_order" => 1
				)
			),
			$common_content
		)

);