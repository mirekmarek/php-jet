<?php
/**
 * @see Jet\Application\Modules_Module_Info
 */
return array(
	"API_version" => 201208,
	
	"label" => "Test Module 2",

	"vendor" => "Vendor",
	
	"types" => array(Jet\Application_Modules_Module_Info::MODULE_TYPE_GENERAL),

	"description" => "Test module 2...",

	"require" => array( "Vendor\\TestModule" ),

	"factory_overload_map" => array()
);