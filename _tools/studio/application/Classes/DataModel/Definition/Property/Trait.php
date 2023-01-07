<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Cache;

use Jet\Exception;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\IO_File;
use Jet\Tr;
use Jet\UI;
use Jet\UI_icon;

/**
 *
 */
trait DataModel_Definition_Property_Trait
{

	/**
	 * @var bool
	 */
	protected bool $_is_inherited = false;

	/**
	 * @var string
	 */
	protected string $_declaring_class_name = '';

	/**
	 * @var bool
	 */
	protected bool $_is_overload = false;

	/**
	 * @var ?Form
	 */
	protected ?Form $__edit_form = null;

	/**
	 * @var DataModel_Class $_class
	 */
	public function setClass( DataModel_Class $_class ): void
	{
		$reflection = $_class->getReflection();

		$property_reflection = $reflection->getProperty( $this->getName() );

		$declaring_class_name = $property_reflection->getDeclaringClass()->getName();
		if( $_class->getFullClassName() != $declaring_class_name ) {
			$this->_is_inherited = true;
			$this->_declaring_class_name = $declaring_class_name;
			$this->_is_overload = false;
		} else {

			$declaring_class_name = $_class->getPropertyDeclaringClass( $this->getName() );
			if( $declaring_class_name ) {
				$this->_is_inherited = true;
				$this->_declaring_class_name = $declaring_class_name;
				$this->_is_overload = true;
			}
		}
	}

	/**
	 * @return bool
	 */
	public function isInherited(): bool
	{
		return $this->_is_inherited;
	}


	/**
	 * @return bool
	 */
	public function isOverload(): bool
	{
		return $this->_is_overload;
	}

	/**
	 * @return string
	 */
	public function getDeclaringClassName(): string
	{
		return $this->_declaring_class_name;
	}





	/**
	 * @return Form
	 */
	public function getEditForm(): Form
	{

		if( !$this->__edit_form ) {

			$name_field = new Form_Field_Input( 'name', 'Property name:' );
			$name_field->setDefaultValue( $this->name );
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter property name',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid property name format',
				'property_is_not_unique' => 'Property with the same name already exists',
			] );
			$name_field->setFieldValueCatcher( function( $value ) {
				//$this->name = $value;
			} );
			$old_name = $this->getName();
			$name_field->setValidator( function( Form_Field_Input $field ) use ( $old_name ) {
				return DataModel_Definition_Property::checkPropertyName( $field, $old_name );
			} );
			$name_field->setIsReadonly( true );


