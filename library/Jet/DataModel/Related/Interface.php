<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

interface DataModel_Related_Interface {

	/**
	 * @return DataModel_Related_Interface
	 */
	public function createNewRelatedDataModelInstance();


	/**
	 * @param DataModel_Interface $main_model_instance
	 * @param DataModel_Related_Interface $parent_model_instance (optional)
	 *
	 */
	public function setupParentObjects( DataModel_Interface $main_model_instance, DataModel_Related_Interface $parent_model_instance=null );

    /**
     * @param DataModel_Load_OnlyProperties|null $load_only_properties
     *
     * @return array
     */
	public function loadRelatedData( DataModel_Load_OnlyProperties $load_only_properties=null );

	/**
	 * @param array &$loaded_related_data
	 *
	 * @return mixed
	 */
	public function loadRelatedInstances(array &$loaded_related_data );

	/**
	 *
	 */
	public function save();

	/**
	 *
	 */
	public function delete();

	/**
	 * @return array
	 */
	public function getCommonFormPropertiesList();

	/**
	 *
	 * @param DataModel_Definition_Property_Abstract $parent_property_definition
	 * @param array $properties_list
	 *
	 * @return Form_Field_Abstract[]
	 */
	public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, array $properties_list );

	/**
	 * @param array $values
	 *
	 * @return bool
	 */
	public function catchRelatedForm( array $values );


	/**
	 *
	 */
	public function __wakeup_relatedItems();



}