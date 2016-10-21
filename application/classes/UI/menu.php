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
use Jet\Application_Modules;
use JetShop\Admin\Custom\Module_Manifest;
use Jet\BaseObject;
use Jet\Tr;


class UI_menu extends BaseObject
{
    /**
     * @var array
     */
    protected static $menus = [
        'catalog' => [
            'label' => 'Product catalog',
            'index' => 1,
        ],
        'orders' => [
            'label' => 'Orders',
            'index' => 2,
        ],
        'system' => [
            'label' => 'System',
            'index' => 3,
        ],
    ];

    /**
     * @var UI_menu_item[]
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
     * @var UI_menu_item[]
     */
    protected $items = [];

    /**
     * @param string $id
     * @param array $data
     *
     * @throws UI_menu_Exception
     */
    public static function addMenu( $id, array $data ) {
        if(isset(static::$menus[$id])) {
            throw new UI_menu_Exception('Menu ID conflict: '.$id);
        }
        static::$menus[$id] = $data;
    }

    /**
     * @return UI_menu_item[]
     * @throws UI_menu_Exception
     */
    public static function getAllMenuItems() {
        if(static::$all_menu_items===null) {
            static::$all_menu_items = [];

            foreach( Application_Modules::getActivatedModulesList() as $manifest ) {
                /**
                 * @var Module_Manifest $manifest
                 */
                foreach( $manifest->getMenuItems() as $menu_item ) {

                    if(isset(static::$all_menu_items[$menu_item->getId()])) {
                        throw new UI_menu_Exception('Menu item ID conflict: '.$menu_item->getId());
                    }
                    static::$all_menu_items[$menu_item->getId()] = $menu_item;
                }

            }
        }

        return static::$all_menu_items;
    }

    /**
     * @return UI_menu[]
     *
     * @throws UI_menu_Exception
     */
    public static function getMenus()
    {
        $menus = [];

        foreach( static::$menus as $menu_id=>$data ) {
            $data['id'] = $menu_id;

            $menu = new UI_menu($data);

            if(isset($menus[$menu->getId()])) {
                throw new UI_menu_Exception('Menu ID conflict: '.$menu->getId());
            }

            foreach( static::getAllMenuItems() as $menu_item ) {
                if($menu_item->getParentMenuId()!=$menu->getId()) {
                    continue;
                }

                $menu->addMenuItem($menu_item);
            }

            if(!count($menu->getItems())) {
                continue;
            }

            $menu->sortMenuItems();

            $menus[$menu->getId()] = $menu;
        }

        uasort( $menus, function( UI_menu $a, UI_menu $b ) {
            return strcmp( $a->getLabel(), $b->getLabel() );
        } ) ;

        uasort( $menus, function( UI_menu $a, UI_menu $b ) {

            if ($a->getIndex() == $b->getIndex()) {
                return 0;
            }
            return ($a->getIndex() < $b->getIndex()) ? -1 : 1;
        } ) ;

        return $menus;
    }


    /**
     *
     * @param array $data
     *
     * @throws UI_menu_Exception
     */
    public function __construct( array $data)
    {

        if(!isset($data['id'])) {
            throw  new UI_menu_Exception('Inconsistent menu data. ID is missing.');
        }
        if(!isset($data['label'])) {
            throw  new UI_menu_Exception('Inconsistent menu data. label is missing.');
        }

        $this->id = $data['id'];
        $this->label = Tr::_( $data['label'], [], Tr::COMMON_NAMESPACE );

        $optional = [
            'icon',
            'index',
        ];

        foreach( $optional as $k ) {
            if(isset($data[$k])) {
                $this->{$k} = $data[$k];
            }
        }
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
     * @return UI_menu_item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param UI_menu_item $item
     */
    public function addMenuItem(UI_menu_item $item)
    {
	    /**
	     * @var UI_menu $this
	     */
        $item->setMenu($this);
        $this->items[$item->getId()] = $item;
    }

    /**
     * @param UI_menu_item[] $items
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
        uasort( $this->items, function( UI_menu_item $a, UI_menu_item $b ) {
            return strcmp( $a->getLabel(), $b->getLabel() );
        } ) ;

        uasort( $this->items, function( UI_menu_item $a, UI_menu_item $b ) {

            if ($a->getIndex() == $b->getIndex()) {
                return 0;
            }
            return ($a->getIndex() < $b->getIndex()) ? -1 : 1;
        } ) ;

    }



}