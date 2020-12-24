<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface DataModel_Related_Interface extends DataModel_Interface
{

	/**
	 *
	 * @param array                  $where
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return array
	 */
	public static function fetchRelatedData( array $where, DataModel_PropertyFilter $load_filter = null ) : array;

	/**
	 *
	 * @param array  $this_data
	 * @param array  &$related_data
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return mixed
	 */
	public static function initRelatedByData( array $this_data, array &$related_data, DataModel_PropertyFilter $load_filter = null ) : mixed;

	/**
	 * @return DataModel_Related_Interface|DataModel_Related_1toN_Iterator|DataModel_Related_MtoN_Iterator|null
	 */
	public function createNewRelatedDataModelInstance() : DataModel_Related_Interface|DataModel_Related_1toN_Iterator|DataModel_Related_MtoN_Iterator|null;


	/**
	 * @param DataModel_IDController $parent_id
	 */
	public function actualizeParentId( DataModel_IDController $parent_id ) : void;

	/**
	 * @param DataModel_IDController $main_id
	 */
	public function actualizeMainId( DataModel_IDController $main_id ) : void;

	/**
	 *
	 */
	public function save() : void;

	/**
	 *
	 */
	public function delete() : void;

	/**
	 *
	 * @param DataModel_Definition_Property $parent_property_definition
	 * @param DataModel_PropertyFilter|null $property_filter
	 *
	 * @return Form_Field[]
	 *
	 */
	public function getRelatedFormFields( DataModel_Definition_Property $parent_property_definition, DataModel_PropertyFilter $property_filter = null ) : array;

}