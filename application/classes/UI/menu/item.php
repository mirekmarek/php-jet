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
use JetShop\Admin\Custom\Module_Manifest;
use JetShop\Admin\Custom\Page;
use Jet\BaseObject;
use Jet\Tr;

class UI_menu_item extends BaseObject{

    /**
     * @var Module_Manifest
     */
    protected $module_manifest;

    /**
     * @var UI_menu
     */
    protected $menu;

    /**
     * @var string
     */
    protected $id = '';

    /**
     * @var string
     */
    protected $parent_menu_id = '';
    
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
     * @var bool
     */
    protected $separator_before = false;

    /**
     * @var bool
     */
    protected $separator_after = false;

    /**
     * @var string
     */
    protected $page_id = '';

    /**
     * @var array
     */
    protected $url_parts = [];

    /**
     * @var array
     */
    protected $get_params = [];

    /**
     *
     * @param Module_Manifest $module_manifest
     * @param array $data
     *
     * @throws UI_menu_Exception
     */
    public function __construct(Module_Manifest $module_manifest, array $data)
    {
        $this->module_manifest = $module_manifest;

        if(!isset($data['id'])) {
            throw  new UI_menu_Exception('Inconsistent menu data. ID is missing. Module:'.$module_manifest->getName());
        }
        if(!isset($data['parent_menu_id'])) {
            throw  new UI_menu_Exception('Inconsistent menu data. parent_menu_id is missing. Module:'.$module_manifest->getName());
        }
        if(!isset($data['label'])) {
            throw  new UI_menu_Exception('Inconsistent menu data. label is missing. Module:'.$module_manifest->getName());
        }

        $this->parent_menu_id = $data['parent_menu_id'];
        $this->id = $this->parent_menu_id.'/'.$data['id'];
        $this->label = Tr::_( $data['label'], [], $module_manifest->getName() );
        
        $optional = [
            'icon',
            'index',
            'separator_before',
            'separator_after',
            'page_id',
            'url_parts',
            'get_params',
        ];

        foreach( $optional as $k ) {
            if(isset($data[$k])) {
                $this->{$k} = $data[$k];
            }
        }
    }

    /**
     * @return Module_Manifest
     */
    public function getModuleManifest()
    {
        return $this->module_manifest;
    }

    /**
     * @return UI_menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param UI_menu $menu
     */
    public function setMenu(UI_menu $menu)
    {
        $this->menu = $menu;
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
    public function getParentMenuId()
    {
        return $this->parent_menu_id;
    }

    /**
     * @param string $parent_menu_id
     */
    public function setParentMenuId($parent_menu_id)
    {
        $this->parent_menu_id = $parent_menu_id;
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
    public function setLabel($label)
    {
        $this->label = $label;
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
     * @return boolean
     */
    public function isSeparatorBefore()
    {
        return $this->separator_before;
    }

    /**
     * @param boolean $separator_before
     */
    public function setSeparatorBefore($separator_before)
    {
        $this->separator_before = $separator_before;
    }

    /**
     * @return boolean
     */
    public function isSeparatorAfter()
    {
        return $this->separator_after;
    }

    /**
     * @param boolean $separator_after
     */
    public function setSeparatorAfter($separator_after)
    {
        $this->separator_after = $separator_after;
    }

    /**
     * @return string
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @param string $page_id
     */
    public function setPageId($page_id)
    {
        $this->page_id = $page_id;
    }

    /**
     * @return array
     */
    public function getUrlParts()
    {
        return $this->url_parts;
    }

    /**
     * @param array $url_parts
     */
    public function setUrlParts($url_parts)
    {
        $this->url_parts = $url_parts;
    }

    /**
     * @return array
     */
    public function getGetParams()
    {
        return $this->get_params;
    }

    /**
     * @param array $get_params
     */
    public function setGetParams($get_params)
    {
        $this->get_params = $get_params;
    }

    /**
     *
     */
    public function getUrl()
    {
        return Page::get($this->page_id)->getURL($this->getGetParams(), $this->url_parts);
    }

	/**
	 * @return bool
	 */
    public function getAccessAllowed()
    {
		$page = Page::get($this->page_id);

	    if(!$page) {
	    	return false;
	    }

	    return $page->getAccessAllowed();
    }

}