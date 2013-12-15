<?php
return array (
	"name" => "Admin - users",
	"title" => "Administrační rozhraní (klasické) - Uživatelé",
	"URL_fragment" => "users",
	"layout" => "default",
	"is_admin_UI" => true,
	"meta_tags" => array(),
	"contents" =>
			array(
				array(
					"module_name" => "Jet\\AdminUsers",
					"controller_action" => "default",
					"output_position" => "",
					"output_position_required" => true,
					"output_position_order" => 1
				)
			)

);