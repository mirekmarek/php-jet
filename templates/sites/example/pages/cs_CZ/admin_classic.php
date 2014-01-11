<?php
return array (
	"name" => "Admin",
	"title" => "Administrační rozhraní (klasické)",
	"URL_fragment" => "klasicke",
	"layout" => "default",
	"is_admin_UI" => true,
	"meta_tags" => array(),
	"contents" => array(
					array(
						"module_name" => "JetExample\\AdminUIManager",
						"controller_action" => "classic_default",
						"output_position" => "",
						"output_position_required" => true,
						"output_position_order" => 0
					)
			)
);