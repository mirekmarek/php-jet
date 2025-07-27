<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Logger_Interface;
use Jet\Application_Service_MetaInfo;

#[Application_Service_MetaInfo(
	group: Application_Web_Services::GROUP,
	is_mandatory: false,
	name:  'Event logger',
	description: ''
)]
interface Application_Admin_Services_Logger extends Logger_Interface
{
}