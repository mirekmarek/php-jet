<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Application_Module_Manifest;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Input;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\MVC_Cache;
use Jet\MVC_Controller;
use ReflectionClass;
use ReflectionMethod;

/**
 *
 */
class Modules_Manifest extends Application_Module_Manifest
{
	const MAX_ACL_ACTION_COUNT = 100;

	/**
	 * @var ?Form
	 */
	protected ?Form $__edit_form = null;

	protected ?Modules_MenuItems $__menu_items = null;

	protected ?Modules_Pages $__pages = null;


	/**
	 * @return bool
	 */
	public function save(): bool
	{
		$ok = true;
		try {
			$this->create_saveManifest();
			MVC_Cache::reset();
		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}


	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->_name = $name;
	}

	/**
	 * @param string $vendor
	 */
	public function setVendor( string $vendor ): void
	{
		$this->vendor = $vendor;
	}

	/**
	 * @param string $version
	 */
	public function setVersion( string $version ): void
	{
		$this->version = $version;
	}

	/**
	 * @param string $label
	 */
	public function setLabel( string $label ): void
	{
		$this->label = $label;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( string $description ): void
	{
		$this->description = $description;
	}

	/**
	 * @param bool $is_mandatory
	 */
	public function setIsMandatory( bool $is_mandatory ): void
	{
		$this->is_mandatory = $is_mandatory;
	}

	/**
	 * @param array $ACL_actions
	 */
	public function setACLActions( array $ACL_actions ): void
	{
		$this->ACL_actions = $ACL_actions;
	}

	/**
	 *
	 * @return Form
	 */
	public function getEditForm(): Form
	{
		if( !$this->__edit_form ) {

			$module_name = new Form_Field_Input( 'module_name', 'Name:' );
			$module_name->setDefaultValue( $this->getName() );
			$module_name->setIsRequired( true );
			$module_name->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter module name',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid module name format',
				'module_name_is_not_unique' => 'Module with the same name already exists',
			] );
			$module_name->setValidator( function( Form_Field_Input $field ) {
				$name = $field->getValue();
				$old_module_name = $this->getName();

				return Modules_Manifest::checkModuleName( $field, $name, $old_module_name );
			} );
			$module_name->setFieldValueCatcher( function( $value ) {
				$this->setName( $value );
			} );


			$module_label = new Form_Field_Input( 'module_label', 'Label:' );
			$module_label->setDefaultValue( $this->getLabel() );
			$module_label->setIsRequired( true );
			$module_label->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter module label'
			] );
			$module_label->setFieldValueCatcher( function( $value ) {
				$this->setLabel( $value );
			} );


			$vendor = new Form_Field_Input( 'vendor', 'Vendor:' );
			$vendor->setDefaultValue( $this->getVendor() );
			$vendor->setFieldValueCatcher( function( $value ) {
				$this->setVendor( $value );
			} );

			$version = new Form_Field_Input( 'version', 'Version:'  );
			$version->setDefaultValue( $this->getVersion() );
			$version->setFieldValueCatcher( function( $value ) {
				$this->setVersion( $value );
			} );

			$description = new Form_Field_Input( 'description', 'Description:' );
			$description->setDefaultValue( $this->getDescription() );
			$description->setFieldValueCatcher( function( $value ) {
				$this->setDescription( $value );
			} );

			$is_mandatory = new Form_Field_Checkbox( 'is_mandatory', 'Is mandatory' );
			$is_mandatory->setDefaultValue( $this->isMandatory() );
			$is_mandatory->setFieldValueCatcher( function( $value ) {
				$this->setIsMandatory( $value );
			} );

			$is_active = new Form_Field_Checkbox( 'is_active', 'Is active' );
			$is_active->setDefaultValue( $this->isActivated() );
			$is_active->setIsReadonly( true );

			$is_installed = new Form_Field_Checkbox( 'is_installed', 'Is installed',  );
			$is_installed->setDefaultValue( $this->isInstalled() );
			$is_installed->setIsReadonly( true );


			$fields = [
				$module_name,
				$module_label,
				$vendor,
				$version,
				$description,
				$is_mandatory,
				$is_active,
				$is_installed,
			];


			$m = 0;
			foreach( $this->getACLActions( false ) as $action => $description ) {

				$acl_action = new Form_Field_Input( '/ACL_action/' . $m . '/action', 'Action:' );
				$acl_action->setDefaultValue( $action );
				$fields[] = $acl_action;

				$acl_action_description = new Form_Field_Input( '/ACL_action/' . $m . '/description', 'Label:' );
				$acl_action_description->setDefaultValue( $description );
				$fields[] = $acl_action_description;

				$m++;
			}

			for( $c = 0; $c < 8; $c++ ) {

				$acl_action = new Form_Field_Input( '/ACL_action/' . $m . '/action', 'Action:' );
				$fields[] = $acl_action;

				$acl_action_description = new Form_Field_Input( '/ACL_action/' . $m . '/description', 'Label:' );
				$fields[] = $acl_action_description;

				$m++;
			}


			$form = new Form( 'edit_module_form', $fields );


			$form->setAction( Modules::getActionUrl( 'edit' ) );

			$this->__edit_form = $form;
		}

		return $this->__edit_form;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm(): bool
	{
		$form = $this->getEditForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$form->catchFieldValues();
		$this->catchEditForm_ACLAction( $form );


		return true;
	}

	/**
	 * @param Form $form
	 *
	 */
	public function catchEditForm_ACLAction( Form $form ): void
	{
		$ACL_actions = [];
		for( $m = 0; $m < static::MAX_ACL_ACTION_COUNT; $m++ ) {
			if( !$form->fieldExists( '/ACL_action/' . $m . '/action' ) ) {
				break;
			}

			$action = $form->field( '/ACL_action/' . $m . '/action' )->getValue();
			$description = $form->field( '/ACL_action/' . $m . '/description' )->getValue();

			if(
			!$action
			) {
				continue;
			}

			if( !$description ) {
				$description = $action;
			}

			$ACL_actions[$action] = $description;
		}

		$this->ACL_actions = $ACL_actions;

	}


	/**
	 * @param Form_Field_Input $field
	 * @param string $name
	 * @param string $old_module_name
	 *
	 * @return bool
	 */
	public static function checkModuleName( Form_Field_Input $field, string $name, string $old_module_name = '' ): bool
	{

		if(
			!preg_match( '/^[a-z0-9.]{3,}$/i', $name ) ||
			str_contains( $name, '..' ) ||
			$name[0] == '.' ||
			$name[strlen( $name ) - 1] == '.'
		) {
			$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
			return false;
		}

		if(
			(
				!$old_module_name &&
				Modules::exists( $name )
			)
			||
			(
				$old_module_name &&
				$old_module_name != $name &&
				Modules::exists( $name )
			)
		) {
			$field->setError('module_name_is_not_unique');
			return false;
		}

		return true;

	}



	/**
	 * @return array
	 */
	public function getControllers(): array
	{
		$controllers = [];

		/**
		 * @param string $dir
		 */
		$readDir = function( string $dir ) use ( &$readDir, &$controllers ) {
			$dirs = IO_Dir::getList( $dir, '*', true, false );
			$files = IO_Dir::getList( $dir, '*.php', false, true );

			foreach( $files as $path => $name ) {
				$file_data = IO_File::read( $path );

				$parser = new ClassParser( $file_data );

				foreach( $parser->classes as $class ) {
					$full_name = $parser->namespace->namespace . '\\' . $class->name;

					$_class = new ReflectionClass( $full_name );

					$parents = [];

					while( ($parent = $_class->getParentClass()) ) {
						$parents[] = $parent->getName();
						$_class = $parent;
					}

					if( !in_array( MVC_Controller::class, $parents ) ) {
						continue;
					}

					$c_n = substr( $class->name, 11 );

					$controllers[$c_n] = $c_n;
				}

			}

			foreach( $dirs as $path => $name ) {
				$readDir( $path );
			}
		};

		$readDir( $this->getModuleDir() . 'Controller/' );

		return $controllers;
	}

	/**
	 * @param string $controller_name
	 *
	 * @return array
	 */
	public function getControllerAction( string $controller_name ): array
	{
		$class_name = $this->getNamespace() . 'Controller_' . $controller_name;

		$reflection = new ReflectionClass( $class_name );

		$methods = $reflection->getMethods( ReflectionMethod::IS_PUBLIC );

		$actions = [];

		foreach( $methods as $method ) {
			$name = $method->getName();
			if( !str_ends_with( $name, '_Action' ) ) {
				continue;
			}

			$name = substr( $name, 0, -7 );

			$actions[$name] = $name;
		}

		return $actions;
	}



	/**
	 *
	 */
	public function create_saveManifest(): void
	{
		$this->saveDatafile();

	}

	public function getMenuItems() : Modules_MenuItems {
		if(!$this->__menu_items) {
			$this->__menu_items = new Modules_MenuItems($this);
		}

		return $this->__menu_items;
	}

	public function getPages() : Modules_Pages {
		if(!$this->__pages) {
			$this->__pages = new Modules_Pages($this);
		}

		return $this->__pages;
	}

}