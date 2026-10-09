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
use Jet\MVC_Base_LocalizedData_Interface;

#[Application_Service_MetaInfo(
	group: Application_Service_General::GROUP,
	is_mandatory: false,
	name:  'OAuth services manager',
	description: ''
)]
abstract class OAuth_Manager extends Application_Module
{
	abstract public function init( string $cfg_specification_id, OAuth_UserHandler $auth_user_handler ) : void;
	
	/**
	 * @return OAuth_BackendModule[]
	 */
	abstract public function getOAuthModules( bool $only_ready=false ): array;
	
	abstract public function generateHandlerURL( OAuth_BackendModule $module, ?MVC_Base_LocalizedData_Interface $base=null ) : string;
	
	abstract public function renderLoginButton( OAuth_BackendModule $module ) : string;
	
	abstract public function handle(): void;
	
}