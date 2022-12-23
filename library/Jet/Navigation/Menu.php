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
class Navigation_Menu extends BaseObject
{

	/**
	 * @var string
	 */
	protected string $id = '';

	/**
	 * @var Navigation_Menu|null
	 */
	protected Navigation_Menu|null $parent_menu = null;

	/**
	 * @var string
	 */
	protected string $label = '';

	/**
	 * @var string
	 */
	protected string $icon = '';

	/**
	 * @var int
	 */
	protected int $index = 0;

	/**
	 * @var Navigation_Menu_Item[]|Navigation_Menu[]
	 */
	protected array $items = [];


	/**
	 * menu constructor.
	 *
	 * @param string $id
	 * @param string $label
	 * @param int $index
	 * @param string $icon
	 *
	 */
	public function __construct( string $id, string $label, int $index, string $icon = '' )
	{

		$this->id = $id;
		$this->label = $label;

		$this->index = $index;
		$this->icon = $icon;

	}


	/**
	 * @return Navigation_Menu|null
	 */
	public function getParentMenu(): Navigation_Menu|null
	{
		return $this->parent_menu;
	}

	/**
	 * @param Navigation_Menu $parent_menu
	 */
	public function setParentMenu( Navigation_Menu $parent_menu ): void
	{
		$this->parent_menu = $parent_menu;
	}

	/**
	 * @param bool $absolute (optional)
	 *
	 * @return string
	 */
	public function getId( bool $absolute = true ): string
	{
		if( $this->parent_menu && $absolute ) {
			return $this->getParentMenu()->getId() . '/' . $this->id;
		}
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( string $id ): void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel( string $label ): void
	{
		$this->label = $label;
	}

	/**
	 * @return int
	 */
	public function getIndex(): int
	{
		return $this->index;
	}

	/**
	 * @param int $index
	 */
	public function setIndex( int $index ): void
	{
		$this->index = $index;
	}

	/**
	 * @return string
	 */
	public function getIcon(): string
	{
		return $this->icon;
	}

	/**
	 * @param string $icon
	 */
	public function setIcon( string $icon ): void
	{
		$this->icon = $icon;
	}


	/**
	 * @param Navigation_Menu_Item $item
	 *
	 * @throws Navigation_Menu_Exception
	 */
	public function addItem( Navigation_Menu_Item $item ): void
	{
		$item->setMenu( $this );

		$id = $item->getId();
		if( isset( $this->items[$id] ) ) {
			throw new Navigation_Menu_Exception( 'Duplicate menu element: ' . $id );
		}

		$this->items[$id] = $item;
	}

	/**
	 * @param Navigation_Menu $menu
	 *
	 * @throws Navigation_Menu_Exception
	 */
	public function addMenu( Navigation_Menu $menu ): void
	{
		$menu->setParentMenu( $this );

		$id = $menu->getId();
		if( isset( $this->items[$id] ) ) {
			throw new Navigation_Menu_Exception( 'Duplicate menu element: ' . $id );
		}

		$this->items[$id] = $menu;
	}

	/**
	 * @return bool
	 */
	public function getAccessAllowed(): bool
	{
		return (count( $this->getItems() ) > 0);
	}

	/**
	 * @param bool $check_access
	 *
	 * @return Navigation_Menu[]|Navigation_Menu_Item[]
	 */
	public function getItems( bool $check_access = true ): array
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

		static::sortMenuItems( $items );

		return $items;
	}

	/**
	 * @param Navigation_Menu_Item[]|Navigation_Menu[] $items
	 */
	public function setItems( array $items ): void
	{
		$this->items = [];

		foreach( $items as $item ) {
			if( $item instanceof Navigation_Menu_Item ) {
				$this->addItem( $item );
			}

			if( $item instanceof Navigation_Menu ) {
				$this->addMenu( $item );
			}
		}
	}


	/**
	 * @param Navigation_Menu[]|Navigation_Menu_Item[] $items
	 */
	public static function sortMenuItems( array &$items ): void
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

				if( $a->getIndex() == $b->getIndex() ) {
					return 0;
				}

				return ($a->getIndex() < $b->getIndex()) ? -1 : 1;
			}
		);

	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{

		$menu = [
			'label' => $this->getLabel(),
			'icon'  => $this->getIcon(),
			'index' => $this->getIndex()
		];

		if( $this->items ) {
			$menu['items'] = [];

			foreach( $this->items as $item ) {
				$item_id = $item->getId();

				$menu_item = $item->toArray();


				$menu['items'][$item_id] = $menu_item;

			}
		}

		return $menu;

	}

}