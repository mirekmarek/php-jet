<?php
namespace JetStudio;

use Jet\Http_Headers;

$property = DataModels::getCurrentProperty();

if(!$property) {
	Application::end();
}


if( ($new_property = $property->catchEditForm()) ) {

	//TODO:

}

Http_Headers::reload([], ['action']);
