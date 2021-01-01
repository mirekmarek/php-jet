<?php
/**
 *
 * @copyright %<COPYRIGHT>%
 * @license  %<LICENSE>%
 * @author  %<AUTHOR>%
 */
namespace %<NAMESPACE>%;

use %<DATA_MODEL_CLASS_NAME>% as %<DATA_MODEL_CLASS_ALIAS>%;

use Jet\Mvc_Controller_Router_AddEditDelete;
use Jet\UI_messages;
use Jet\Mvc_Controller_Default;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\Navigation_Breadcrumb;

use JetApplicationModule\UI\Admin\Main as UI_module;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{

	/**
	 * @var ?Mvc_Controller_Router_AddEditDelete
	 */
	protected ?Mvc_Controller_Router_AddEditDelete $router = null;

	/**
	 * @var ?%<DATA_MODEL_CLASS_ALIAS>%
	 */
	protected ?%<DATA_MODEL_CLASS_ALIAS>% $%<ITEM_VAR_NAME>% = null;

	/**
	 *
	 * @return Mvc_Controller_Router_AddEditDelete
	 */
	public function getControllerRouter() : Mvc_Controller_Router_AddEditDelete
	{
		if( !$this->router ) {
			$this->router = new Mvc_Controller_Router_AddEditDelete(
				$this,
				function($id) {
					return (bool)($this->%<ITEM_VAR_NAME>% = %<DATA_MODEL_CLASS_ALIAS>%::get($id));
				},
				[
					'listing'=> Main::ACTION_GET_%<ACL_ENTITY_CONST_NAME>%,
					'view'   => Main::ACTION_GET_%<ACL_ENTITY_CONST_NAME>%,
					'add'    => Main::ACTION_ADD_%<ACL_ENTITY_CONST_NAME>%,
					'edit'   => Main::ACTION_UPDATE_%<ACL_ENTITY_CONST_NAME>%,
					'delete' => Main::ACTION_DELETE_%<ACL_ENTITY_CONST_NAME>%,
				]
			);
		}

		return $this->router;
	}

	/**
	 * @param string $current_label
	 */
	protected function _setBreadcrumbNavigation( string $current_label = '' ) : void
	{
		UI_module::initBreadcrumb();

		if( $current_label ) {
			Navigation_Breadcrumb::addURL( $current_label );
		}
	}

	/**
	 *
	 */
	public function listing_Action() : void
	{
		$this->_setBreadcrumbNavigation();

		$listing = new Listing();
		$listing->handle();

		$this->view->setVar( 'filter_form', $listing->filter_getForm());
		$this->view->setVar( 'grid', $listing->getGrid() );

		$this->render( 'list' );
	}

	/**
	 *
	 */
	public function add_Action() : void
	{
		$this->_setBreadcrumbNavigation( Tr::_( '%<TXT_BTN_NEW>%' ) );

		$%<ITEM_VAR_NAME>% = new %<DATA_MODEL_CLASS_ALIAS>%();


		$form = $%<ITEM_VAR_NAME>%->getAddForm();

		if( $%<ITEM_VAR_NAME>%->catchAddForm() ) {
			$%<ITEM_VAR_NAME>%->save();

			$this->logAllowedAction( '%<LOG_EVENT_CREATED>%', $%<ITEM_VAR_NAME>%->%<ITEM_ID_GETTER>%(), $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%(), $%<ITEM_VAR_NAME>% );

			UI_messages::success(
				Tr::_( '%<TXT_MSG_CREATED>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] )
			);

			Http_Headers::reload( ['id'=>$%<ITEM_VAR_NAME>%->%<ITEM_ID_GETTER>%()], ['action'] );
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( '%<ITEM_VAR_NAME>%', $%<ITEM_VAR_NAME>% );

		$this->render( 'edit' );

	}

	/**
	 *
	 */
	public function edit_Action() : void
	{
		$%<ITEM_VAR_NAME>% = $this->%<ITEM_VAR_NAME>%;

		$this->_setBreadcrumbNavigation( Tr::_( '%<TXT_BN_EDIT>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] ) );

		$form = $%<ITEM_VAR_NAME>%->getEditForm();

		if( $%<ITEM_VAR_NAME>%->catchEditForm() ) {

			$%<ITEM_VAR_NAME>%->save();
			$this->logAllowedAction( '%<LOG_EVENT_UPDATED>%', $%<ITEM_VAR_NAME>%->%<ITEM_ID_GETTER>%(), $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%(), $%<ITEM_VAR_NAME>% );

			UI_messages::success(
				Tr::_( '%<TXT_MSG_UPDATED>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] )
			);

			Http_Headers::reload();
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( '%<ITEM_VAR_NAME>%', $%<ITEM_VAR_NAME>% );

		$this->render( 'edit' );

	}

	/**
	 *
	 */
	public function view_Action() : void
	{
		$%<ITEM_VAR_NAME>% = $this->%<ITEM_VAR_NAME>%;

		$this->_setBreadcrumbNavigation(
			Tr::_( '%<TXT_BN_DETAIL>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] )
		);

		$form = $%<ITEM_VAR_NAME>%->getEditForm();

		$form->setIsReadonly();

		$this->view->setVar( 'form', $form );
		$this->view->setVar( '%<ITEM_VAR_NAME>%', $%<ITEM_VAR_NAME>% );

		$this->render( 'edit' );

	}

	/**
	 *
	 */
	public function delete_Action() : void
	{
		$%<ITEM_VAR_NAME>% = $this->%<ITEM_VAR_NAME>%;

		$this->_setBreadcrumbNavigation(
			Tr::_( '%<TXT_BN_DELETE>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] )
		);

		if( Http_Request::POST()->getString( 'delete' )=='yes' ) {
			$%<ITEM_VAR_NAME>%->delete();
			$this->logAllowedAction( '%<LOG_EVENT_DELETED>%', $%<ITEM_VAR_NAME>%->%<ITEM_ID_GETTER>%(), $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%(), $%<ITEM_VAR_NAME>% );

			UI_messages::info(
				Tr::_( '%<TXT_MSG_DELETED>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] )
			);

			Http_Headers::reload([], ['action', 'id']);
		}


		$this->view->setVar( '%<ITEM_VAR_NAME>%', $%<ITEM_VAR_NAME>% );

		$this->render( 'delete-confirm' );
	}

}