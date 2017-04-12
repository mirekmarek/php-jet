<?php
/**
 *
 *
 *
 * Class that contains basic information about the module
 *
 * @see Application_Modules_Module_Abstract
 *
 * Each module has manifest file (~/application/modules/Module/manifest.php), that contains these specifications:
 *  - label (required)
 *  - API_version (required)
 *  - type (required)
 *  - description (optional)
 *  - require (optional)
 *  - signals_callbacks (optional)
 *
 * See class variables description for more details
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Modules
 */
namespace JetExampleApp;

use Jet\Application_Modules_Module_Manifest as Jet_Application_Modules_Module_Manifest;

use JetUI\menu_item;


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
	 * @return array[]
	 *
	 */
	public function getAdminSections()
	{
		return $this->admin_sections;
	}

	/**
	 * @return array[]
	 *
	 */
	public function getAdminDialogs()
	{
		return $this->admin_dialogs;
	}

	/**
	 * @return menu_item[]
	 */
	public function getMenuItems()
	{
		return $this->admin_menu_items;
	}

}