<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Int;
use Jet\Form_Field_Select;
use Jet\Form_Field_Textarea;
use Jet\Mvc_Page_Content;

/**
 *
 */
class Pages_Page_Content extends Mvc_Page_Content
{
	const CONTENT_KIND_MODULE = 'module';
	const CONTENT_KIND_CLASS = 'class';
	const CONTENT_KIND_STATIC = 'static';
	const CONTENT_KIND_CALLBACK = 'callback';
	const PARAMS_COUNT = 3;	

	/**
	 * @var Form
	 */
	protected $__edit_form;

	/**
	 * @return string
	 */
	public function getContentKind()
	{

		if( $this->getOutput() ) {
			if( is_array($this->getOutput()) ) {
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

	/**
	 * @param string $default_value
	 * @param Pages_Page $page
	 *
	 * @return Form_Field_Select
	 *
	 */
	public static function getField__output_position( $default_value, Pages_Page $page )
	{
		/**
		 * @var Sites_Site $site
		 */
		$site = $page->getSite();

		$output_position = new Form_Field_Select('output_position', 'Output position:', $default_value );
		$output_position->setIsRequired( true );
		$output_position->setSelectOptions( $site->getLayoutOutputPositions( $page->getLayoutScriptName() ) );
		$output_position->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select output position',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select output position'
		]);

		return $output_position;
	}


	/**
	 * @param int $default_value
	 *
	 * @return Form_Field_Int
	 */
	public static function getField__output_position_order( $default_value )
	{
		$output_position_order = new Form_Field_Int('output_position_order', 'Output position order:', $default_value);

		return $output_position_order;
	}

	/**
	 * @param string $default_value
	 *
	 * @return Form_Field_Select
	 */
	public static function getField__module_name( $default_value )
	{
		$modules = [
			'' => ''
		];
		foreach( Modules::getModules() as $module ) {
			$modules[ $module->getName() ] = $module->getName().' ('.$module->getLabel().')';
		}

		asort( $modules );

		$module_name = new Form_Field_Select('module_name', 'Module name:', $default_value);
		$module_name->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select module name',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select module name'
		]);
		$module_name->setSelectOptions( $modules );

