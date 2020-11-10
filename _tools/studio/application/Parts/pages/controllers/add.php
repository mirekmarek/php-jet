<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$form = Pages_Page::getCreateForm();
$ok = false;
$data = [];

if( ($new_page=Pages_Page::catchCreateForm( Pages::getCurrentSiteId(), Pages::getCurrentLocale(), Pages::getCurrentPage() ) ) ) {

	Pages::addPage( $new_page );

	if( $new_page->save() ) {
		$ok = true;

		UI_messages::success(
			Tr::_('Page <strong>%key%</strong> has been created',[
				'key' => $new_page->getName()
			])
		);

		$data = [
			'new_page_id' => $new_page->getId()
		];
	}

}

AJAX::formResponse(
	$ok,
	[
		$form->getId().'_form_area' => Application::getView()->render('create_page/form')
	],
	$data
);