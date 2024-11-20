<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\Pages;

use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Form_Field_Int;
use Jet\Form_Field_Select;
use Jet\Form_Field_Textarea;
use Jet\MVC_Page_Content;
use JetStudio\Form_Field_AssocArray;
use JetStudio\JetStudio;
use JetStudio\Form_Field_Callable;

/**
 *
 */
class Page_Content extends MVC_Page_Content
{
	public const CONTENT_KIND_MODULE = 'module';
	public const CONTENT_KIND_CLASS = 'class';
	public const CONTENT_KIND_STATIC = 'static';
	public const CONTENT_KIND_CALLBACK = 'callback';
	public const PARAMS_COUNT = 3;
	
	protected ?Form $__edit_form = null;


	public function getContentKind(): string
	{

		if( $this->getOutput() ) {
			if( is_array( $this->getOutput() ) ) {
				return static::CONTENT_KIND_CALLBACK;
			} else {
				return static::CONTENT_KIND_STATIC;
			}
		} else {
			if( $this->getControllerClass() ) {
				return static::CONTENT_KIND_CLASS;
			} else {
				return static::CONTENT_KIND_MODULE;
			}
		}

	}

	public static function getField__is_cacheable( bool $default_value ): Form_Field_Checkbox
	{
		$chb = new Form_Field_Checkbox( 'is_cacheable', 'Is cacheable' );
		
		$chb->setDefaultValue( $default_value );
		
		return $chb;
	}

	
	public static function getField__output_position( string $default_value, Page $page ): Form_Field_Select
	{
		$base = $page->getBase();

		$output_position = new Form_Field_Select( 'output_position', 'Output position:' );
		$output_position->setDefaultValue( $default_value );
		$output_position->setIsRequired( true );
		$output_position->setSelectOptions( $base->getLayoutOutputPositions( $page->getLayoutScriptName() ) );
		$output_position->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select output position',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select output position'
		] );

		return $output_position;
	}


	public static function getField__output_position_order( int $default_value ): Form_Field_Int
	{
		$pos_order = new Form_Field_Int( 'output_position_order', 'Output position order:' );
		$pos_order->setDefaultValue( $default_value );
		
		return  $pos_order;
	}

	public static function getField__module_name( string $default_value ): Form_Field_Hidden
	{
		$module_name = new Form_Field_Hidden( 'module_name', 'Module name:' );
		$module_name->setDefaultValue( $default_value );
		$module_name->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select module name',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select module name'
		] );

		return $module_name;
	}
	
	public static function getField__controller_name( string $default_value, string $module_name = '' ): Form_Field_Select
	{
		$controller_name = new Form_Field_Select( 'controller_name', 'Controller name:' );
		$controller_name->setDefaultValue( $default_value );
		$controller_name->setIsRequired( true );
		$controller_name->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select controller',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid controller name format'
		] );

		$select_options = Page::getModuleControllers( $module_name );

		$controller_name->setSelectOptions( $select_options );


		return $controller_name;
	}


	public static function getField__controller_action( string $default_value = '',
	                                                    string $module_name = '',
	                                                    string $controller = '' ): Form_Field_Select
	{
		$controller_action = new Form_Field_Select( 'controller_action', 'Controller action:' );
		$controller_action->setDefaultValue( $default_value );
		$controller_action->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select controller action',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select controller action'
		] );

		$select_options = Page::getModuleControllerActions( $module_name, $controller );

		$controller_action->setSelectOptions( $select_options );


		return $controller_action;

	}
	

	public static function getField__controller_class( string $default_value ): Form_Field_Input
	{
		$controller_class = new Form_Field_Input( 'controller_class', 'Custom controller class:' );
		$controller_class->setDefaultValue( $default_value );
		$controller_class->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => 'Please enter controller class',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid class name format'
		] );
		$controller_class->setValidator( function( Form_Field $filed ) {
			return JetStudio::validateClassName( $filed );
		} );

		return $controller_class;
	}
	
	public static function getField__controller_class_action( string $default_value ): Form_Field_Input
	{
		$controller_class_action = new Form_Field_Input( 'controller_class_action', 'Controller action:' );
		$controller_class_action->setDefaultValue( $default_value );
		$controller_class_action->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => 'Please enter controller class action name',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid action name format'
		] );
		$controller_class_action->setValidator( function( Form_Field $filed ) {
			return JetStudio::validateMethodName( $filed );
		} );

		return $controller_class_action;
	}
	
	public static function getField__output( string $default_value ): Form_Field_Textarea
	{
		$output = new Form_Field_Textarea( 'output', 'Static output:' );
		$output->setDefaultValue( $default_value );
		$output->setValidator( function( Form_Field_Textarea $field ) {
			$value = $field->getValueRaw();

			$field->setValue( $value );


			return true;
		} );
		$output->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter static output'
		] );

		return $output;
	}
	
	public static function getField__output_callback( string|array $default_value ): Form_Field_Callable
	{
		$output_callback = new Form_Field_Callable( 'output_callback', 'Output callback:' );
		$output_callback->setMethodArguments( 'Jet\MVC_Page $page, Jet\MVC_Page_Content $content ' );
		$output_callback->setMethodReturnType( 'string' );
		$output_callback->setDefaultValue( $default_value );
		$output_callback->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter callback',
			Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Callback is not callable'
		] );

		return $output_callback;
	}
	
	public function getEditForm( Page $page ): Form
	{
		if( !$this->__edit_form ) {

			$fields = [];

			$is_cacheable = Page_Content::getField__is_cacheable( $this->isCacheable() );
			$is_cacheable->setFieldValueCatcher( function( $value ) {
				$this->setIsCacheable( $value );
			} );
			$fields[] = $is_cacheable;

			$output_position = static::getField__output_position( $this->getOutputPosition(), $page );
			$output_position->setFieldValueCatcher( function( $value ) {
				$this->setOutputPosition( $value );
			} );
			$fields[] = $output_position;

			$output_position_order = static::getField__output_position_order( $this->getOutputPositionOrder() );
			$output_position_order->setFieldValueCatcher( function( $value ) {
				$this->setOutputPositionOrder( $value );
			} );
			$fields[] = $output_position_order;
			
			
			
			$params_field = new Form_Field_AssocArray('params', 'Parameters:');
			$params_field->setAssocChar('=');
			$params_field->setNewRowsCount(static::PARAMS_COUNT);
			$params_field->setDefaultValue( $this->parameters );
			$params_field->setFieldValueCatcher(function($value) {
				$this->setParameters( $value );
			});
			$fields[] = $params_field;
			

			switch( $this->getContentKind() ) {
				case static::CONTENT_KIND_MODULE:
					$module_name = static::getField__module_name( $this->getModuleName() );
					$module_name->setIsRequired( true );
					$module_name->setFieldValueCatcher( function( $value ) {
						$this->setModuleName( $value );
					} );
					$fields[] = $module_name;

					$controller_name = static::getField__controller_name( $this->getControllerName(), $this->getModuleName() );
					$controller_name->setIsRequired( true );
					$controller_name->setFieldValueCatcher( function( $value ) {
						$this->setControllerName( $value );
					} );
					$fields[] = $controller_name;

					$controller_action = static::getField__controller_action( $this->getControllerAction(), $this->getModuleName(), $this->getControllerName() );
					$controller_action->setIsRequired( true );
					$controller_action->setFieldValueCatcher( function( $value ) {
						$this->setControllerAction( $value );
					} );
					$fields[] = $controller_action;

					break;
				case static::CONTENT_KIND_CLASS:
					$controller_class = static::getField__controller_class( $this->getControllerClass() );
					$controller_class->setIsRequired( true );
					$controller_class->setFieldValueCatcher( function( $value ) {
						$this->setControllerClass( $value );
					} );
					$fields[] = $controller_class;


					$controller_class_action = static::getField__controller_class_action( $this->getControllerAction() );
					$controller_class_action->setIsRequired( true );
					$controller_class_action->setFieldValueCatcher( function( $value ) {
						$this->setControllerAction( $value );
					} );
					$fields[] = $controller_class_action;

					break;
				case static::CONTENT_KIND_STATIC:
					$output = static::getField__output( $this->getOutput() );
					$output->setFieldValueCatcher( function( $value ) {
						$this->setOutput( $value );
					} );
					$fields[] = $output;

					break;
				case static::CONTENT_KIND_CALLBACK:
					$output_callback = static::getField__output_callback( $this->getOutput() );
					$output_callback->setIsRequired( true );
					$output_callback->setFieldValueCatcher( function($value) {
						$this->setOutput( $value );
					} );
					
					$fields[] = $output_callback;

					break;

			}


			$form = new Form( 'page_content_edit_form', $fields );

			$form->setAction( Main::getActionUrl( 'edit_content' ) );

			$this->__edit_form = $form;
		}

		return $this->__edit_form;
	}
	

	public static function fromArray( array $data ): Page_Content
	{
		$content = new Page_Content();

		foreach( $data as $k => $v ) {
			$content->{$k} = $v;
		}

		return $content;
	}

}