		return $module_name;
	}



	/**
	 * @param string $default_value
	 * @param string $module_name
	 *
	 * @return Form_Field_Select
	 */
	public static function getField__controller_name( $default_value, $module_name='' )
	{
		$controller_name = new Form_Field_Select('controller_name', 'Controller name:', $default_value, true);
		$controller_name->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select controller',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Invalid controller name format'
		]);

		$select_options = [];

		if($module_name && Modules::exists($module_name)) {
			$module = Modules::getModule($module_name);

			$select_options = $module->getControllers();
		}

		$controller_name->setSelectOptions( $select_options );


		return $controller_name;
	}


	/**
	 * @param string $default_value
	 * @param string $module_name
	 * @param string $controller
	 *
	 * @return Form_Field_Select
	 */
	public static function getField__controller_action( $default_value, $module_name='', $controller='' )
	{
		$controller_action = new Form_Field_Select('controller_action', 'Controller action:', $default_value);
		$controller_action->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select controller action',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select controller action'
		]);

		$select_options = [];

		if(
			$module_name &&
			Modules::exists($module_name)
		) {
			$module = Modules::getModule($module_name);

			$controllers = $module->getControllers();
			if(isset($controllers[$controller])) {
				$select_options = $module->getControllerAction( $controller );
			}
		}

		$controller_action->setSelectOptions( $select_options );


		return $controller_action;

	}


	/**
	 * @param string $default_value
	 *
	 * @return Form_Field_Input
	 */
	public static function getField__controller_class( $default_value )
	{
		$controller_class = new Form_Field_Input('controller_class', 'Custom controller class:', $default_value);
		$controller_class->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter controller class',
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid class name format'
		]);
		$controller_class->setValidator( function( Form_Field $filed ) {
			return Project::validateClassName( $filed );
		} );

		return $controller_class;
	}

	/**
	 * @param string $default_value
	 *
	 * @return Form_Field_Input
	 */
	public static function getField__controller_class_action( $default_value )
	{
		$controller_class_action = new Form_Field_Input('controller_class_action', 'Controller action:', $default_value);
		$controller_class_action->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter controller class action name',
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid action name format'
		]);
		$controller_class_action->setValidator( function( Form_Field $filed ) {
			return Project::validateMethodName( $filed );
		} );

		return $controller_class_action;
	}


	/**
	 * @param string $default_value
	 *
	 * @return Form_Field_Textarea
	 */
	public static function getField__output( $default_value )
	{
		$output = new Form_Field_Textarea('output', 'Static output:', $default_value);
		$output->setValidator( function( Form_Field_Textarea $field ) {
			$value = $field->getValueRaw();

			$field->setValue( $value );


			return true;
		} );
		$output->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter static output'
		]);

		return $output;
	}

	/**
	 * @param string $default_value
	 *
	 * @return Form_Field_Input
	 */
	public static function getField__output_callback_class( $default_value )
	{
		$output_callback_class = new Form_Field_Input('output_callback_class', 'Output callback class:', $default_value);
		$output_callback_class->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter class name',
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid class name format'
		]);
		$output_callback_class->setValidator( function( Form_Field $filed ) {
			return Project::validateClassName( $filed );
		} );

		return $output_callback_class;
	}

	/**
	 * @param Form_Field_Input $output_callback_class_field
	 * @param string $default_value
	 *
	 * @return Form_Field_Input
	 */
	public static function getField__output_callback_method( Form_Field_Input $output_callback_class_field, $default_value )
	{
		$output_callback_method = new Form_Field_Input('output_callback_method', 'Output callback method:', $default_value);
		$output_callback_method->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter method name',
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid method name format'
		]);
		$output_callback_method->setValidator( function( Form_Field $filed ) use ($output_callback_class_field) {
			return Project::validateMethodName( $filed, $output_callback_class_field );
		} );

		return $output_callback_method;
	}



	/**
	 * @param Pages_Page $page
	 *
	 * @return Form
	 */
	public function getEditForm( Pages_Page $page )
	{
		if(!$this->__edit_form) {

			$fields = [];

			$output_position = static::getField__output_position( $this->getOutputPosition(), $page );
			$output_position->setCatcher( function($value) { $this->setOutputPosition( $value );} );
			$fields[] = $output_position;

			$output_position_order = static::getField__output_position_order( $this->getOutputPositionOrder() );
			$output_position_order->setCatcher( function($value) { $this->setOutputPositionOrder( $value ); } );
			$fields[] = $output_position_order;

			$i = 0;
			foreach( $this->parameters as $key=>$val ) {
				
				$param_key = new Form_Field_Input('/params/'.$i.'/key', '', $key);
				$fields[] = $param_key;

				$param_value = new Form_Field_Input('/params/'.$i.'/value', '', $val);
				$fields[] = $param_value;
				
				$i++;
			}
			
			for( $c=0; $c<static::PARAMS_COUNT; $c++) {
				
				$param_key = new Form_Field_Input('/params/'.$i.'/key', '', '');
				$fields[] = $param_key;

				$param_value = new Form_Field_Input('/params/'.$i.'/value', '', '');
				$fields[] = $param_value;
				
				$i++;
			}



			switch( $this->getContentKind() ) {
				case static::CONTENT_KIND_MODULE:
					$module_name =  static::getField__module_name($this->getModuleName());
					$module_name->setCatcher( function($value) { $this->setModuleName( $value ); } );
					$fields[] = $module_name;

					$controller_name = static::getField__controller_name( $this->getControllerName(), $this->getModuleName() );
					$controller_name->setCatcher( function($value) { $this->setControllerName( $value ); } );
					$fields[] = $controller_name;

					$controller_action = static::getField__controller_action( $this->getControllerAction(), $this->getModuleName(), $this->getControllerName() );
					$controller_action->setCatcher( function($value) { $this->setControllerAction( $value ); } );
					$fields[] = $controller_action;

					break;
				case static::CONTENT_KIND_CLASS:
					$controller_class = static::getField__controller_class( $this->getControllerClass() );
					$controller_class->setCatcher( function($value) { $this->setControllerClass( $value ); } );
					$fields[] = $controller_class;


					$controller_class_action = static::getField__controller_class_action( $this->getControllerAction() );
					$controller_class_action->setCatcher( function($value) { $this->setControllerAction( $value ); } );
					$fields[] = $controller_class_action;

					break;
				case static::CONTENT_KIND_STATIC:
					$output = static::getField__output( $this->getOutput() );
					$output->setCatcher( function($value) { $this->setOutput( $value ); } );
					$fields[] = $output;

					break;
				case static::CONTENT_KIND_CALLBACK:
					$callback = $this->getOutput();

					$output_callback_class= static::getField__output_callback_class( $callback[0] );
					$output_callback_method = static::getField__output_callback_method( $output_callback_class, $callback[1] );

					$output_callback_method->setCatcher( function() use ($output_callback_class, $output_callback_method) {

						$class = $output_callback_class->getValue();
						$method = $output_callback_method->getValue();

						$this->setOutput( [$class, $method] );
					} );
					$fields[] = $output_callback_class;
					$fields[] = $output_callback_method;

					break;

			}



			$form = new Form('page_content_edit_form', $fields );

			$form->setAction( Pages::getActionUrl('edit_content') );

			$this->__edit_form = $form;
		}

		return $this->__edit_form;
	}

	/**
	 * @param Form $form
	 * @param string $field_prefix
	 *
	 * @return array
	 */
	public static function catchParams( Form $form, $field_prefix='' )
	{
		$params = [];
		
		$i = 0;
		while( $form->fieldExists( $field_prefix.'/params/'.$i.'/key' ) ) {

			$param_key = $form->field($field_prefix.'/params/'.$i.'/key')->getValue();
			$param_value = $form->field($field_prefix.'/params/'.$i.'/value')->getValue();

			if($param_key) {
				$params[$param_key] = $param_value;
			}

			$i++;
		}

		return $params;
	}

	/**
	 * @param array $data
	 * @return Pages_Page_Content
	 */
	public static function fromArray( array $data )
	{
		$content = new Pages_Page_Content();

		foreach( $data as $k=>$v ) {
			$content->{$k} = $v;
		}

		if($content->getModuleName()) {
			$module_name = $content->getModuleName();

			foreach( Modules::getModules() as $module ) {
				if($module->getName()==$module_name) {
					$content->setModuleName( $module->getInternalId() );
				}
			}

		}


		return $content;
	}
	

	/**
	 * @return array
	 */
	public function toArray()
	{
		$data = parent::toArray();

		if(!empty($data['module_name'])) {

			$module = Modules::getModule( $data['module_name'] );

			if($module) {
				$data['module_name'] = $module->getName();
			}

		}

		return $data;
	}

}