<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\ApplicationModules;

use Jet\Application_Module_Manifest;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Input;
use Jet\MVC_Cache;
use JetStudio\JetStudio;

/**
 *
 */
class Modules_Manifest extends Application_Module_Manifest
{
	public const MAX_ACL_ACTION_COUNT = 100;

	protected ?Form $__edit_form = null;
	
	protected ?Form $__clone_form = null;
	
	protected ?Modules_MenuItems $menu_items = null;

	protected ?Modules_Pages $pages = null;


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
			JetStudio::handleError( $e );
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


			$form->setAction( Main::getActionUrl( 'edit' ) );

			$this->__edit_form = $form;
		}

		return $this->__edit_form;
	}

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
				Main::exists( $name )
			)
			||
			(
				$old_module_name &&
				$old_module_name != $name &&
				Main::exists( $name )
			)
		) {
			$field->setError('module_name_is_not_unique');
			return false;
		}

		return true;

	}


	/**
	 *
	 */
	public function create_saveManifest(): void
	{
		$this->saveDatafile();

	}

	public function getMenuItems() : Modules_MenuItems {
		if(!$this->menu_items) {
			$this->menu_items = new Modules_MenuItems($this);
		}

		return $this->menu_items;
	}
	

	public function getPages() : Modules_Pages {
		if(!$this->pages) {
			$this->pages = new Modules_Pages($this);
		}

		return $this->pages;
	}
	
	
	public function getCloneForm(): Form
	{
		if( !$this->__clone_form ) {
			
			$module_name = new Form_Field_Input( 'module_name', 'New module name:' );
			$module_name->setDefaultValue( $this->getName() );
			$module_name->setIsRequired( true );
			$module_name->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter module name',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid module name format',
				'module_name_is_not_unique' => 'Module with the same name already exists',
			] );
			$module_name->setValidator( function( Form_Field_Input $field ) {
				$name = $field->getValue();
				
				return Modules_Manifest::checkModuleName( $field, $name );
			} );
			
			
			$module_label = new Form_Field_Input( 'module_label', 'Label:' );
			$module_label->setDefaultValue( $this->getLabel() );
			$module_label->setIsRequired( true );
			$module_label->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter module label'
			] );
			
			
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
			
			
			
			$fields = [
				$module_name,
				$module_label,
				$vendor,
				$version,
			];
			
			$form = new Form( 'clone_module_form', $fields );
			
			$form->setAction( Main::getActionUrl( 'clone' ) );
			
			$this->__clone_form = $form;
		}
		
		return $this->__clone_form;
	}
	
	public function catchCloneForm(): bool|static
	{
		$form = $this->getCloneForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}
		
		$new_manifest = clone $this;
		$new_manifest->setName( $form->field('module_name')->getValue() );
		$new_manifest->setLabel( $form->field('module_label')->getValue() );
		$new_manifest->setVendor( $form->field('vendor')->getValue() );
		$new_manifest->setVersion( $form->field('version')->getValue() );
		
		return $new_manifest;
	}

}