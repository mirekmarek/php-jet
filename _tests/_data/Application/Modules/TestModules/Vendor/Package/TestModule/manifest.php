<?php
/**
 * @see Jet\Application_Modules_Module_Info
 */
return array(
	"API_version" => 201208,

	"vendor" => "Vendor",
	
	"label" => "Test Module 1",
	"types" => array(Jet\Application_Modules_Module_Info::MODULE_TYPE_GENERAL),
	"description" => "Test module 1...",

	"require" => array(),

	"factory_overload_map" => array(

	),

	"signals_callbacks" => array(
		"/test/ack" => "testAck",
	),
	
	"signals" => array(
		"/test/received" => "Test signal 1",
		"/test/multiple" => "Test signal 2"
	)
    
);