			$type_field = new Form_Field_Select( 'type', 'Type:' );
			$type_field->setDefaultValue( $this->getType() );
			$type_field->setSelectOptions( DataModel_Definition_Property::getPropertyTypes() );
			$type_field->setIsRequired( true );
			$type_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please select property type',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select property type'
			] );

			$database_column_name_field = new Form_Field_Input( 'database_column_name', 'Custom column name:' );
			$database_column_name_field->setDefaultValue( $this->database_column_name );
			$database_column_name_field->setFieldValueCatcher( function( $value ) {
				$this->database_column_name = $value;
			} );


			$is_id_filed = new Form_Field_Checkbox( 'is_id', 'Is ID' );
			$is_id_filed->setDefaultValue( $this->getIsId() );
			$is_id_filed->setFieldValueCatcher( function( $value ) {
				$this->is_id = $value;
			} );

			$is_key_filed = new Form_Field_Checkbox( 'is_key', 'Is key (index)' );
			$is_key_filed->setDefaultValue( $this->getIsKey() );
			$is_key_filed->setFieldValueCatcher( function( $value ) {
				$this->is_key = $value;
			} );

			$is_unique_filed = new Form_Field_Checkbox( 'is_unique', 'Is unique (index)' );
			$is_unique_filed->setDefaultValue( $this->getIsUnique() );
			$is_unique_filed->setFieldValueCatcher( function( $value ) {
				$this->is_unique = $value;
			} );


			$is_do_not_export_filed = new Form_Field_Checkbox( 'is_do_not_export', 'Do not export to XML or JSON' );
			$is_do_not_export_filed->setDefaultValue( $this->doNotExport() );
			$is_do_not_export_filed->setFieldValueCatcher( function( $value ) {
				$this->do_not_export = $value;
			} );

			if( $this->getIsId() && $this->getRelatedToPropertyName() ) {
				$is_do_not_export_filed->setDefaultValue( true );
				$is_do_not_export_filed->setIsReadonly( true );
			}

			$fields = [
				$type_field->getName()                 => $type_field,
				$name_field->getName()                 => $name_field,
				$database_column_name_field->getName() => $database_column_name_field,
				$is_id_filed->getName()                => $is_id_filed,
				$is_key_filed->getName()               => $is_key_filed,
				$is_unique_filed->getName()            => $is_unique_filed,
				$is_do_not_export_filed->getName()     => $is_do_not_export_filed,

			];
			
			$this->getEditFormCustomFields( $fields );

			$form = new Form( 'property_edit_form_' . $this->getName(), $fields );

			$form->setAction( DataModels::getActionUrl( 'property/edit' ) );

			if(
				$this->getRelatedToClassName() &&
				$form->fieldExists( 'type' )
			) {
				$form->field( 'type' )->setIsReadonly( true );
			}

			if( $this->isInherited() ) {
				if( !$this->isOverload() ) {
					$form->setIsReadonly();
				}
			}

			$form->setAction( DataModels::getActionUrl( 'property/edit' ) );

			$this->__edit_form = $form;
		}


		return $this->__edit_form;
	}
	
	/**
	 * @return bool|DataModel_Definition_Property_Interface
	 */
	public function catchEditForm(): bool|DataModel_Definition_Property_Interface
	{
		$form = $this->getEditForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$result = $this;

		if(
			$form->fieldExists( 'type' ) &&
			$form->field( 'type' )->getValue() != $this->getType()
		) {
			$type = $form->field( 'type' )->getValue();

			$class_name = __NAMESPACE__ . '\\DataModels_Property_' . $type;

			/**
			 * @var DataModel_Definition_Property_Interface $new_property ;
			 */
			$new_property = new $class_name();
			$new_property->setName( $this->getName() );
			$new_property->setIsId( $this->getIsId() );
			$new_property->setIsKey( $this->getIsKey() );

			DataModels::getCurrentModel()->addProperty( $new_property );

			$result = $new_property;
		}

		$form->catchFieldValues();


		$this->__edit_form = null;

		return $result;
	}


	/**
	 * @return string
	 */
	public function getHeadCssClass(): string
	{
		/*
		$class = 'bg-default';

		if($this->getIsId()) {
			$class='bg-warning';
		}

		if($this->getRelatedToClassName()) {
			$class = 'bg-info';
		}

		return $class;
		*/
		return '';
	}

	/**
	 * @return string
	 */
	public function getTypeDescription(): string
	{
		return DataModel_Definition_Property::getPropertyTypes()[$this->getType()];
	}

	/**
	 * @return string
	 */
	public function getIcons(): string
	{
		$icon = '';

		if( $this->getRelatedToPropertyName() ) {
			$icon .= UI::icon( 'arrows-alt-h' )
				->setSize( UI_icon::SIZE_EXTRA_SMALL )
				->setTitle( Tr::_( 'Related to parent models' ) );
		}

		if( $this->getIsId() ) {
			$icon .= UI::icon( 'magic' )
				->setSize( UI_icon::SIZE_EXTRA_SMALL )
				->setTitle( Tr::_( 'Is ID' ) );
		}

		if( $this->getIsKey() ) {
			$icon .= UI::icon( 'key' )
				->setSize( UI_icon::SIZE_EXTRA_SMALL )
				->setTitle( Tr::_( 'Is key' ) );
		}

		if( $this->isInherited() ) {

			$icon .= UI::icon( 'angle-double-up' )
				->setSize( UI_icon::SIZE_EXTRA_SMALL )
				->setTitle( Tr::_( 'Is inherited' ) );


			if( $this->isOverload() ) {
				$icon .= UI::icon( 'check' )
					->setSize( UI_icon::SIZE_EXTRA_SMALL )
					->setTitle( Tr::_( 'Overloaded' ) );
			} else {
				$icon .= UI::icon( 'times' )
					->setSize( UI_icon::SIZE_EXTRA_SMALL )
					->setTitle( Tr::_( 'Not overloaded' ) );
			}
		}

		return $icon;
	}


	/**
	 *
	 * @param mixed &$value
	 */
	public function checkValueType( mixed &$value ): void
	{
	}


	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}

	/**
	 * @return null|string
	 */
	public function getRelatedToClassName(): string|null
	{
		return $this->related_to_class_name;
	}

	/**
	 * @param null|string $related_to_class_name
	 */
	public function setRelatedToClassName( ?string $related_to_class_name ): void
	{
		$this->related_to_class_name = $related_to_class_name;
	}

	/**
	 * @return null|string
	 */
	public function getRelatedToPropertyName(): string|null
	{
		return $this->related_to_property_name;
	}

	/**
	 * @param null|string $related_to_property_name
	 */
	public function setRelatedToPropertyName( ?string $related_to_property_name ): void
	{
		$this->related_to_property_name = $related_to_property_name;
	}

	/**
	 * @param string $database_column_name
	 */
	public function setDatabaseColumnName( string $database_column_name ): void
	{
		$this->database_column_name = $database_column_name;
	}

	/**
	 * @return bool
	 */
	public function isId(): bool
	{
		return $this->is_id;
	}

	/**
	 * @param bool $is_id
	 */
	public function setIsId( bool $is_id ): void
	{
		$this->is_id = $is_id;
	}

	/**
	 * @return bool
	 */
	public function isKey(): bool
	{
		return $this->is_key;
	}

	/**
	 * @param bool $is_key
	 */
	public function setIsKey( bool $is_key ): void
	{
		$this->is_key = $is_key;
	}

	/**
	 * @return bool
	 */
	public function isUnique(): bool
	{
		return $this->is_unique;
	}

	/**
	 * @param bool $is_unique
	 */
	public function setIsUnique( bool $is_unique ): void
	{
		$this->is_unique = $is_unique;
	}

	/**
	 * @return bool
	 */
	public function isDoNotExport(): bool
	{
		return $this->do_not_export;
	}

	/**
	 * @param bool $do_not_export
	 */
	public function setDoNotExport( bool $do_not_export ): void
	{
		$this->do_not_export = $do_not_export;
	}
	
	/**
	 *
	 * @param ClassCreator_Class $class
	 * @param string $property_type
	 * @param string $data_model_type
	 * @param array $attributes
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty_main( ClassCreator_Class $class,
	                                          string $property_type,
	                                          string $data_model_type,
	                                          array $attributes = []
	): ClassCreator_Class_Property
	{
		$declared_type = $property_type;

		$property = new ClassCreator_Class_Property( $this->getName(), $property_type, $declared_type );

		$property->setDefaultValue( $this->getDefaultValue() );

		if( $this->getRelatedToClassName() ) {

			if( !str_contains( $this->getRelatedToClassName(), ':' ) ) {
				$to_scope = 'main';
				$to_model_class_name = $this->getRelatedToClassName();
			} else {
				[
					$to_scope,
					$to_model_class_name
				] = explode( ':', $this->getRelatedToClassName() );
			}

			$related_to_model = DataModels::getClass( $to_model_class_name )?->getDefinition();
			if( $related_to_model ) {
				$related_to_property = $related_to_model->getProperty( $this->getRelatedToPropertyName() );

				$property->setAttribute( 'DataModel_Definition', 'related_to', $to_scope . '.' . $related_to_property->getName() );
			} else {
				$class->addError( 'Unable to get related DataModel definition (related model:' . $to_model_class_name . ')' );
			}

		} else {

			$property->setAttribute( 'DataModel_Definition', 'type', $data_model_type );

		}

		if( $this->database_column_name ) {
			$property->setAttribute( 'DataModel_Definition', 'database_column_name', $this->database_column_name );
		}


		if( $this->getIsId() ) {
			$property->setAttribute( 'DataModel_Definition', 'is_id', true );

		}
		if( $this->is_key ) {
			$property->setAttribute( 'DataModel_Definition', 'is_key', true );
		}
		if( $this->do_not_export ) {
			$property->setAttribute( 'DataModel_Definition', 'do_not_export', true );
		}


		foreach( $attributes as $a ) {
			$property->setAttribute( $a[0], $a[1], $a[2] );
		}

		
		return $property;
	}


	/**
	 * @return string
	 */
	public function getSetterGetterMethodName(): string
	{
		return static::generateSetterGetterMethodName( $this->getName() );
	}

	/**
	 * @param string $name
	 *
	 * @return string
	 */
	public static function generateSetterGetterMethodName( string $name ): string
	{
		$name = explode( '_', $name );

		foreach( $name as $i => $n ) {
			$name[$i] = ucfirst( strtolower( $n ) );
		}

		return implode( '', $name );

	}

	/**
	 *
	 */
	public function prepare(): void
	{
		if( !$this->database_column_name ) {
			$this->database_column_name = $this->getName();
		}
	}


	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function update( DataModel_Class $class ): bool
	{
		$ok = true;
		try {
			$model = $class->getDefinition();

			$created_class = $model->createClass();

			if( $created_class->getErrors() ) {
				return false;
			}

			$script = IO_File::read( $class->getScriptPath() );

			$parser = new ClassParser( $script );

			$parser->actualize_updateProperty(
				$class->getClassName(),
				$this->createClassProperty( $created_class )
			);

			$parser->actualize_setUse( $created_class->getUse() );

			IO_File::write(
				$class->getScriptPath(),
				$parser->toString()
			);

			Cache::resetOPCache();


		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}

	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function add( DataModel_Class $class ): bool
	{
		$ok = true;
		try {
			$model = $class->getDefinition();

			$created_class = $model->createClass();

			if( $created_class->getErrors() ) {
				return false;
			}

			$script = IO_File::read( $class->getScriptPath() );

			$parser = new ClassParser( $script );

			$parser->actualize_addProperty(
				$class->getClassName(),
				$this->createClassProperty( $created_class )
			);

			$created_methods = $this->createClassMethods( $created_class );

			foreach( $created_methods as $name ) {
				$method = $created_class->getMethod( $name );

				$parser->actualize_addMethod(
					$class->getClassName(),
					$method
				);
			}

			$parser->actualize_setUse( $created_class->getUse() );

			IO_File::write(
				$class->getScriptPath(),
				$parser->toString()
			);

			Cache::resetOPCache();


		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}

}