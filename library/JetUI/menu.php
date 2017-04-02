<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetUI;
use Jet\BaseObject;


class menu extends BaseObject
{
    /**
     * @var menu[]|array
     */
    protected static $menus = [];

    /**
     * @var menu_item[]
     */
    protected static $all_menu_items;

    /**
     * @var string
     */
    protected $id = '';

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
     * @var menu_item[]
     */
    protected $items = [];

    /**
     * @param string $id
     *
     * @param $label
     * @param int|null $index
     * @param string $icon
     *
     * @throws menu_Exception
     *
     * @return menu
     */
    public static function addMenu( $id, $label, $index=null, $icon='' ) {
        if(isset(static::$menus[$id])) {
            throw new menu_Exception('Menu ID conflict: '.$id);
        }

        if($index===null) {
            $index = count(static::$menus[$id])+1;
        }

        $menu = new static( $id, $label, $index, $icon );

        static::$menus[$id] = $menu;

        return $menu;
    }

    /**
     * @param $id
     * @return menu|null
     */
    public static function getMenu( $id ) {
        if(!isset(static::$menus[$id])) {
            return null;
        }

        return static::$menus[$id];
    }

    /**
     * @return menu[]
     *
     * @throws menu_Exception
     */
    public static function getMenus()
    {
        $menus = [];

        foreach( static::$menus as $menu_id=>$menu ) {

            if(!count($menu->getItems())) {
                continue;
            }

            $menu->sortMenuItems();

            $menus[$menu->getId()] = $menu;
        }

        uasort( $menus, function( menu $a, menu $b ) {
            return strcmp( $a->getLabel(), $b->getLabel() );
        } ) ;

        uasort( $menus, function( menu $a, menu $b ) {

            if ($a->getIndex() == $b->getIndex()) {
                return 0;
            }
            return ($a->getIndex() < $b->getIndex()) ? -1 : 1;
        } ) ;

        return $menus;
    }


    /**
     * menu constructor.
     *
     * @param string $id
     * @param string $label
     * @param int $index
     * @param string $icon
     *
     */
    public function __construct(  $id, $label, $index, $icon='' )
    {

        $this->id = $id;
        $this->label = $label;

        $this->index = $index;
        $this->icon = $icon;

    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
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
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
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
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return menu_item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param menu_item $item
     */
    public function addMenuItem(menu_item $item)
    {
	    /**
	     * @var menu $this
	     */
        $item->setMenu($this);
        $this->items[$item->getId()] = $item;
    }

    /**
     * @param menu_item[] $items
     */
    public function setItems($items)
    {
        $this->items = [];

        foreach( $items as $item ) {
            $this->addMenuItem($item);
        }
    }

    /**
     *
     */
    public function sortMenuItems()
    {
        uasort( $this->items, function( menu_item $a, menu_item $b ) {
            return strcmp( $a->getLabel(), $b->getLabel() );
        } ) ;

        uasort( $this->items, function( menu_item $a, menu_item $b ) {

            if ($a->getIndex() == $b->getIndex()) {
                return 0;
            }
            return ($a->getIndex() < $b->getIndex()) ? -1 : 1;
        } ) ;

    }



}