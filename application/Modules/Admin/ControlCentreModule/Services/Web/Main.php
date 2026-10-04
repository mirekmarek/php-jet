<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\ControlCentreModule\Services\Web;


use Jet\Application_Module;
use JetApplication\Admin_ControlCentre;
use JetApplication\Admin_ControlCentre_Module_Interface_WebIsSpecification;
use JetApplication\Admin_ControlCentre_Module_Trait_WebIsSpecification;


class Main extends Application_Module implements Admin_ControlCentre_Module_Interface_WebIsSpecification
{
	use Admin_ControlCentre_Module_Trait_WebIsSpecification;

	protected string$control_centre_group = Admin_ControlCentre::GROUP_SYSTEM;
	protected string $control_centre_title = 'Services - Web';
	protected string $control_centre_icon = 'gears';
	protected int $control_centre_priority = 99;
	protected bool $control_cnter_specification_mode = true;
}