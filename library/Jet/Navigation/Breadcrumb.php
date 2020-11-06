<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static $items;

	/**
	 *
	 */
	public static function reset()
	{
		static::$items = [];
	}

	/**
	 * @param Navigation_Breadcrumb_Item[] $items
	 *
	 */
	public static function set( array $items=[] )
	{
		static::$items = [];

		foreach( $items as $dat ) {
			static::addItem( $dat );
		}
	}

	/**
	 * @param Navigation_Breadcrumb_Item $item
	 */
	public static function addItem( Navigation_Breadcrumb_Item $item )
	{
		if(static::$items===null) {
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
	public static function addURL( $title, $URL = '' )
	{
		if(static::$items===null) {
			static::setByPage();
		}

		if( !$URL ) {
			$URL = Http_Request::URL();
		}

		$item = new Navigation_Breadcrumb_Item();
		$item->setTitle( $title );
		$item->setURL( $URL );

		static::addItem($item);

		return $item;
	}

	/**
	 * @param Mvc_Page_Interface $page
	 *
	 * @return Navigation_Breadcrumb_Item
	 */
	public static function addPage( Mvc_Page_Interface $page )
	{
		if(static::$items===null) {
			static::setByPage();
		}

		$item = new Navigation_Breadcrumb_Item();
		$item->setPage( $page );

		static::addItem($item);

		return $item;
	}

	/**
	 * @return Navigation_Breadcrumb_Item[]
	 */
	public static function getItems()
	{
		if(static::$items===null) {
			static::setByPage();
		}

		$count = count( static::$items );

		foreach( static::$items as $i => $item ) {
			$i++;
			$item->setIndex( $i );
			$item->setIsLast( $i==$count );
		}

		return static::$items;
	}

	/**
	 * @return Navigation_Breadcrumb_Item
	 */
	public static function getCurrentLastItem()
	{
		if(static::$items===null) {
			static::setByPage();
		}

		return static::$items[count(static::$items)-1];
	}


	/**
	 * @param Mvc_Page_Interface|null $page (optional)
	 */
	public static function setByPage( Mvc_Page_Interface $page=null )
	{
		if(!$page) {
			$page = Mvc::getCurrentPage();
		}

		static::$items = [];

		$item = new Navigation_Breadcrumb_Item();
		$item->setPage( $page );

		static::$items[] = $item;

		$parent = $page;
		while( ( $parent = $parent->getParent() ) ) {

			$item = new Navigation_Breadcrumb_Item();
			$item->setPage( $parent );

			array_unshift( static::$items, $item );
		}
	}

	/**
	 *
	 * @param int $shift_count
	 */
	public static function shift( $shift_count )
	{

		if( $shift_count<0 ) {
			$shift_count = count( static::$items )+$shift_count;
		}

		for( $c = 0; $c<$shift_count; $c++ ) {
			array_shift( static::$items );
		}
	}

}