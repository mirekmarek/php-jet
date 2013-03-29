<?php
$common_content = require "__common_admin_content__.php";

return array (
	"name" => "Admin - users",
	"title" => "Administration Interface - Users",
	"URL_fragment" => "users",
	"layout" => "default",
	"is_admin_UI" => true,
	"meta_tags" => array(),
	"contents" => array_merge(
			array(
				array(
					"module_name" => "Jet\\AdminUsers",
					"controller_action" => "default",
					"output_position" => "",
					"output_position_required" => true,
					"output_position_order" => 1
				)
			),
			$common_content
		)

);