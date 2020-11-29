<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Form;

/**
 */
interface DataModel_Definition_Model_Interface
{
	/**
	 * @return bool
	 */
	public function isAbstract();

	/**
	 * @return bool
	 */
	public function canHaveRelated();

	/**
	 * @return ClassCreator_Class|null
	 */
	public function createClass();

	/**
	 * @param ClassCreator_Class $class
	 * @param string $default
	 *
	 * @return string
	 */
	public function createClass_getExtends( ClassCreator_Class $class, $default );


	/**
	 *
	 * @param string $option
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	public function getIDControllerOption( $option, $default_value );

	/**
	 *
	 * @param string $option
	 * @param mixed $value
	 */
	public function setIDControllerOption( $option, $value );

	/**
	 * @return DataModel_Definition_Relation_External[]
	 */
	public function getExternalRelations();


	/**
	 * @return DataModel_Definition_Property_Interface|\Jet\DataModel_Definition_Property
	 */
	public function getProperties();

	/**
	 *
	 * @param string $property_name
	 *
	 * @return DataModel_Definition_Property_Interface|\Jet\DataModel_Definition_Property
	 */
	public function getProperty( $property_name );



	/**
	 * @return string
	 */
	public function getInternalType();


	/**
	 * @return string
	 */
	public function getModelName();

	/**
	 * @param string $model_name
	 */
	public function setModelName($model_name);

	/**
	 * @return string
	 */
	public function getDatabaseTableName();

	/**
	 * @param string $database_table_name
	 */
	public function setDatabaseTableName($database_table_name);

	/**
	 * @return string
	 */
	public function getClassName();

	/**
	 * @return string
	 */
	public function getExtends();

	/**
	 * @return array
	 */
	public function getImplements();

	/**
	 * @return array
	 */
	public function getExtendsScope();

	/**
	 * @return Form
	 */
	public function getEditForm();

	/**
	 * @return bool
	 */
	public function catchEditForm();


	/**
	 * @param DataModel_Definition_Property_Interface $property
	 */
	public function addProperty(DataModel_Definition_Property_Interface $property );


	/**
	 * @param DataModel_Definition_Key $key
	 */
	public function addCustomNewKey( DataModel_Definition_Key $key );

	/**
	 * @param string $key_name
	 *
	 * @return DataModel_Definition_Key|null
	 */
	public function getCustomKey( $key_name );

	/**
	 * @return DataModel_Definition_Key[]
	 */
	public function getCustomKeys();

	/**
	 * @param string $key_name
	 */
	public function deleteCustomKey( $key_name );


	/**
	 * @param DataModel_Definition_Relation_External $relation
	 */
	public function addExternalRelation( DataModel_Definition_Relation_External $relation );

	/**
	 * @param string $relation_id
	 *
	 * @return DataModel_Definition_Relation_External|null
	 */
	public function getExternalRelation( $relation_id );


	/**
	 * @param string $relation_id
	 */
	public function deleteExternalRelation( $relation_id );

	/**
	 * @return bool
	 */
	public function save();

	/**
	 * @return bool
	 */
	public function create();

}