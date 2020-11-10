<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;
use Jet\AJAX;

$current = Modules::getCurrentModule();

$form = Modules_Manifest::getPageCreateForm();
$ok = false;

if(
	$current &&
	($new_page=$current->catchCratePageForm())
) {
	if( Modules::save() ) {
		$ok = true;
		UI_messages::success( Tr::_('Page <b>%page%</b> has been added', ['page'=>$new_page->getName()]) );

	}
}

$view = Application::getView();

$data = [];

$snippets = [
	'add_page_form_area' => $view->render('modules/module_edit/add_page_form')
];


AJAX::formResponse(
	$ok,
	$snippets,
	$data
);
