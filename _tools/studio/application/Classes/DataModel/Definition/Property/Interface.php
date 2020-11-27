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

/**
 *
 */
interface DataModel_Definition_Property_Interface {

	/**
	 * @var DataModel_Class $_class
	 */
	public function setClass( DataModel_Class $_class );

	/**
	 * @return string
	 */
	public function getDeclaringClassName();

	/**
	 * @return bool
	 */
	public function isInherited();

	/**
	 * @return bool
	 */
	public function isOverload();

	/**
	 * @return string
	 */
	public function getHeadCssClass();

	/**
	 * @return string
	 */
	public function getTypeDescription();

	/**
	 * @return string
	 */
	public function getIcons();



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

	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function update( DataModel_Class $class );


	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function add( DataModel_Class $class );

}