<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\DataModel;

use Exception;
use Jet\DataModel;
use Jet\DataModel_Backend;
use Jet\Debug;
use Jet\Form;
use Jet\AJAX;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;

use JetStudio\JetStudio;
use JetStudio\JetStudio_Module_Controller;

class Controller extends JetStudio_Module_Controller
{
	protected function resolve(): string
	{
		$action = Http_Request::GET()->getString( 'action' );
		return $action ? : 'default';
	}
	
	public function default_Action(): void
	{
		$this->output( 'main' );
	}
	
	public function key_add_Action(): void
	{
		$current = DataModels::getCurrentModel();
		
		$ok = false;
		$data = [];
		$snippets = [];
		
		if( ($new_key = DataModel_Definition_Key::catchCreateForm()) ) {
			
			$form = DataModel_Definition_Key::getCreateForm();
			
			if( $current->save() ) {
				$ok = true;
				
				UI_messages::success(
					Tr::_( 'Key <strong>%key%</strong> has been created', [
						'key' => $new_key->getName()
					] )
				);
			} else {
				$message = implode( '', UI_messages::get() );
				
				$form->setCommonMessage( $message );
			}
		}
		
		$snippets['key_add_form_area'] = $this->view->render( 'key/create/form' );
		
		AJAX::operationResponse(
			$ok,
			$snippets,
			$data
		);
	}
	
	public function key_delete_Action(): void
	{
		$current = DataModels::getCurrentModel();
		
		/**
		 * @var DataModel_Definition_Key $key
		 */
		if(
			$current &&
			($key = $current->getCustomKey( Http_Request::GET()->getString( 'key' ) ))
		) {
			$current->deleteCustomKey( $key->getName() );
			
			if( $current->save() ) {
				UI_messages::info(
					Tr::_( 'Key <strong>%key%</strong> has been deleted', ['key' => $key->getName()] )
				);
				
			}
		}
		
		Http_Headers::reload( [], [
			'action',
			'key'
		] );
		
	}
	
