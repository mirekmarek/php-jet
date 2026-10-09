<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\ControlCentreModule\Services\General;

use JetApplication\Admin_ControlCentre_ServicesHandler;
use JetApplication\Application_Service_General;

class Handler extends Admin_ControlCentre_ServicesHandler
{
	public function __construct()
	{
		$this->list = Application_Service_General::list();
		$this->handleControlCentre();
	}
}