<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\DataModel_Definition_Model;
use Jet\Form_Field;
use Jet\Form_Field_Select;

/**
 *
 */
abstract class DataModel_Definition_Id_Abstract
{

	/**
	 * @var DataModel_Definition_Model_Interface|DataModel_Definition_Model|null
	 */
	protected DataModel_Definition_Model_Interface|DataModel_Definition_Model|null $model = null;


	/**
	 *
	 * @param DataModel_Definition_Model_Interface $model
	 */
	public function __construct( DataModel_Definition_Model_Interface $model )
	{
		$this->model = $model;
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_IdDefinition( ClassCreator_Class $class ): void
	{

		$id_controller_class = $this->model->getIDControllerClassName();
		$id_controller_class = str_replace( 'Jet\\', '', $id_controller_class );
		$class->addUse( new ClassCreator_UseClass( 'Jet', $id_controller_class ) );

		$class->setAttribute( 'DataModel_Definition', 'id_controller_class', $id_controller_class . '::class' );
	}

	/**
	 * @param string $type
	 *
	 * @return array
	 */
	protected function getIdProperties( string $type ): array
	{
		$id_properties = [];

		foreach( $this->model->getProperties() as $property ) {
			if(
				!$property->getIsId() ||
				$property->getType() != $type ||
				$property->getRelatedToPropertyName()
			) {
				continue;
			}

			$id_properties[$property->getName()] = $property->getName();
		}

		return $id_properties;

	}

	/**
	 * @param string $type
	 *
	 * @return bool|string
	 */
	public function getSelectedIdPropertyName( string $type ): bool|string
	{

		$id_properties = $this->getIdProperties( $type );

		if( !$id_properties ) {
			return false;
		}

		$default_id_property = array_keys( $id_properties )[0];

		$id_property = $this->model->getIDControllerOption( 'id_property_name', $default_id_property );
		if( !isset( $id_properties[$id_property] ) ) {
			$id_property = $default_id_property;
		}

		return $id_properties[$id_property];

	}


	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return array
	 */
	public function createClassMethods( ClassCreator_Class $class ): array
	{
		return [];
	}

	/**
	 * @return array
	 */
	abstract public function getOptionsList(): array;

	/**
	 *
	 * @return Form_Field[]
	 */
	abstract public function getOptionsFormFields(): array;

	/**
	 *
	 * @param string $type
	 *
	 * @return Form_Field_Select
	 */
	protected function getOptionsFormField_idProperty( string $type ): Form_Field_Select
	{
		$id_properties = $this->getIdProperties( $type );

		$default_id_property = '';
		if( $id_properties ) {
			$default_id_property = array_keys( $id_properties )[0];
		}

		if( !$id_properties ) {
			$id_properties = ['' => ''];
		}

		$id_property_name = new Form_Field_Select( 'id_property_name', 'ID property:' );
		$id_property_name->setDefaultValue( $this->model->getIDControllerOption( 'id_property_name', $default_id_property ) );
		$id_property_name->setSelectOptions( $id_properties );
		$id_property_name->setErrorMessages( [
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select ID property'
		] );
		$id_property_name->setFieldValueCatcher( function( $value ) {
			$this->model->setIDControllerOption( 'id_property_name', $value );
		} );

		return $id_property_name;
	}

}