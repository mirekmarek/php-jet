<?php

namespace JetStudio;

use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$current = Pages::getCurrentPage();

$what = Pages::whatToEdit();

$res = false;
if( $current ) {
	$res = match ($what) {
		'main' => $current->catchEditForm_main(),
		'content' => $current->catchEditForm_content(),
		'static_content' => $current->catchEditForm_static_content(),
		'callback' => $current->catchEditForm_callback(),
	};
}

if( $res ) {
	if( $current->save() ) {
		UI_messages::success( Tr::_( 'Saved ...' ) );
	}

	Http_Headers::reload( [], ['action'] );

} else {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
}
