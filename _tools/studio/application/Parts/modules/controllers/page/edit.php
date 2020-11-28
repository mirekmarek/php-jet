<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$module = Modules::getCurrentModule();
$page = Modules::getCurrentPage();


$res = false;
if($page) {
	switch(Modules::getCurrentPage_whatToEdit()) {
		case 'main': $res = $page->catchEditForm_main(); break;
		case 'content': $res = $page->catchEditForm_content(); break;
		case 'static_content': $res = $page->catchEditForm_static_content(); break;
		case 'callback': $res = $page->catchEditForm_callback(); break;
	}
}

if($res) {
	if( $module->save() ) {
		UI_messages::success(Tr::_('Saved ...'));
	}

	Http_Headers::reload([], ['action']);

} else {
	UI_messages::danger(
		Tr::_('There are some problems ... Please check the form.')
	);
}
