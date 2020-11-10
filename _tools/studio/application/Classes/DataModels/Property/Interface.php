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

interface DataModels_Property_Interface
{

	/**
	 * @param DataModels_Parser_Class $class
	 * @param DataModels_Parser_Class_Property $property
	 *
	 * @return DataModels_Property_Interface
	 */
	public static function createByParser( DataModels_Parser_Class $class, DataModels_Parser_Class_Property $property );

	/**
	 * @return string
	 */
	public function getInternalId();

	/**
	 * @param string $id
	 */
	public function setInternalId( $id );

	/**
	 * @return int
	 */
	public function getInternalPriority();

	/**
	 * @param int $internal_priority
	 */
	public function setInternalPriority($internal_priority);

	/**
	 * @return bool
	 */
	public function isInherited();

	/**
	 * @param bool $is_inherited
	 */
	public function setIsInherited( $is_inherited );

	/**
	 * @return string
	 */
	public function getInheritedModelId();

	/**
	 * @param string $inherited_model_id
	 */
	public function setInheritedModelId( $inherited_model_id );

	/**
	 * @return string
	 */
	public function getInheritedPropertyId();

	/**
	 * @param string $inherited_property_id
	 */
	public function setInheritedPropertyId( $inherited_property_id );

	/**
	 * @return bool
	 */
	public function isOverload();

	/**
	 * @param bool $overload
	 */
	public function setOverload( $overload );


	/**
	 * @return string
	 */
	public function getDataModelClassName();

	/**
	 * @param string $data_model_class_name
	 */
	public function setDataModelClassName($data_model_class_name);

	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $name
	 */
	public function setName($name);

	/**
	 * @return null|string
	 */
	public function getRelatedToClassName();

	/**
	 * @param null|string $related_to_class_name
	 */
	public function setRelatedToClassName($related_to_class_name);

	/**
	 * @return null|string
	 */
	public function getRelatedToPropertyName();

	/**
	 * @param null|string $related_to_property_name
	 */
	public function setRelatedToPropertyName($related_to_property_name);

	/**
	 * @return string
	 */
	public function getDatabaseColumnName();

	/**
	 * @param string $database_column_name
	 */
	public function setDatabaseColumnName($database_column_name);

	/**
	 * @return bool
	 */
	public function getIsId();

	/**
	 * @param bool $is_id
	 */
	public function setIsId($is_id);

	/**
	 * @return bool
	 */
	public function getIsKey();

	/**
	 * @param bool $is_key
	 */
	public function setIsKey($is_key);

	/**
	 * @return bool
	 */
	public function getIsUnique();

	/**
	 * @param bool $is_unique
	 */
	public function setIsUnique($is_unique);

	/**
	 * @return bool
	 */
	public function isDoNotExport();

	/**
	 * @param bool $do_not_export
	 */
	public function setDoNotExport($do_not_export);

	/**
	 * @return string
	 */
	public function getDefaultValue();

	/**
	 * @param string $default_value
	 */
	public function setDefaultValue($default_value);

	/**
	 * @return Form
	 */
	public function getEditForm();

	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( &$fields );

	/**
	 * @return bool
	 */
	public function catchEditForm();


	/**
	 *
	 */
	public function showEditForm();

	/**
	 *
	 */
	public function showEditFormFields();

	/**
	 * @return bool
	 */
	public function canBeDeleted();


	/**
	 * @return string
	 */
	public function getHeadCssClass();

	/**
	 * @return string
	 */
	public function getIcons();

	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class );

	/**
	 * @param ClassCreator_Class $class
	 *
	 */
	public function createClassMethods( ClassCreator_Class $class );

	/**
	 *
	 */
	public function prepare();

}