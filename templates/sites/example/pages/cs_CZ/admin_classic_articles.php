<?php
return array (
	"name" => "Admin - users",
	"title" => "Administrační rozhraní (klasické) - Články",
	"breadcrumb_title" => "Články",
	"menu_title" => "Články",
	"URL_fragment" => "clanky",
	"layout" => "default",
	"is_admin_UI" => true,
	"meta_tags" => array(),
	"contents" =>
			array(
				array(
					"module_name" => "JetExample\\Articles",
					"controller_action" => "default",
					"output_position" => "",
					"output_position_required" => true,
					"output_position_order" => 1
				)
			)

);