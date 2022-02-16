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
class DataModel_Query_OrderBy_Item extends BaseObject
{

	/**
	 *
	 * @var DataModel_Query_Select_Item|DataModel_Definition_Property|null
	 */
	protected DataModel_Query_Select_Item|DataModel_Definition_Property|null $item = null;

	/**
	 * @var bool
	 */
	protected bool $desc = false;


	/**
	 * @param DataModel_Definition_Property|DataModel_Query_Select_Item $item
	 * @param bool $desc (optional)
	 *
	 * @throws Exception
	 */
	public function __construct( DataModel_Definition_Property|DataModel_Query_Select_Item $item, bool $desc = false )
	{
		if(
			!($item instanceof DataModel_Definition_Property) &&
			!($item instanceof DataModel_Query_Select_Item)
		) {
			throw new Exception(
				'Item must be instance of \'DataModel_Definition_Property_Abstract\' or \'DataModel_Query_Select_Item\' '
			);
		}

		$this->item = $item;
		$this->desc = $desc;
	}

	/**
	 * @return DataModel_Definition_Property|DataModel_Query_Select_Item
	 */
	public function getItem(): DataModel_Definition_Property|DataModel_Query_Select_Item
	{
		return $this->item;
	}

	/**
	 * @return bool
	 */
	public function getDesc(): bool
	{
		return $this->desc;
	}

	/**
	 * @param bool $desc
	 */
	public function setDesc( bool $desc ): void
	{
		$this->desc = $desc;
	}


}