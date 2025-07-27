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
	group: Application_Admin_Services::GROUP,
	is_mandatory: true,
	name:  'Login UI',
	description: ''
)]
abstract class Application_Admin_Services_Auth_LoginModule extends Application_Module
{
	abstract public function handleLogin( Application_Admin_Services_Auth_Controller $auth_controller ): void;
}