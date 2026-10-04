<?php
/**
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license EUPL 1.2  https://eupl.eu/1.2/en/
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\ControlCentreModule\Services\Admin;


use Jet\Application_Module;
use JetApplication\Admin_ControlCentre;
use JetApplication\Admin_ControlCentre_Module_Interface;
use JetApplication\Admin_ControlCentre_Module_Trait;


class Main extends Application_Module implements Admin_ControlCentre_Module_Interface
{
	use Admin_ControlCentre_Module_Trait;

	protected string$control_centre_group = Admin_ControlCentre::GROUP_SYSTEM;
	protected string $control_centre_title = 'Services - Admin panel';
	protected string $control_centre_icon = 'gears';
	protected int $control_centre_priority = 99;
	protected bool $control_cnter_specification_mode = false;
}