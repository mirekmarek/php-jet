<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Navigation_Menu_Item extends BaseObject
{


	/**
	 * @var Navigation_Menu
	 */
	protected $menu;

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
	 * @var string
	 */
	protected $site_id = '';

	/**
	 * @var string
	 */
	protected $locale = '';

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
	 * @param string $id
	 * @param string $label
	 */
	public function __construct( $id, $label )
	{
		$this->id = $id;
		$this->label = $label;
	}

	/**
	 * @param array $data
	 *
	 * @throws Navigation_Menu_Exception
	 */
	public function setData( array $data )
	{
		foreach( $data as $key=>$val ) {
			if($key=='id' || $key=='label') {
				continue;
			}

			if(!property_exists($this, $key)) {
				throw new Navigation_Menu_Exception( 'Unknown menu property: '.$key);
			}

			$setter = $this->getSetterMethodName($key);

			$this->$setter($val);
		}

	}

	/**
	 * @return Navigation_Menu
	 */
	public function getMenu()
	{
		return $this->menu;
	}

	/**
	 * @param Navigation_Menu $menu
	 */
	public function setMenu( Navigation_Menu $menu )
	{
		$this->menu = $menu;
	}


	/**
	 * @param bool $absolute (optional)
	 *
	 * @return string
	 */
	public function getId( $absolute=true )
	{
		if($absolute) {
			return $this->getMenu()->getId().'/'.$this->id;
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
	 * @return string
	 */
	public function getSiteId()
	{
		return $this->site_id;
	}

	/**
	 * @param string $site_id
	 */
	public function setSiteId( $site_id )
	{
		$this->site_id = $site_id;
	}

	/**
	 * @return string
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @param string $locale
	 */
	public function setLocale( $locale )
	{
		$this->locale = $locale;
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

		/**
		 * @var Mvc_Page_Interface $page_class
		 */
		$page_class = JET_MVC_PAGE_CLASS;

		$page = $page_class::get( $this->page_id, $this->locale, $this->site_id );

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

		$page = Mvc_Page::get( $this->page_id );

		if( !$page ) {
			return false;
		}

		return $page->getAccessAllowed();
	}

}