<?php

namespace JetStudio;

use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$module = Modules::getCurrentModule();
$page = Modules::getCurrentPage();


$res = false;
if( $page ) {
	$res = match (Modules::getCurrentPage_whatToEdit()) {
		'main' => $page->catchEditForm_main(),
		'content' => $page->catchEditForm_content(),
		'static_content' => $page->catchEditForm_static_content(),
		'callback' => $page->catchEditForm_callback(),
	};
}

if( $res ) {

	if( $page->save() ) {
		UI_messages::success( Tr::_( 'Saved ...' ) );
	}

	Http_Headers::reload( [], ['action'] );

} else {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
}
