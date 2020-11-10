<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;


use Jet\Form;

interface DataModels_Model_Interface {

	/**
	 * @param DataModels_Parser_Class $class
	 *
	 * @return DataModels_Model_Interface
	 */
	public static function createByParser( DataModels_Parser_Class $class );

	/**
	 * @return string
	 */
	public function getNamespaceId();

	/**
	 * @param string $namespace_id
	 */
	public function setNamespaceId($namespace_id);

	/**
	 * @return string
	 */
	public function getInternalType();

	/**
	 * @return string
	 */
	public function getInternalId();

	/**
	 * @return bool
	 */
	public function isAbstract();

	/**
	 * @param bool $is_abstract
	 */
	public function setIsAbstract( $is_abstract );

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
	 * @param string $class_name
	 */
	public function setClassName($class_name);


	/**
	 * @param DataModels_Model_Related_Interface $child
	 */
	public function addChild(DataModels_Model_Related_Interface $child );

	/**
	 * @param DataModels_Model_Related_Interface $child
	 */
	public function removeChild(DataModels_Model_Related_Interface $child );

	/**
	 * @return array
	 */
	public function getChildrenIds();

	/**
	 * @param array $internal_children_ids
	 */
	public function setInternalChildrenIds( $internal_children_ids );

	/**
	 * @return string
	 */
	public function getExtends();

	/**
	 * @param string $extends
	 * @param bool $handle_inheritance
	 */
	public function setExtends( $extends, $handle_inheritance=true );

	/**
	 * @return array
	 */
	public function getImplements();

	/**
	 * @param array $implements
	 */
	public function setImplements( array $implements );


	/**
	 * @return array
	 */
	public function getExtendsPath();

	/**
	 * @return array
	 */
	public function getExtendsScope();

	/**
	 * @param DataModels_Model_Interface $model
	 *
	 * @return bool
	 */
	public function isDescendantOf( DataModels_Model_Interface $model );


	/**
	 *
	 * @return DataModels_Model_Related_1to1[]|DataModels_Model_Related_1toN[]|DataModels_Model_Related_MtoN[]
	 */
	public function getChildren();

	/**
	 * @return Form
	 */
	public function getEditForm();

	/**
	 *
	 */
	public function showEditFormFields();

	/**
	 * @return bool
	 */
	public function catchEditForm();


	/**
	 * @param DataModels_Property_Interface $property
	 */
	public function addProperty(DataModels_Property_Interface $property );

	/**
	 * @param DataModels_Property_Interface $property
	 */
	public function replaceProperty(DataModels_Property_Interface $property );

	/**
	 * @param string $property_id
	 */
	public function removeProperty( $property_id );

	/**
	 * @param string $property_id
	 */
	public function deleteProperty( $property_id );

	/**
	 * @return DataModels_Property_Interface[]
	 */
	public function getProperties();

	/**
	 *
	 */
	public function checkIdProperties();

	/**
	 *
	 */
	public function delete();

	/**
	 * @param DataModels_Property_Interface $property
	 */
	public function propertyUpdated( DataModels_Property_Interface  $property );


	/**
	 * @param DataModels_Key $key
	 */
	public function addNewKey( DataModels_Key $key );

	/**
	 * @param string $key_id
	 *
	 * @return DataModels_Key|null
	 */
	public function getKey( $key_id );

	/**
	 * @return DataModels_Key[]
	 */
	public function getKeys();

	/**
	 * @param string $key_id
	 */
	public function deleteKey( $key_id );


	/**
	 * @param DataModels_OuterRelation $relation
	 */
	public function addOuterRelation( DataModels_OuterRelation $relation );

	/**
	 * @param string $relation_id
	 *
	 * @return DataModels_OuterRelation|null
	 */
	public function getOuterRelation( $relation_id );

	/**
	 * @return DataModels_OuterRelation[]
	 */
	public function getOuterRelations();

	/**
	 * @param string $relation_id
	 */
	public function deleteOuterRelation( $relation_id );

	/**
	 *
	 */
	public function checkSortOfProperties();

	/**
	 * @return bool
	 */
	public function canHaveRelated();

	/**
	 *
	 */
	public function checkOuterRelations();

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

}
