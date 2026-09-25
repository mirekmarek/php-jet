<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Web\CookieConsent;


use Jet\AJAX;
use Jet\Factory_MVC;
use Jet\Http_Request;
use Jet\Tr;
use JetApplication\Application_Service_Web_CookieConsent;
use JetApplication\Web_CookieConsent_Group;


class Main extends Application_Service_Web_CookieConsent
{
	public static function getCookieName() : string
	{
		return 'ja_cookie_consent';
	}
	
	/**
	 * @return Web_CookieConsent_Group[]
	 */
	protected function initGroups() :array {
		
		return Tr::setCurrentDictionaryTemporary(
			dictionary: $this->module_manifest->getName(),
			action: function() {
				$groups = [];
				
				$stats = new Web_CookieConsent_Group();
				$stats->setCode( Web_CookieConsent_Group::STATS );
				$stats->setTitle( Tr::_( 'Stats' ) );
				$stats->setDescription( Tr::_( '' ) );
				$stats->setChecked( false );
				$groups[$stats->getCode()] = $stats;
				
				$marketing = new Web_CookieConsent_Group();
				$marketing->setCode( Web_CookieConsent_Group::MARKETING );
				$marketing->setTitle( Tr::_( 'Marketing' ) );
				$marketing->setDescription( Tr::_( '' ) );
				$marketing->setChecked( false );
				$groups[$marketing->getCode()] = $marketing;
				
				$mesurement = new Web_CookieConsent_Group();
				$mesurement->setCode( Web_CookieConsent_Group::MESUREMENT );
				$mesurement->setTitle( Tr::_( 'Mesurement' ) );
				$mesurement->setDescription( Tr::_( '' ) );
				$mesurement->setChecked( false );
				$groups[$mesurement->getCode()] = $mesurement;
				
				return $groups;
				
			}
		);
	}
	
	
	public function groupEnabled( string $group_code ) : bool
	{
		if(empty($_COOKIE[static::getCookieName()])) {
			return false;
		}
		
		return in_array($group_code, $this->getEnabledGroups());
	}
	
	/**
	 * @return Web_CookieConsent_Group[]
	 */
	protected function getEnabledGroups() : array
	{
		$cookie_data = $this->readCookieData();
		if(!$cookie_data) {
			return [];
		}
		
		return $cookie_data->getEnabledGroups();
	}
	
	
	public function consentRequired() : bool
	{
		$cookie_data = $this->readCookieData();
		if(
			!$cookie_data ||
			!$cookie_data->isValid()
		) {
			return true;
		}
		
		return false;
	}
	
	
	protected function setEnabledGroups( array $group_codes ) : void
	{
		$cookie_data = $this->readCookieData();
		if(!$cookie_data) {
			$cookie_data = new Consent();
			$cookie_data->initNew();
		}
		
		$cookie_data->enableGroups( $group_codes );
		
		if($cookie_data->isAgree()) {
			$this->logAgree( $cookie_data->getEnabledGroups(), $cookie_data->isCompleteAgree() );
		} else {
			$this->logDisagree();
		}
		
		
		$this->writeCookiesData( $cookie_data );
	}
	
	public function resetConsent() : void
	{
		$cookie_name = static::getCookieName();
		if(isset($_COOKIE[$cookie_name])) {
			unset($_COOKIE[$cookie_name]);
			setcookie($cookie_name, '', -1);
		}
	}
	
	protected function readCookieData( bool $create_default=false ) : ?Consent
	{
		if(!empty($_COOKIE[static::getCookieName()])) {
			$cookie_data = new Consent( $_COOKIE[static::getCookieName()] );
			
			return $cookie_data;
		}
		
		return null;
	}
	
	protected function writeCookiesData( Consent $cookie_data) : void
	{
		
		$cookie_data = $cookie_data->toString();
		$cookie_name = static::getCookieName();
		
		setcookie(
			$cookie_name,
			$cookie_data,
			strtotime('+10 years')
		);
		
		$_COOKIE[$cookie_name] = $cookie_data;
		
	}
	
	public function renderDialog() : string
	{
		if(!$this->consentRequired()) {
			return '';
		}
		
		return Tr::setCurrentDictionaryTemporary( $this->module_manifest->getName(), function() {
			$GET = Http_Request::GET();
			if(($action=$GET->getString('cookie_consent'))) {
				switch($action) {
					case 'accept_all':
						$this->allowAll();
						break;
					case 'reject_all':
						$this->denyAll();
						break;
					case 'custom':
						$groups = explode(',', $GET->getString('groups'));
						$this->enableCustom( $groups );
						break;
					case 'reset':
						$this->resetConsent();
						break;
				}
				
				AJAX::snippetResponse('');
			}
			
			$view = Factory_MVC::getViewInstance( $this->getViewsDir() );
			
			return $view->render('dialog');
		} );
		
	}
	
	protected function logAgree( array $enabled_groups, bool $complete_consent ) : void
	{
		Evidence_Agree::performEvidence( $enabled_groups, $complete_consent );
	}
	
	protected function logDisagree() : void
	{
		Evidence_Disagree::performEvidence();
	}
	
	
}