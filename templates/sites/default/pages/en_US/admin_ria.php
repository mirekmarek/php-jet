<?php
return array (
	"name" => "Admin",
	"title" => "Administration Interface (RIA)",
	"URL_fragment" => "ria",
	"layout" => "ria",
	"is_admin_UI" => true,
	"meta_tags" => array(),
	"contents" => array(
					array(
						"module_name" => "Jet\\DefaultAdminUI",
						"controller_action" => "ria_default",
						"output_position" => "",
						"output_position_required" => true,
						"output_position_order" => 0
					)
			)
);