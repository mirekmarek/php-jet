<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$form = Pages_Page::getCreateForm();
$ok = false;
$data = [];

if( ($new_page = Pages_Page::catchCreateForm()) ) {

	if( $new_page->save() ) {
		$ok = true;

		UI_messages::success(
			Tr::_( 'Page <strong>%key%</strong> has been created', [
				'key' => $new_page->getName()
			] )
		);

		$data = [
			'new_page_id' => $new_page->getId()
		];
	} else {
		Pages_Page::getCreateForm()->setCommonMessage( implode('', UI_messages::get()) );
	}

}

AJAX::operationResponse(
	$ok,
	[
		$form->getId() . '_form_area' => Application::getView()->render( 'page/create/form' )
	],
	$data
);