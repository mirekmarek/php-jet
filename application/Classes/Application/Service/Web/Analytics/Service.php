<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;
use Jet\Application_Module_Manifest;
use Jet\Factory_MVC;
use Jet\MVC_Page_Interface;
use Jet\MVC_View;
use Jet\Application_Service_MetaInfo;

#[Application_Service_MetaInfo(
	group: Application_Service_Web::GROUP,
	is_mandatory: false,
	multiple_mode: true,
	name: 'Analytics - service',
	description: '',
	module_name_prefix: '',
)]
abstract class Application_Service_Web_Analytics_Service extends Application_Module
{
	
	protected bool $enabled = false;
	protected bool $testing_allowed = true;
	
	protected MVC_Page_Interface $page;
	protected string $currency_code;
	protected MVC_View $view;
	
	public function __construct( Application_Module_Manifest $manifest )
	{
		parent::__construct( $manifest );
		$this->view = Factory_MVC::getViewInstance( $this->getViewsDir() );
	}
	
	public function init( MVC_Page_Interface $page ) : void
	{
		$this->page = $page;
	}
	
	public function initTest( MVC_Page_Interface $page ) : void
	{
		$this->init( $page );
	}
	
	
	public function getEnabled(): bool
	{
		return $this->enabled;
	}
	
	public function getTestingAllowed(): bool
	{
		return $this->testing_allowed;
	}
	
	abstract public function allowed(): bool;
	
	public function canPerform() : bool
	{
		return $this->getEnabled() && $this->allowed();
	}
	
	abstract public function header() : string;
	
	abstract public function documentStart() : string;
	
	abstract public function documentEnd() : string;
	
	abstract public function viewHomePage() : string;
	
	/**
	 * @param string $event
	 * @param array<string,mixed> $event_data
	 * @return string
	 */
	abstract public function customEvent( string $event, array $event_data=[] ) : string;
	
	
}