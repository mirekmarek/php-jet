<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;
use Jet\Application_Service_MetaInfo;

#[Application_Service_MetaInfo(
	group: Application_Service_Web::GROUP,
	is_mandatory: false,
	name: 'Cookie Consent',
	description: '',
	module_name_prefix: 'Web.'
)]
abstract class Application_Service_Web_CookieConsent extends Application_Module
{
	
	
	protected ?array $groups = null;
	
	/**
	 * @return Web_CookieConsent_Group[]
	 */
	abstract protected function initGroups() : array;
	
	/**
	 * @return Web_CookieConsent_Group[]
	 */
	public function getGroups() : array
	{
		if($this->groups===null) {
			$this->groups = $this->initGroups();
		}
		
		return $this->groups;
	}
	
	public function groupAllowed( string $group_code ) : bool
	{
		$groups = $this->getGroups();
		if(!isset( $groups[$group_code])) {
			return false;
		}
		
		return $groups[$group_code]->getEnabled();
	}
	
	
	abstract public function groupEnabled( string $group_code ) : bool;
	
	
	public function enableGroup( string $group_code ) : void
	{
		$enabled = $this->getEnabledGroups();
		if(in_array($group_code, $enabled)) {
			return;
		}
		
		$enabled[] = $group_code;
		$this->setEnabledGroups($enabled);
	}
	
	public function disableGroup( string $group_code ) : void
	{
		$enabled = $this->getEnabledGroups();
		if(!in_array($group_code, $enabled)) {
			return;
		}
		
		$_enabled = [];
		
		foreach($enabled as $_g_id) {
			if($_g_id!=$group_code) {
				$_enabled[] = $_g_id;
			}
		}
		
		$this->setEnabledGroups($_enabled);
	}
	
	
	abstract public function resetConsent() : void;
	
	
	/**
	 * @return Web_CookieConsent_Group[]
	 */
	abstract protected function getEnabledGroups() : array;
	
	abstract public function consentRequired() : bool;
	
	
	public function denyAll() : void
	{
		$this->setEnabledGroups([]);
	}
	
	public function allowAll() : void
	{
		$this->setEnabledGroups(array_keys($this->getGroups()));
	}
	
	
	public function enableCustom( array $group_codes ) : void
	{
		$this->setEnabledGroups($group_codes);
	}
	
	abstract protected function logAgree( array $enabled_groups, bool $complete_consent ) : void;
	
	abstract protected function logDisagree() : void;
	
	abstract public function renderDialog() : string;
}