<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Auth_Controller_Interface;

use Jet\Application_Service_MetaInfo;

#[Application_Service_MetaInfo(
	group: Application_Service_Admin::GROUP,
	is_mandatory: true,
	name:  'Authentication and authorization controller',
	description: ''
)]
interface Application_Service_Admin_Auth_Controller extends Auth_Controller_Interface
{
}