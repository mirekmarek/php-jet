<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;

use Jet\BaseObject;
use Jet\Mvc_Page;

/**
 * Class menu_item
 * @package JetUI
 */
class menu_item extends BaseObject
{


	/**
	 * @var menu
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
	protected $URL;

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
	 *
	 * @param string $parent_menu_id
	 * @param string $id
	 * @param string $label
	 */
	public function __construct( $parent_menu_id, $id, $label )
	{

		$this->parent_menu_id = $parent_menu_id;
		$this->id = $this->parent_menu_id.'/'.$id;
		$this->label = $label;
	}

	/**
	 * @return menu
	 */
	public function getMenu()
	{
		return $this->menu;
	}

	/**
	 * @param menu $menu
	 */
	public function setMenu( menu $menu )
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
	public function setId( $id )
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
	public function setParentMenuId( $parent_menu_id )
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
	public function setLabel( $label )
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
	public function setIcon( $icon )
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
	public function setIndex( $index )
	{
		$this->index = $index;
	}

	/**
	 * @return bool
	 */
	public function getSeparatorBefore()
	{
		return $this->separator_before;
	}

	/**
	 * @param bool $separator_before
	 */
	public function setSeparatorBefore( $separator_before )
	{
		$this->separator_before = $separator_before;
	}

	/**
	 * @return bool
	 */
	public function getSeparatorAfter()
	{
		return $this->separator_after;
	}

	/**
	 * @param bool $separator_after
	 */
	public function setSeparatorAfter( $separator_after )
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
	public function setPageId( $page_id )
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
	public function setUrlParts( $url_parts )
	{
		$this->url_parts = $url_parts;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		if( $this->URL ) {
			return $this->URL;
		}

		$page_class = JET_MVC_PAGE_CLASS;

		/**
		 * @var Mvc_Page $page
		 */
		/** @noinspection PhpUndefinedMethodInspection */
		$page = $page_class::get( $this->page_id );

		if( !$page ) {
			return '';
		}

		return $page->getURL( $this->getGetParams(), $this->url_parts );
	}

	/**
	 * @param string $URL
	 */
	public function setURL( $URL )
	{
		$this->URL = $URL;
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
	public function setGetParams( $get_params )
	{
		$this->get_params = $get_params;
	}

	/**
	 * @return bool
	 */
	public function getAccessAllowed()
	{
		if( $this->URL ) {
			return true;
		}

		$page_class = JET_MVC_PAGE_CLASS;

		/**
		 * @var Mvc_Page $page
		 */
		/** @noinspection PhpUndefinedMethodInspection */
		$page = $page_class::get( $this->page_id );

		if( !$page ) {
			return false;
		}

		return $page->getAccessAllowed();
	}

}