	public function key_edit_Action(): void
	{
		$current = DataModels::getCurrentModel();
		
		$key = $current->getCustomKey( Http_Request::GET()->getString( 'key' ) );
		
		/**
		 * @var DataModel_Definition_Key $key
		 */
		if( !$key ) {
			die();
		}
		
		if( $key->catchEditForm() ) {
			if( $current->save() ) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
			}
			Http_Headers::reload( [], ['action'] );
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
		}
	}
	
	public function property_add_Action(): void
	{
		$current = DataModels::getCurrentClass();
		
		$ok = false;
		$data = [];
		$snippets = ['add_property_form_message' => ''];
		
		
		if( ($new_properties = DataModel_Definition_Property::catchCreateForm( $current )) ) {
			
			$ok = true;
			foreach( $new_properties as $new_property ) {
				
				if( $new_property->add( $current ) ) {
					UI_messages::success(
						Tr::_( 'Property <strong>%property%</strong> has been created', [
							'property' => $new_property->getName()
						] )
					);
				} else {
					
					die( 'Error ???' );
					$ok = false;
					
					$snippets['add_property_form_message'] .= implode( '', UI_messages::get() );
				}
			}
		}
		
		AJAX::operationResponse(
			$ok,
			$snippets,
			$data
		);
	}
	
	public function property_edit_Action(): void
	{
		$class = DataModels::getCurrentClass();
		$property = DataModels::getCurrentProperty();
		
		if( !$property ) {
			die();
		}
		
		
		if( $property->catchEditForm() ) {
			
			if( $property->update( $class ) ) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
			}
			
			Http_Headers::reload( [], ['action'] );
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
		}
	}
	
	public function relation_add_form_Action() : void
	{
		$related = DataModels::getClass( Http_Request::GET()->getString( 'related_model' ) )->getDefinition();
		$form = DataModel_Definition_Relation_External::getCreateForm( $related );
		
		$this->view->setVar( 'related', $related );
		$this->view->setVar( 'form', $form );
		
		AJAX::snippetResponse( $this->view->render( 'relation/create/form' ) );
	}
	
	public function relation_add_Action(): void
	{
		$current = DataModels::getCurrentModel();
		$related = DataModels::getClass( Http_Request::POST()->getString( 'related_model_class_name' ) )->getDefinition();
		$form = DataModel_Definition_Relation_External::getCreateForm( $related );
		
		$ok = false;
		$data = [];
		$snippets = [];
		
		if( ($new_relation = DataModel_Definition_Relation_External::catchCreateForm( $related, $form )) ) {
			$current->addExternalRelation( $new_relation );
			
			if( $current->save() ) {
				UI_messages::success(
					Tr::_( 'Relation to <strong>%relation%</strong> has been created', [
						'relation' => $related->getModelName()
					] )
				);
				$ok = true;
			} else {
				$message = implode( '', UI_messages::get() );
				
				$form->setCommonMessage( $message );
			}
		}
		
		
		$this->view->setVar( 'related', $related );
		$this->view->setVar( 'form', $form );
		
		
		$snippets['create_relation_form_area'] = $this->view->render( 'relation/create/form' );
		
		AJAX::operationResponse(
			$ok,
			$snippets,
			$data
		);
	}
	
	public function add_form_Action(): void
	{
		$related = DataModels::getClass( Http_Request::GET()->getString( 'related_model' ) )->getDefinition();
		$form = DataModel_Definition_Relation_External::getCreateForm( $related );
		
		$this->view->setVar( 'related', $related );
		$this->view->setVar( 'form', $form );
		
		AJAX::snippetResponse( $this->view->render( 'relation/create/form' ) );
	}
	
	public function relation_delete_Action(): void
	{
		$current = DataModels::getCurrentModel();
		
		$relation_name = Http_Request::GET()->getString( 'relation' );
		
		/**
		 * @var DataModel_Definition_Relation_External $relation
		 */
		if(
			$current &&
			($relation = $current->getExternalRelation( $relation_name ))
		) {
			$current->deleteExternalRelation( $relation_name );
			
			if( $current->save() ) {
				UI_messages::info(
					Tr::_( 'Relation <strong>%relation%</strong> has been deleted', ['relation' => $relation->getName()] )
				);
				
			}
		}
		
		Http_Headers::reload( [], [
			'action',
			'relation'
		] );
	}
	
	public function relation_edit_Action(): void
	{
		$current = DataModels::getCurrentModel();
		
		$relation = $current->getExternalRelation( Http_Request::GET()->getString( 'relation' ) );
		
		/**
		 * @var DataModel_Definition_Relation_External $relation
		 */
		if( !$relation ) {
			die();
		}
		
		if( $relation->catchEditForm() ) {
			if( $current->save() ) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
			}
			Http_Headers::reload( [], ['action'] );
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
		}
	}
	
	public function model_add_Action(): void
	{
		$POST = Http_Request::POST();
		
		$type = $POST->getString( key: 'type', valid_values: [
			DataModel::MODEL_TYPE_MAIN,
			DataModel::MODEL_TYPE_RELATED_1TO1,
			DataModel::MODEL_TYPE_RELATED_1TON,
		] );
		if( !$type ) {
			die();
		}
		
		$class_name = __NAMESPACE__ . '\\DataModel_Definition_Model_' . $type;
		
		
		/**
		 * @var DataModel_Definition_Model_Interface $class_name
		 * @var Form $form
		 */
		$form = $class_name::getCreateForm();
		
		
		$ok = false;
		$data = [];
		
		if( ($new_model = $class_name::catchCreateForm()) ) {
			UI_messages::success(
				Tr::_( 'Class <strong>%class%</strong> has been created', [
					'class' => $new_model->getClassName()
				] )
			);
			
			$ok = true;
			$data['new_class_name'] = $new_model->getClassName();
			
		} else {
			$message = implode( '', UI_messages::get() );
			
			$form->setCommonMessage( $message );
		}
		
		
		AJAX::operationResponse(
			$ok,
			[
				'create_model_form_area_' . $type => $this->view->render( 'model/create/' . $type . '/form' )
			],
			$data
		);
	}
	
	public function model_edit_Action(): void
	{
		$current = DataModels::getCurrentModel();
		
		if(
			$current &&
			$current->catchEditForm()
		) {
			
			if( $current->save() ) {
				UI_messages::success( Tr::_( 'Saved ...' ) );
				
			}
			
			Http_Headers::reload( [], ['action'] );
			
		} else {
			UI_messages::danger(
				Tr::_( 'There are some problems ... Please check the form.' )
			);
		}
	}
	
	public function model_generate_class_source_Action(): void
	{
		$current = DataModels::getCurrentModel();
		if( !$current ) {
			die();
		}
		
		header( 'Content-Type: text/plain' );
		
		$class = $current->createClass( true ) ?? '';
		
		AJAX::snippetResponse( $class );
	}
	
	
	public function model_generate_database_table_Action(): void
	{
		$current = DataModels::getCurrentModel();
		if( !$current ) {
			die();
		}
		
		$current->prepare();
		
		$backend = DataModel_Backend::get( $current );
		
		$updated = false;
		$ok = true;
		try {
			if( $backend->helper_tableExists( $current ) ) {
				//echo implode(PHP_EOL.PHP_EOL, $backend->helper_getUpdateCommand( $current ));
				$backend->helper_update( $current );
				$updated = true;
			} else {
				//echo $backend->helper_getCreateCommand( $current );
				$backend->helper_create( $current );
			}
		} catch( Exception $e ) {
			$ok = false;
			
			JetStudio::handleError( $e );
		}
		
		if( $ok ) {
			if( $updated ) {
				UI_messages::success( Tr::_( 'Database table <b>%table%</b> has been updated', ['table' => $current->getDatabaseTableName()] ) );
			} else {
				UI_messages::success( Tr::_( 'Database table <b>%table%</b> has been created', ['table' => $current->getDatabaseTableName()] ) );
			}
		}
		
		Http_Headers::reload( [], ['action'] );
	}
	
	public function model_generate_script_path_Action(): void
	{
		$GET = Http_Request::GET();
		
		$namespace = $GET->getString( 'namespace' );
		$class_name = $GET->getString( 'class_name' );
		
		$path = DataModels::generateScriptPath( $namespace, $class_name );
		
		AJAX::commonResponse( ['path' => $path] );
	}
	
	public function model_generate_SQL_create_Action(): void
	{
		Debug::setOutputIsHTML( false );
		
		$current = DataModels::getCurrentModel();
		if( !$current ) {
			die();
		}
		
		$current->prepare();
		
		$backend = DataModel_Backend::get( $current );
		
		AJAX::snippetResponse( $backend->helper_getCreateCommand( $current ) );
	}
	
	public function model_generate_SQL_update_Action(): void
	{
		Debug::setOutputIsHTML( false );
		
		$current = DataModels::getCurrentModel();
		if( !$current ) {
			die();
		}
		
		$current->prepare();
		
		$backend = DataModel_Backend::get( $current );
		
		$res = '';
		if( $backend->helper_tableExists( $current ) ) {
			$res = implode( PHP_EOL . PHP_EOL, $backend->helper_getUpdateCommand( $current ) );
		}
		
		AJAX::snippetResponse( $res );
	}
}