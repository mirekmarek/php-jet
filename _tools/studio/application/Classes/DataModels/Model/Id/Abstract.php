<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Form_Field;
use Jet\Form_Field_Select;

abstract class DataModels_Model_Id_Abstract {

	/**
	 * @var DataModels_Model_Interface|DataModels_Model
	 */
	protected $model;


	/**
	 *
	 * @param DataModels_Model_Interface $model
	 */
	public function __construct( DataModels_Model_Interface $model )
	{
		$this->model = $model;
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClassIdDefinition( ClassCreator_Class $class )
	{

		$id_controller_class_name = $this->model->getIDControllerClassName();
		$id_controller_class_name = str_replace('Jet\\', '', $id_controller_class_name);
		$class->addUse( new ClassCreator_UseClass('Jet', $id_controller_class_name) );

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'id_controller_class_name', var_export($id_controller_class_name, true)) )
		);
	}

	/**
	 * @param string $type
	 *
	 * @return array
	 */
	protected function getIdProperties( $type )
	{
		$id_properties = [];

		foreach( $this->model->getProperties() as $property ) {
			if(
				!$property->getIsId() ||
				$property->getType()!=$type ||
				$property->getRelatedToPropertyName()
			) {
				continue;
			}

			$id_properties[$property->getInternalId()] = $property->getName();
		}

		return $id_properties;

	}

	/**
	 * @param string $type
	 *
	 * @return bool|string
	 */
	public function getSelectedIdPropertyName( $type )
	{

		$id_properties = $this->getIdProperties($type);

		if(!$id_properties) {
			return false;
		}

		$default_id_property = array_keys($id_properties)[0];

		$id_property = $this->model->getIDControllerOption('id_property_name', $default_id_property);
		if(!isset($id_properties[$id_property])) {
			$id_property = $default_id_property;
		}

		return $id_properties[$id_property];

	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClassMethods( ClassCreator_Class $class )
	{

	}

	/**
	 * @return array
	 */
	abstract public function getOptionsList();

	/**
	 *
	 * @return Form_Field[]
	 */
	abstract public function getOptionsFormFields();

	/**
	 *
	 * @param string $type
	 *
	 * @return Form_Field_Select
	 */
	protected function getOptionsFormField_idProperty( $type ) {
		$id_properties = $this->getIdProperties( $type );

		$default_id_property = '';
		if($id_properties) {
			$default_id_property = array_keys($id_properties)[0];
		}

		if(!$id_properties) {
			$id_properties = [''=>''];
		}

		$id_property_name = new Form_Field_Select('id_property_name', 'ID property:', $this->model->getIDControllerOption('id_property_name', $default_id_property));
		$id_property_name->setSelectOptions($id_properties);
		$id_property_name->setErrorMessages([
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select ID property'
		]);
		$id_property_name->setCatcher( function($value) {
			$this->model->setIDControllerOption('id_property_name', $value);
		} );

		return $id_property_name;
	}

}