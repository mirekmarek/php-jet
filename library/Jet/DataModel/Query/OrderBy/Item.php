<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class DataModel_Query_OrderBy_Item extends BaseObject
{

	/**
	 * Property instance
	 *
	 * @var DataModel_Query_Select_Item|DataModel_Definition_Property
	 */
	protected $item;

	/**
	 * @var bool
	 */
	protected $desc = false;


	/**
	 * @param DataModel_Definition_Property|DataModel_Query_Select_Item $item
	 * @param bool                                                      $desc (optional)
	 *
	 * @throws Exception
	 */
	public function __construct( $item, $desc = false )
	{
		if(
			!( $item instanceof DataModel_Definition_Property ) &&
			!( $item instanceof DataModel_Query_Select_Item )
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
	public function getItem()
	{
		return $this->item;
	}

	/**
	 * @return bool
	 */
	public function getDesc()
	{
		return $this->desc;
	}

	/**
	 * @param bool $desc
	 */
	public function setDesc( $desc )
	{
		$this->desc = (bool)$desc;
	}


}