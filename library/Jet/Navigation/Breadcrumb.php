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
class Navigation_Breadcrumb extends BaseObject
{
	/**
	 * @var Navigation_Breadcrumb_Item[] $items
	 */
	protected static array|null $items = null;

	/**
	 *
	 */
	public static function reset(): void
	{
		static::$items = [];
	}

	/**
	 * @param Navigation_Breadcrumb_Item[] $items
	 *
	 */
	public static function set( array $items = [] ): void
	{
		static::$items = [];

		foreach( $items as $dat ) {
			static::addItem( $dat );
		}
	}

	/**
	 * @param Navigation_Breadcrumb_Item $item
	 */
	public static function addItem( Navigation_Breadcrumb_Item $item ): void
	{
		if( static::$items === null ) {
			static::setByPage();
		}

		static::$items[] = $item;

	}

	/**
	 * @param string $title
	 * @param string $URL
	 *
	 * @return Navigation_Breadcrumb_Item
	 */
	public static function addURL( string $title, string $URL = '' ): Navigation_Breadcrumb_Item
	{
		if( static::$items === null ) {
			static::setByPage();
		}

		if( !$URL ) {
			$URL = Http_Request::URL();
		}

		$item = new Navigation_Breadcrumb_Item();
		$item->setTitle( $title );
		$item->setURL( $URL );

		static::addItem( $item );

		return $item;
	}

	/**
	 * @param MVC_Page_Interface $page
	 *
	 * @return Navigation_Breadcrumb_Item
	 */
	public static function addPage( MVC_Page_Interface $page ): Navigation_Breadcrumb_Item
	{
		if( static::$items === null ) {
			static::setByPage();
		}

		$item = new Navigation_Breadcrumb_Item();
		$item->setPage( $page );

		static::addItem( $item );

		return $item;
	}

	/**
	 * @return Navigation_Breadcrumb_Item[]
	 */
	public static function getItems(): array
	{
		if( static::$items === null ) {
			static::setByPage();
		}

		$count = count( static::$items );

		foreach( static::$items as $i => $item ) {
			$i++;
			$item->setIndex( $i );
			$item->setIsLast( $i == $count );
		}

		return static::$items;
	}

	/**
	 * @return Navigation_Breadcrumb_Item
	 */
	public static function getCurrentLastItem(): Navigation_Breadcrumb_Item
	{
		if( static::$items === null ) {
			static::setByPage();
		}

		return static::$items[count( static::$items ) - 1];
	}


	/**
	 * @param MVC_Page_Interface|null $page (optional)
	 */
	public static function setByPage( MVC_Page_Interface $page = null ): void
	{
		if( !$page ) {
			$page = MVC::getPage();
		}

		static::$items = [];

		$item = new Navigation_Breadcrumb_Item();
		$item->setPage( $page );

		static::$items[] = $item;

		$parent = $page;
		while( ($parent = $parent->getParent()) ) {

			$item = new Navigation_Breadcrumb_Item();
			$item->setPage( $parent );

			array_unshift( static::$items, $item );
		}
	}

	/**
	 *
	 * @param int $shift_count
	 */
	public static function shift( int $shift_count ): void
	{
		if( static::$items === null ) {
			static::setByPage();
		}

		if( $shift_count < 0 ) {
			$shift_count = count( static::$items ) + $shift_count;
		}

		for( $c = 0; $c < $shift_count; $c++ ) {
			array_shift( static::$items );
		}
	}

}