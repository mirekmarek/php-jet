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
class Application_Module_Manifest_AdminSection extends BaseObject
{
	/**
	 * @var string
	 */
	protected $page_id = '';

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var string
	 */
	protected $relative_path_fragment = '';

	/**
	 * @var string
	 */
	protected $menu_title = '';

	/**
	 * @var string
	 */
	protected $breadcrumb_title = '';

	/**
	 * @var string
	 */
	protected $layout_script_name = '';

	/**
	 * @var string
	 */
	protected $icon = '';

	/**
	 * @var string
	 */
	protected $action = '';


	/**
	 * @param string $page_id
	 * @param array $data
	 *
	 * @return Application_Module_Manifest_AdminSection
	 */
	public static function create( $page_id, array $data )
	{
		$i = Application_Factory::getModuleManifestAdminSectionInstance();

		$i->page_id = $page_id;

		foreach( $data as $k=>$v ) {
			$i->{$k} = $v;
		}

		return $i;
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
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getRelativePathFragment()
	{
		return $this->relative_path_fragment;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}



	/**
	 * @return string
	 */
	public function getMenuTitle()
	{
		return $this->menu_title;
	}

	/**
	 * @return string
	 */
	public function getBreadcrumbTitle()
	{
		return $this->breadcrumb_title;
	}

	/**
	 * @return string
	 */
	public function getLayoutScriptName()
	{
		return $this->layout_script_name;
	}

	/**
	 * @param string $layout_script_name
	 */
	public function setLayoutScriptName( $layout_script_name )
	{
		$this->layout_script_name = $layout_script_name;
	}

	/**
	 * @return string
	 */
	public function getAction()
	{
		if(!$this->action) {
			$this->action = 'default';
		}

		return $this->action;
	}



	/**
	 * @return array
	 */
	public function asArray()
	{
		return get_object_vars( $this );
	}

}