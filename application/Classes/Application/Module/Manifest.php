<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application_Module_Manifest as Jet_Application_Module_Manifest;

/**
 *
 */
class Application_Module_Manifest extends Jet_Application_Module_Manifest
{

	/**
	 * @var array
	 */
	protected $admin_sections = [];

	/**
	 * @var array
	 */
	protected $admin_dialogs = [];

	/**
	 * @var array
	 */
	protected $admin_menu_items = [];

	/**
	 * @var array
	 */
	protected $has_rest_api = false;


	/**
	 * @return array
	 *
	 */
	public function getAdminSections()
	{
		return $this->admin_sections;
	}

	/**
	 * @return array
	 *
	 */
	public function getAdminDialogs()
	{
		return $this->admin_dialogs;
	}

	/**
	 * @return array
	 */
	public function getMenuItems()
	{
		return $this->admin_menu_items;
	}

	/**
	 * @return array
	 */
	public function hasRestAPI()
	{
		return $this->has_rest_api;
	}


}