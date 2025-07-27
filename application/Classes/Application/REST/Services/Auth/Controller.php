<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Service_MetaInfo;
use Jet\Auth_Controller_Interface;

#[Application_Service_MetaInfo(
	group: Application_REST_Services::GROUP,
	is_mandatory: true,
	name:  'Authentication and authorization controller',
	description: ''
)]
interface Application_REST_Services_Auth_Controller extends Auth_Controller_Interface
{
}