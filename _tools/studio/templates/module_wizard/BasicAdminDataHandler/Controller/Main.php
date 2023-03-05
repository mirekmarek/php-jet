<?php
/**
 *
 * @copyright %<COPYRIGHT>%
 * @license  %<LICENSE>%
 * @author  %<AUTHOR>%
 */
namespace %<NAMESPACE>%;

use %<DATA_MODEL_CLASS_NAME>% as %<DATA_MODEL_CLASS_ALIAS>%;

use Jet\MVC_Controller_Router_AddEditDelete;
use Jet\MVC_Controller_Default;
use Jet\UI_messages;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\Navigation_Breadcrumb;
use Jet\Logger;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	/**
	 * @var ?MVC_Controller_Router_AddEditDelete
	 */
	protected ?MVC_Controller_Router_AddEditDelete $router = null;

	/**
	 * @var ?%<DATA_MODEL_CLASS_ALIAS>%
	 */
	protected ?%<DATA_MODEL_CLASS_ALIAS>% $%<ITEM_VAR_NAME>% = null;

	/**
	 *
	 * @return MVC_Controller_Router_AddEditDelete
	 */
	public function getControllerRouter() : MVC_Controller_Router_AddEditDelete
	{
		if( !$this->router ) {
			$this->router = new MVC_Controller_Router_AddEditDelete(
				controller: $this,
				item_catcher: function($id) : bool {
					return (bool)($this->%<ITEM_VAR_NAME>% = %<DATA_MODEL_CLASS_ALIAS>%::get($id));
				},
				actions_map: [
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
	 *
	 */
	public function listing_Action() : void
	{
		$listing = new Listing();
		$listing->handle();

		$this->view->setVar( 'filter_form', $listing->getFilterForm());
		$this->view->setVar( 'grid', $listing->getGrid() );

		$this->output( 'list' );
	}

	/**
	 *
	 */
	public function add_Action() : void
	{
		Navigation_Breadcrumb::addURL( Tr::_( '%<TXT_BTN_NEW>%' ) );

		$%<ITEM_VAR_NAME>% = new %<DATA_MODEL_CLASS_ALIAS>%();


		$form = $%<ITEM_VAR_NAME>%->getAddForm();

		if( $%<ITEM_VAR_NAME>%->catchAddForm() ) {
			$%<ITEM_VAR_NAME>%->save();

			Logger::success(
				event: '%<LOG_EVENT_CREATED>%',
				event_message: '%<LOG_EVENT_CREATED_MESSAGE>%',
				context_object_id: $%<ITEM_VAR_NAME>%->%<ITEM_ID_GETTER>%(),
				context_object_name: $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%(),
				context_object_data: $%<ITEM_VAR_NAME>%
			);


			UI_messages::success(
				Tr::_( '%<TXT_MSG_CREATED>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] )
			);

			Http_Headers::reload( ['id'=>$%<ITEM_VAR_NAME>%->%<ITEM_ID_GETTER>%()], ['action'] );
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( '%<ITEM_VAR_NAME>%', $%<ITEM_VAR_NAME>% );

		$this->output( 'edit' );

	}

	/**
	 *
	 */
	public function edit_Action() : void
	{
		$%<ITEM_VAR_NAME>% = $this->%<ITEM_VAR_NAME>%;

		Navigation_Breadcrumb::addURL( Tr::_( '%<TXT_BN_EDIT>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] ) );

		$form = $%<ITEM_VAR_NAME>%->getEditForm();

		if( $%<ITEM_VAR_NAME>%->catchEditForm() ) {

			$%<ITEM_VAR_NAME>%->save();

			Logger::success(
				event: '%<LOG_EVENT_UPDATED>%',
				event_message: '%<LOG_EVENT_UPDATED_MESSAGE>%',
				context_object_id: $%<ITEM_VAR_NAME>%->%<ITEM_ID_GETTER>%(),
				context_object_name: $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%(),
				context_object_data: $%<ITEM_VAR_NAME>%
			);

			UI_messages::success(
				Tr::_( '%<TXT_MSG_UPDATED>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] )
			);

			Http_Headers::reload();
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( '%<ITEM_VAR_NAME>%', $%<ITEM_VAR_NAME>% );

		$this->output( 'edit' );

	}

	/**
	 *
	 */
	public function view_Action() : void
	{
		$%<ITEM_VAR_NAME>% = $this->%<ITEM_VAR_NAME>%;

		Navigation_Breadcrumb::addURL(
			Tr::_( '%<TXT_BN_DETAIL>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] )
		);

		$form = $%<ITEM_VAR_NAME>%->getEditForm();

		$form->setIsReadonly();

		$this->view->setVar( 'form', $form );
		$this->view->setVar( '%<ITEM_VAR_NAME>%', $%<ITEM_VAR_NAME>% );

		$this->output( 'edit' );

	}

	/**
	 *
	 */
	public function delete_Action() : void
	{
		$%<ITEM_VAR_NAME>% = $this->%<ITEM_VAR_NAME>%;

		Navigation_Breadcrumb::addURL(
			Tr::_( '%<TXT_BN_DELETE>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] )
		);

		if( Http_Request::POST()->getString( 'delete' )=='yes' ) {
			$%<ITEM_VAR_NAME>%->delete();

			Logger::success(
				event: '%<LOG_EVENT_DELETED>%',
				event_message: '%<LOG_EVENT_DELETED_MESSAGE>%',
				context_object_id: $%<ITEM_VAR_NAME>%->%<ITEM_ID_GETTER>%(),
				context_object_name: $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%(),
				context_object_data: $%<ITEM_VAR_NAME>%
			);

			UI_messages::info(
				Tr::_( '%<TXT_MSG_DELETED>%', [ 'ITEM_NAME' => $%<ITEM_VAR_NAME>%->%<ITEM_NAME_GETTER>%() ] )
			);

			Http_Headers::reload([], ['action', 'id']);
		}


		$this->view->setVar( '%<ITEM_VAR_NAME>%', $%<ITEM_VAR_NAME>% );

		$this->output( 'delete-confirm' );
	}

}