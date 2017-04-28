<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\Application_Modules_Module_Manifest as Jet_Application_Modules_Module_Manifest;

/**
 *
 */
class Application_Modules_Module_Manifest extends Jet_Application_Modules_Module_Manifest
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
	protected $rest_api_hooks = [];


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
	public function getRestApiHooks()
	{
		return $this->rest_api_hooks;
	}



}