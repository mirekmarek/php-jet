<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;

class Modules_Module_Controller extends BaseObject {

	const MAX_ACTIONS_COUNT = 100;

	protected static $base_class_list = [
		'Jet\Mvc_Controller_Default'  => 'Jet\Mvc_Controller_Default',
		'Jet\Mvc_Controller_REST'     => 'Jet\Mvc_Controller_REST',
	];

	/**
	 * @var string
	 */
	protected $internal_id = '';

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $extends_class = 'Jet\Mvc_Controller_Default';

	/**
	 * @var Modules_Module_Controller_Action[]
	 */
	protected $actions = [];

	/**
	 * @var Form
	 */
	protected $__edit_form;

	/**
	 * @var Form
	 */
	protected static $create_form;


	/**
	 * @return array
	 */
	public static function getBaseClassList()
	{
		return self::$base_class_list;
	}

	/**
	 * @param array $base_class_list
	 */
	public static function setBaseClassList( $base_class_list )
	{
		self::$base_class_list = $base_class_list;
	}

	/**
	 *
	 */
	public function __construct()
	{
		$this->internal_id = uniqid();
	}

	/**
	 * @return string
	 */
	public function getInternalId()
	{
		return $this->internal_id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		$class_name = 'Controller_'.$this->getName();

		return $class_name;
	}

	/**
	 * @return string
	 */
	public function getScriptName()
	{
		$script_name = str_replace('_','/', $this->getClassName()).'.php';

		return $script_name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getExtendsClass()
	{
		return $this->extends_class;
	}

	/**
	 * @param string $extends_class
	 */
	public function setExtendsClass( $extends_class )
	{
		$this->extends_class = $extends_class;
	}

	/**
	 * @param Modules_Module_Controller_Action $action
	 */
	public function addAction( Modules_Module_Controller_Action $action )
	{
		$this->actions[$action->getControllerAction()] = $action;
	}

	/**
	 * @return Modules_Module_Controller_Action[]
	 */
	public function getActions()
	{
		return $this->actions;
	}

	/**
	 * @param array $list
	 */
	public function setActions( array $list )
	{
		$this->actions = [];

		foreach( $list as $controller_action=>$ACL_action ) {
			$action = new Modules_Module_Controller_Action();
			$action->setControllerAction($controller_action);
			$action->setACLAction($ACL_action);

			$this->addAction( $action );
		}
	}


	/**
	 *
	 * @return Form
	 */
	public static function getCreateForm()
	{

		if( !isset(static::$create_form) ) {

			$controller_name =  static::getField__controller_name('');
			$extends_class = static::getField__extends_class('Jet\Mvc_Controller_Default');


			$form = new Form('add_controller_form',[
				$controller_name,
				$extends_class
			]);

			$form->setAction( Modules::getActionUrl('controller/add' ) );

			static::$create_form = $form;
		}

		return static::$create_form;
	}


	/**
	 * @param string $default_value
	 *
	 * @return Form_Field_Input
	 */
	protected static function getField__controller_name( $default_value )
	{
		$controller_name = new Form_Field_Input('controller_name', 'Controller name:', $default_value);
		$controller_name->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter controller name',
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid controller name format'
		]);
		$controller_name->setValidator( function( Form_Field $filed ) {
			return Project::validateControllerName( $filed );
		} );

