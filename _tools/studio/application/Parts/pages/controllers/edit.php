<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$current = Pages::getCurrentPage();

$what = Pages::whatToEdit();

$res = false;
if($current) {
	switch($what) {
		case 'main': $res = $current->catchEditForm_main(); break;
		case 'content': $res = $current->catchEditForm_content(); break;
		case 'static_content': $res = $current->catchEditForm_static_content(); break;
		case 'callback': $res = $current->catchEditForm_callback(); break;
	}
}

if($res) {
	if( $current->save() ) {
		UI_messages::success(Tr::_('Saved ...'));
	}

	Http_Headers::reload([], ['action']);

} else {
	UI_messages::danger(
		Tr::_('There are some problems ... Please check the form.')
	);
}
