<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$form = Sites::getCreateForm();
$ok = false;
$data = [];

if( ($new_site=Sites::catchCreateForm()) ) {

	if( Sites::save() ) {
		$ok = true;

		UI_messages::success(
			Tr::_('Site <strong>%key%</strong> has been created',[
				'key' => $new_site->getName()
			])
		);

		$data = [
			'new_site_id' => $new_site->getId()
		];
	}

}

AJAX::formResponse(
	$ok,
	[
		$form->getId().'_form_area' => Application::getView()->render('create_site/form')
	],
	$data
);