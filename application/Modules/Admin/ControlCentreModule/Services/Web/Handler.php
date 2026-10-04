<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\ControlCentreModule\Services\Web;

use Jet\Locale;
use Jet\MVC_Base_Interface;
use JetApplication\Application_Service_Web;
use JetApplication\Admin_ControlCentre_ServicesHandler;

class Handler extends Admin_ControlCentre_ServicesHandler
{
	public function __construct( MVC_Base_Interface $base, Locale $locale )
	{
		$this->list = Application_Service_Web::getList( $base, $locale );
		$this->handleControlCentre();
	}
	
}