<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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

interface DataModel_Related_1toN_Iterator_Interface extends \ArrayAccess, \Iterator, \Countable, DataModel_Related_Interface {


	/**
	 * @param DataModel_Definition_Model_Related_1toN $item_definition
	 */
	public function __construct( DataModel_Definition_Model_Related_1toN $item_definition );

	/**
	 * @param array $order_by
	 */
	public function setLoadRelatedDataOrderBy(array $order_by);

	/**
	 * @return array
	 */
	public function getLoadRelatedDataOrderBy();

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
	public function removeAllItems();

}