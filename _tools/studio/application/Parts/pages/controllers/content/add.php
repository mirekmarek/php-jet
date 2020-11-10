<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$page = Pages::getCurrentPage();

$form = Pages_Page_Content::getCreateForm( $page );

$ok = false;
$data = [];
$snippets = [];

if(
	$page &&
	($new_content=Pages_Page_Content::catchCreateForm( $page ))
) {
	$page->addContent( $new_content );

	if( $page->save() ) {
		$ok = true;
		$form = Pages_Page_Content::getCreateForm( $page );

		$form->setCommonMessage(
			UI_messages::createSuccess(
				Tr::_('New content has been created')
			)
		);

		$data = [];

		$snippets['content_list_area_'.$page->getSiteId().'_'.$page->getId()] = Application::getView()->render('page_edit/content_list');

	}


}



$view = Application::getView();

$view->setVar( 'form', $form);

$snippets[$form->getId().'_form_area'] = $view->render('add_content/form');

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);
