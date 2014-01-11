<?php
return array (
	"name" => "Admin",
	"title" => "Administration Interface - Signpost",
	"URL_fragment" => "admin",
	"layout" => "default",
	"is_admin_UI" => true,
	"meta_tags" => array(),
	"contents" => array(
					array(
						"module_name" => "JetExample\\AdminUIManager",
						"controller_action" => "signpost",
						"output_position" => "",
						"output_position_required" => true,
						"output_position_order" => 0
					)
			)
);