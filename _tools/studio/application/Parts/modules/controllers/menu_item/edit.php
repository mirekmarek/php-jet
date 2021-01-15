<?php

namespace JetStudio;

use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$module = Modules::getCurrentModule();
$menu_item = Modules::getCurrentMenuItem();

if(
	$module &&
	$menu_item &&
	$menu_item->catchEditForm()
) {

	if( $module->save() ) {
		UI_messages::success( Tr::_( 'Saved ...' ) );
		Http_Headers::reload( [], ['action'] );
	}

} else {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
}
