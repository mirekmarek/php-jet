<?php

namespace JetStudio;

use Exception;
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

	$ok = true;
	try {
		$module->getMenuItems()->save();
	} catch( Exception $e) {
		$ok = false;
		UI_messages::danger( $e->getMessage() );

	}

	if( $ok ) {
		UI_messages::success( Tr::_( 'Saved ...' ) );
		Http_Headers::reload( [], ['action'] );
	}

} else {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
}
