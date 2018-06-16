<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Application_Module_Manifest_AdminMenuItem extends BaseObject
{

	/**
	 * @var string
	 */
	protected $item_id = '';

	/**
	 * @var string
	 */
	protected $menu_id = '';

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
	 * @param string $item_id
	 * @param array $data
	 *
	 * @return Application_Module_Manifest_AdminMenuItem
	 */
	public static function create( $item_id, array $data )
	{
		$i = Application_Factory::getModuleManifestAdminMenuItemInstance();

		$i->item_id = $item_id;

		foreach( $data as $k=>$v ) {
			$i->{$k} = $v;
		}

		return $i;
	}

	/**
	 * @return string
	 */
	public function getItemId()
	{
		return $this->item_id;
	}

	/**
	 * @return string
	 */
	public function getMenuId()
	{
		return $this->menu_id;
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
	public function getPageId()
	{
		return $this->page_id;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @return int
	 */
	public function getIndex()
	{
		return $this->index;
	}

	/**
	 * @return bool
	 */
	public function getSeparatorBefore()
	{
		return $this->separator_before;
	}

	/**
	 * @return bool
	 */
	public function getSeparatorAfter()
	{
		return $this->separator_after;
	}

	/**
	 * @return string
	 */
	public function getURL()
	{
		return $this->URL;
	}

	/**
	 * @return string
	 */
	public function getSiteId()
	{
		return $this->site_id;
	}

	/**
	 * @return string
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @return array
	 */
	public function getUrlParts()
	{
		return $this->url_parts;
	}

	/**
	 * @return array
	 */
	public function getGetParams()
	{
		return $this->get_params;
	}


	/**
	 * @return array
	 */
	public function asArray()
	{
		return get_object_vars( $this );
	}

}