		return $controller_name;
	}

	/**
	 * @param string $default_value
	 *
	 * @return Form_Field_Select
	 */
	protected static function getField__extends_class( $default_value )
	{

		$extends_class = new Form_Field_Select('extends_class', 'Extends class:', $default_value);
		$extends_class->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select base class',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select base class'
		]);
		$extends_class->setSelectOptions( static::getBaseClassList() );

		return $extends_class;

	}


	/**
	 *
	 * @return bool|Modules_Module_Controller
	 */
	public static function catchCreateForm()
	{
		$form = static::getCreateForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$controller = new Modules_Module_Controller();

		$controller->setName( $form->field('controller_name')->getValue() );
		$controller->setExtendsClass( $form->field('extends_class')->getValue() );

		$default_action = new Modules_Module_Controller_Action();
		$default_action->setACLAction('');
		$default_action->setControllerAction('default');

		$controller->addAction( $default_action );

		return $controller;
	}

	/**
	 * @param Modules_Manifest $module
	 *
	 * @return Form
	 */
	public function getEditForm( Modules_Manifest $module )
	{
		if(!$this->__edit_form) {

			$controller_name =  static::getField__controller_name( $this->getName() );
			$controller_name->setCatcher( function( $value ) {
				$this->setName( $value );
			} );
			$extends_class = static::getField__extends_class( $this->getExtendsClass() );
			$extends_class->setCatcher( function($value) {
				$this->setExtendsClass( $value );
			} );

			$fields = [
				$controller_name,
				$extends_class
			];

			$ACL_actions = [
				'' => 'None - public action without ACL controll'
			];

			foreach( $module->getACLActions( false ) as $ACL_action=>$ACL_action_description ) {
				$ACL_actions[$ACL_action] = $ACL_action_description.' ('.$ACL_action.')';
			}


			$validator = function( Form_Field $field ) {
				$value = $field->getValue();

				if($value) {
					if(
						!preg_match('/^([a-zA-Z1-9\_]{3,})$/', $value) ||
						strpos( $value, '__' )!==false
					) {
						$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

						return false;
					}

				}

				return true;
			};

			$m = 0;
			foreach( $this->getActions() as $action) {

				$controller_action = new Form_Field_Input('/actions/'.$m.'/controller_action', '', $action->getControllerAction() );
				$controller_action->setErrorMessages([
					Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid controller action name format'
				]);
				$controller_action->setValidator( $validator );
				$fields[] = $controller_action;


				$ACL_action = new Form_Field_Select('/actions/'.$m.'/ACL_action', '', $action->getACLAction());
				$ACL_action->setSelectOptions( $ACL_actions );
				$ACL_action->setErrorMessages([
					Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select ACT action'
				]);
				$fields[] = $ACL_action;

				$m++;
			}

			for( $c=0;$c<5;$c++ ) {

				$controller_action = new Form_Field_Input('/actions/'.$m.'/controller_action', '', '' );
				$controller_action->setErrorMessages([
					Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid controller action name format'
				]);
				$controller_action->setValidator( $validator );
				$fields[] = $controller_action;


				$ACL_action = new Form_Field_Select('/actions/'.$m.'/ACL_action', '', '');
				$ACL_action->setSelectOptions( $ACL_actions );
				$ACL_action->setErrorMessages([
					Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select ACT action'
				]);
				$fields[] = $ACL_action;

				$m++;
			}




			$form = new Form('edit_controller_form_'.$this->getInternalId(), $fields );

			$this->__edit_form = $form;
		}

		return $this->__edit_form;
	}

	/**
	 * @param Modules_Manifest $module
	 *
	 * @return bool
	 */
	public function catchEditForm( Modules_Manifest $module )
	{
		$form = $this->getEditForm( $module );
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$form->catchData();

		$actions = [];
		for( $m=0;$m<static::MAX_ACTIONS_COUNT;$m++ ) {
			$a_f_prefix = '/actions/'.$m;


			if(!$form->fieldExists($a_f_prefix.'/controller_action')) {
				break;
			}
			$controller_action = $form->field($a_f_prefix.'/controller_action')->getValue();
			$ACL_action = $form->field($a_f_prefix.'/ACL_action')->getValue();

			if(
			!$controller_action
			) {
				continue;
			}


			$actions[$controller_action] = $ACL_action;
		}

		$this->setActions( $actions );

		$this->__edit_form = null;

		return true;
	}


	/**
	 * @param Modules_Manifest $module
	 *
	 * @return ClassCreator_Class
	 */
	public function createClass( Modules_Manifest $module )
	{
		$class_name = 'Controller_'.$this->getName();

		$class = new ClassCreator_Class();

		$class->setNamespace( rtrim($module->getNamespace(), '\\') );
		$class->setName( $class_name );

		list($extends_ns, $extends_class) = explode('\\', $this->getExtendsClass());


		$class->addUse( new ClassCreator_UseClass($extends_ns, $extends_class) );
		$class->setExtends( $extends_class );


		$ACL_ACTIONS_MAP = [];

		foreach( $this->actions as $action ) {
			$method = new ClassCreator_Class_Method( $action->getControllerAction().'_Action' );
			$method->line(1, '//TODO: implement ...');

			$ACL_action = $action->getACLAction();
			if(!$ACL_action) {
				$ACL_action = false;
			}

			$ACL_ACTIONS_MAP[ $action->getControllerAction() ] = $ACL_action;

			$class->addMethod( $method );
		}

		$class->createConstant( 'ACL_ACTIONS_MAP', $ACL_ACTIONS_MAP );

		$dm = new ClassCreator_ActualizeDecisionMaker();

		$dm->update_constant = function( ClassCreator_Class_Constant $new_constant, ClassParser_Class_Constant $current_constant ) {
			return ( $current_constant->name=='ACL_ACTIONS_MAP' );
		};

		$dm->remove_method = function( ClassParser_Class_Method $current_method ) {
			return ( substr($current_method->name, -7)=='_Action' );
		};

		$class->setActualizeDecisionMaker( $dm );

		return $class;
	}

	/**
	 * @param Modules_Manifest $module
	 *
	 * @return string
	 * @throws \Jet\BaseObject_Exception
	 */
	public function toString( Modules_Manifest $module )
	{
		return $this->createClass( $module )->toString();
	}
}