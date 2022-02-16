<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class DataModel_Query_Select_Item extends BaseObject
{

	/**
	 *
	 * @var DataModel_Definition_Property|DataModel_Query_Select_Item_Expression|null
	 */
	protected DataModel_Definition_Property|DataModel_Query_Select_Item_Expression|null $item = null;

	/**
	 * @var string
	 */
	protected string $select_as = '';


	/**
	 * @param DataModel_Definition_Property|DataModel_Query_Select_Item_Expression $item
	 * @param string $select_as
	 */
	public function __construct( DataModel_Definition_Property|DataModel_Query_Select_Item_Expression $item, string $select_as )
	{
		$this->item = $item;
		$this->select_as = $select_as;
	}

	/**
	 * @return DataModel_Definition_Property|DataModel_Query_Select_Item_Expression
	 */
	public function getItem(): DataModel_Definition_Property|DataModel_Query_Select_Item_Expression
	{
		return $this->item;
	}

	/**
	 * @return string
	 */
	public function getSelectAs(): string
	{
		return $this->select_as;
	}

}