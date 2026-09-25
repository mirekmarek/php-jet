<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Service_MetaInfo;
use Jet\Application_Module;

#[Application_Service_MetaInfo(
	group: Application_Service_Web::GROUP,
	is_mandatory: false,
	name: 'Analytics - manager',
	description: '',
	module_name_prefix: 'Web.'
)]
abstract class Application_Service_Web_Analytics_Manager extends Application_Module
{
	
	/**
	 * @return Application_Service_Web_Analytics_Service[]
	 */
	abstract public function getServices() : array;
	
	abstract public function header() : string;
	
	abstract public function documentStart() : string;
	
	abstract public function documentEnd() : string;
	
	abstract public function viewHomePage() : string;
	
	abstract public function customEvent( string $event, array $event_data=[] ) : string;
}