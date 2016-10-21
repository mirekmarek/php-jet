<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;
use Jet\BaseObject;
use Jet\Mvc;


class UI_breadcrumbNavigation extends BaseObject
{
	/**
	 * @var UI_breadcrumbNavigation_item[] $items
	 */
	protected static $items = [];

	/**
	 * @param string $label
	 * @param string $URL
	 * @return UI_breadcrumbNavigation_item
	 */
	public static function addItem( $label, $URL='' ) {
		if(!$URL) {
			$URL = Mvc::getCurrentRouter()->getRequestURL();
		}

		$item = new UI_breadcrumbNavigation_item();
		$item->setLabel( $label );
		$item->setURL( $URL );

		static::$items[] = $item;

		return $item;
	}

	/**
	 * @return UI_breadcrumbNavigation_item[]
	 */
	public static function getItems() {
		$count = count(static::$items);

		foreach( static::$items as $i=>$item ) {
			$i++;
			$item->setIndex($i);
			$item->setIsLast( $i==$count );

		}

		return static::$items;
	}
}