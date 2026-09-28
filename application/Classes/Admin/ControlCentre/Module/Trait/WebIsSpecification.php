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
use Jet\MVC_Page_Interface;
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
					$list[$base->getId().'_'.$locale] = $base->getName().' - '.UI::flag( $locale );
				}
			}
		}
		
		return $list;
	}
	
	public function getSpecificationIdByPage( MVC_Page_Interface $page ) : string
	{
		return $page->getBaseId().'_'.$page->getLocale();
	}
	
}