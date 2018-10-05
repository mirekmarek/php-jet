<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class DataModel_Query_Select_Item extends BaseObject
{

	/**
	 *
	 * @var DataModel_Definition_Property|DataModel_Query_Select_Item_Expression
	 */
	protected $item;

	/**
	 * @var string
	 */
	protected $select_as = '';


	/**
	 * @param DataModel_Definition_Property|DataModel_Query_Select_Item_Expression $item
	 * @param string                                                               $select_as
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( $item, $select_as )
	{
		if(
			!( $item instanceof DataModel_Definition_Property ) &&
			!( $item instanceof DataModel_Query_Select_Item_Expression )
		) {
			throw new DataModel_Query_Exception(
				'Item must be instance of DataModel_Definition_Property_Abstract or DataModel_Query_Select_Item_BackendFunctionCall',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);
		}

		if(
			$item instanceof DataModel_Query_Having_Expression &&
			!is_string($select_as)
		) {
			throw new DataModel_Query_Exception(
				'The item is DataModel_Query_Select_Item_BackendFunctionCall. So the key must be string ( because: key = select AS for SQL query )',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);
		}

		$this->item = $item;
		$this->select_as = $select_as;
	}

	/**
	 * @return DataModel_Definition_Property|DataModel_Query_Select_Item_Expression
	 */
	public function getItem()
	{
		return $this->item;
	}

	/**
	 * @return string
	 */
	public function getSelectAs()
	{
		return $this->select_as;
	}

}