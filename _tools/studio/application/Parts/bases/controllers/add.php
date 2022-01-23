<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$form = Bases::getCreateForm();
$ok = false;
$data = [];

if( ($new_base = Bases::catchCreateForm()) ) {

	if( $new_base->create() ) {
		$ok = true;

		UI_messages::success(
			Tr::_( 'Base <strong>%key%</strong> has been created', [
				'key' => $new_base->getName()
			] )
		);

		$data = [
			'new_base_id' => $new_base->getId()
		];
	}

}

AJAX::operationResponse(
	$ok,
	[
		$form->getId() . '_form_area' => Application::getView()->render( 'create_base/form' )
	],
	$data
);