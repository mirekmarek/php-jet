<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Locale;
use Jet\MVC;
use Jet\MVC_Base_LocalizedData_Interface;
use Jet\UI;

trait Admin_ControlCentre_Module_Trait_WebIsSpecification
{
	use Admin_ControlCentre_Module_Trait;
	
	/**
	 * @return array<string,string>
	 */
	public function getControlCentreSpecificationList() : array
	{
		$list = [];
		
		foreach(MVC::getBases() as $base) {
			if(!$base->getIsSecret()) {
				foreach($base->getLocales() as $locale) {
					/** @var Locale $locale */
					$spoecification_id = $this->getSpecificationIdByBase( $base->getLocalizedData( $locale ) );
					
					$list[$spoecification_id] = $base->getName().' - '.UI::flag( $locale );
				}
			}
		}
		
		return $list;
	}
	
	public function getSpecificationIdByBase( MVC_Base_LocalizedData_Interface $base ) : string
	{
		return $base->getBase()->getId().':'.$base->getLocale();
	}
	
	public function getBaseBySpecificationId( string $specification_id ) : ?MVC_Base_LocalizedData_Interface
	{
		$specification_id = explode(':', $specification_id);
		[$base_id, $locale] = $specification_id;
		$locale = new Locale($locale);
		
		$base = MVC::getBase( $base_id );
		
		return $base?->getLocalizedData($locale)??null;
	}
	
	
}