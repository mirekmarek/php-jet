<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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

interface DataModel_Related_1toN_Iterator_Interface extends DataModel_Related_Interface, \ArrayAccess, \Iterator, \Countable {


	/**
	 * @param DataModel_Definition_Model_Related_1toN $item_definition
	 */
	public function __construct( DataModel_Definition_Model_Related_1toN $item_definition );

	/**
	 *
	 * @param DataModel_Definition_Property_Abstract $parent_property_definition
	 * @param DataModel_PropertyFilter|null $property_filter
	 *
	 * @return Form_Field_Abstract[]
	 *
	 */
	public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, DataModel_PropertyFilter $property_filter=null );

	/**
	 *
	 */
	public function removeAllItems();

}