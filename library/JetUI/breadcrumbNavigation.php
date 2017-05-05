<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;

use Jet\BaseObject;
use Jet\Mvc;


/**
 * Class breadcrumbNavigation
 * @package JetUI
 */
class breadcrumbNavigation extends BaseObject
{
	/**
	 * @var breadcrumbNavigation_item[] $items
	 */
	protected static $items = [];

	/**
	 * @param string $label
	 * @param string $URL
	 *
	 * @return breadcrumbNavigation_item
	 */
	public static function addItem( $label, $URL = '' )
	{
		if( !$URL ) {
			$URL = Mvc::getCurrentRouter()->getRequestURL();
		}

		$item = new breadcrumbNavigation_item();
		$item->setLabel( $label );
		$item->setURL( $URL );

		static::$items[] = $item;

		return $item;
	}

	/**
	 * @return breadcrumbNavigation_item[]
	 */
	public static function getItems()
	{
		$count = count( static::$items );

		foreach( static::$items as $i => $item ) {
			$i++;
			$item->setIndex( $i );
			$item->setIsLast( $i==$count );

		}

		return static::$items;
	}
}