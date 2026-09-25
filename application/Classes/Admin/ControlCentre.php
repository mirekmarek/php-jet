<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;


use Jet\Application_Module;
use Jet\Application_Service_List;
use Jet\Data_Text;
use Jet\Tr;

class Admin_ControlCentre
{
	public const GROUP_MAIN = 'main';
	public const GROUP_SYSTEM = 'system';
	public const GROUP_EXPORTS = 'exports';
	public const GROUP_ANALYTICS = 'analytics';
	public const GROUP_PAYMENT = 'payment';
	
	protected static ?array $module_list = null;
	
	public static function getGroupsList(): array
	{
		return [
			Admin_ControlCentre::GROUP_MAIN      => Tr::_( 'Main' ),
			Admin_ControlCentre::GROUP_EXPORTS   => Tr::_( 'Exports' ),
			Admin_ControlCentre::GROUP_ANALYTICS => Tr::_( 'Analytics' ),
			Admin_ControlCentre::GROUP_PAYMENT   => Tr::_( 'Payment' ),
			Admin_ControlCentre::GROUP_SYSTEM    => Tr::_( 'System' ),
		];
	}
	
	/**
	 * @return Admin_ControlCentre_Module_Interface[]|Application_Module[]
	 */
	public static function getModuleList() : array
	{
		if( static::$module_list===null ) {
			static::$module_list = [];
			
			foreach( Application_Service_List::findPossibleModules(Admin_ControlCentre_Module_Interface::class) as $module_name=> $module ) {
				static::$module_list[ $module_name ] = $module;
			}
			
			uasort( static::$module_list, function( Admin_ControlCentre_Module_Interface $a, Admin_ControlCentre_Module_Interface $b ) {
				return strcmp(
					Data_Text::removeAccents($a->getControlCentreTitleTranslated()),
					Data_Text::removeAccents($b->getControlCentreTitleTranslated())
				);
			} );
			
			uasort( static::$module_list, function( Admin_ControlCentre_Module_Interface $a, Admin_ControlCentre_Module_Interface $b ) {
				return $a->getControlCentrePriority() <=> $b->getControlCentrePriority();
			} );
			
		}
		
		
		return static::$module_list;
	}
}