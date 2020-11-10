<?php
namespace JetStudio;

use Jet\DataModel_Backend;
use Jet\Http_Headers;
use Jet\IO_File;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();
if(!$current) {
	die();
}

header('Content-Type: text/plain');

$class = $current->createClass();

if(!$class) {
	die();
}

echo $class;

die();