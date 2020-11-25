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
class Navigation_Menu extends BaseObject
{

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var Navigation_Menu
	 */
	protected $parent_menu;

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var string
	 */
	protected $icon = '';

	/**
	 * @var int
	 */
	protected $index = 0;

	/**
	 * @var Navigation_Menu_Item[]|Navigation_Menu[]
	 */
	protected $items = [];


	/**
	 * menu constructor.
	 *
	 * @param string $id
	 * @param string $label
	 * @param int    $index
	 * @param string $icon
	 *
	 */
	public function __construct( $id, $label, $index, $icon = '' )
	{

		$this->id = $id;
		$this->label = $label;

		$this->index = $index;
		$this->icon = $icon;

	}


	/**
	 * @return Navigation_Menu|null
	 */
	public function getParentMenu()
	{
		return $this->parent_menu;
	}

	/**
	 * @param Navigation_Menu $parent_menu
	 */
	public function setParentMenu( Navigation_Menu $parent_menu )
	{
		$this->parent_menu = $parent_menu;
	}

	/**
	 * @param bool $absolute (optional)
	 *
	 * @return string
	 */
	public function getId( $absolute=true )
	{
		if($this->parent_menu && $absolute) {
			return $this->getParentMenu()->getId().'/'.$this->id;
		}
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel( $label )
	{
		$this->label = $label;
	}

	/**
	 * @return int
	 */
	public function getIndex()
	{
		return $this->index;
	}

	/**
	 * @param int $index
	 */
	public function setIndex( $index )
	{
		$this->index = $index;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @param string $icon
	 */
	public function setIcon( $icon )
	{
		$this->icon = $icon;
	}


	/**
	 * @param Navigation_Menu_Item $item
	 *
	 * @throws Navigation_Menu_Exception
	 */
	public function addItem( Navigation_Menu_Item $item )
	{
		$item->setMenu( $this );

		$id = $item->getId();
		if( isset($this->items[$id]) ) {
			throw new Navigation_Menu_Exception( 'Duplicate menu element: '.$id);
		}

		$this->items[$id] = $item;
	}

	/**
	 * @param Navigation_Menu $menu
	 *
	 * @throws Navigation_Menu_Exception
	 */
	public function addMenu( Navigation_Menu $menu )
	{
		$menu->setParentMenu( $this );

		$id = $menu->getId();
		if( isset($this->items[$id]) ) {
			throw new Navigation_Menu_Exception( 'Duplicate menu element: '.$id);
		}

		$this->items[$id] = $menu;
	}

	/**
	 * @return bool
	 */
	public function getAccessAllowed()
	{
		return (count($this->getItems())>0);
	}

	/**
	 * @param bool $check_access
	 *
	 * @return Navigation_Menu[]|Navigation_Menu_Item[]
	 */
	public function getItems( $check_access=true )
	{
		$items = [];

		foreach( $this->items as $i ) {
			if(
				$check_access &&
				!$i->getAccessAllowed()
			) {
				continue;
			}

			$items[] = $i;
		}

		static::sortMenuItems($items);

		return $items;
	}

	/**
	 * @param Navigation_Menu_Item[]|Navigation_Menu[] $items
	 */
	public function setItems( $items )
	{
		$this->items = [];

		foreach( $items as $item ) {
			if($item instanceof Navigation_Menu_Item) {
				$this->addItem( $item );
			}

			if($item instanceof Navigation_Menu) {
				$this->addMenu( $item );
			}
		}
	}



	/**
	 * @param Navigation_Menu[]|Navigation_Menu_Item[] $items
	 */
	public static function sortMenuItems( array &$items )
	{
		uasort(
			$items,
			function( $a, $b ) {
				/**
				 * @var Navigation_Menu|Navigation_Menu_Item $a
				 * @var Navigation_Menu|Navigation_Menu_Item $b
				 */
				return strcmp( $a->getLabel(), $b->getLabel() );
			}
		);

		uasort(
			$items,
			function( $a, $b ) {
				/**
				 * @var Navigation_Menu|Navigation_Menu_Item $a
				 * @var Navigation_Menu|Navigation_Menu_Item $b
				 */

				if( $a->getIndex()==$b->getIndex() ) {
					return 0;
				}

				return ( $a->getIndex()<$b->getIndex() ) ? -1 : 1;
			}
		);

	}

}