<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$current = Modules::getCurrentModule();

$GET = Http_Request::GET();
$ok = false;

/*
if(
	$current &&
	($site_id=$GET->getString('site')) &&
	($page_id=$GET->getString('page')) &&
	( $page=$current->getPage( $site_id, $page_id ) )
) {

	$what = Pages::whatToEdit();

	$form = $page->{"getEditForm_$what"}();

	$form->setName( 'module_page_edit_form_'.$site_id.'_'.$page_id );

	if($page->{"catchEditForm_{$what}"}()) {
		$form = $page->getEditForm_main();

		if( Modules::save( $form ) ) {
			$ok = true;
			$form->setCommonMessage(
				UI_messages::createSuccess( Tr::_('Saved ...', []) )
			);
		}

	}

	$view = Application::getView();
	$view->setVar('page', $page);

	AJAX::formResponse(
		$ok,
		[
			'page_'.$site_id.'_'.$page_id.'_head' => $view->render('module_edit/pages/item-head'),
			'page_'.$site_id.'_'.$page_id => $view->render('module_edit/pages/item-body')
		]
	);


}
*/