<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Query_Select_Item
 * @package Jet
 */
class DataModel_Query_Select_Item extends BaseObject
{

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract|DataModel_Query_Select_Item_BackendFunctionCall
	 */
	protected $item;

	/**
	 * @var string
	 */
	protected $select_as = '';


	/**
	 * @param DataModel_Definition_Property_Abstract|DataModel_Query_Select_Item_BackendFunctionCall $item
	 * @param string                                                                                 $select_as
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( $item, $select_as )
	{
		if( !( $item instanceof DataModel_Definition_Property_Abstract )&&!( $item instanceof DataModel_Query_Select_Item_BackendFunctionCall ) ) {
			throw new DataModel_Query_Exception(
				'Item must be instance of DataModel_Definition_Property_Abstract or DataModel_Query_Select_Item_BackendFunctionCall',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);
		}

		$this->item = $item;
		$this->select_as = $select_as;
	}

	/**
	 * @return DataModel_Definition_Property_Abstract|DataModel_Query_Select_Item_BackendFunctionCall
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