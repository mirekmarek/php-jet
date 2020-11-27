<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$module = Modules::getCurrentModule();

if(!$module) {
	return;
}

$GET = Http_Request::GET();

$page = $module->getPage( $GET->getString('site'), $GET->getString('page') );
if(!$page) {
	return;
}

$form = Modules_Manifest::getPageContentCreateForm( $page );

$ok = false;
$data = [];
$snippets = [];
$view = Application::getView();

if(
	$page &&
	($new_content=Pages_Page_Content::catchCreateForm( $page ))
) {
	$page->addContent( $new_content );

	if( Modules::save( $form ) ) {
		$ok = true;

		$form = Modules_Manifest::getPageContentCreateForm( $page );

		$form->setCommonMessage(
			UI_messages::createSuccess(
				Tr::_('New content has been created')
			)
		);

		$data = [];

		$field_prefix = '/pages/'.$page->getSiteId().'/'.$page->getId().'/';

		$view->setVar( 'form_field_prefix', $field_prefix);
		$view->setVar( 'form', $module->getEditForm() );
		$view->setVar( 'site', $page->getSite() );
		$view->setVar( 'page', $page );
		$view->setVar('delete_content_action_creator', function( $i ) use ($page) {
			return "Modules.editModule.editPage.removeContent('{$page->getSiteId()}', '{$page->getId()}', $i);";
		});


		$snippets['content_list_area_'.$page->getSiteId().'_'.$page->getId()] = $view->render('pages/page_edit/content_list');
	}
}




$view->setVar( 'page', $page);
$view->setVar( 'form', $form);

$snippets[$form->getId().'_form_area'] = $view->render('pages/add_content/form');

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);


