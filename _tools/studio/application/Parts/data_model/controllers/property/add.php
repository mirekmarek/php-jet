<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;


if( ($new_property=DataModel_Definition_Property::catchCreateForm()) ) {

	$form = DataModel_Definition_Property::getCreateForm();
	$form->getField('type')->setDefaultValue( $new_property->getType() );

	if(DataModels::save($form)) {
		$ok = true;

		$form->setCommonMessage(
			UI_messages::createSuccess(
				Tr::_('Property <strong>%property%</strong> has been created',[
					'property' => $new_property->getName()
				])
			)
		);

	}


}

Http_Headers::reload([], ['action']);
