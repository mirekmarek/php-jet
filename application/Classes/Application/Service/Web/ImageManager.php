<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Service_MetaInfo;

#[Application_Service_MetaInfo(
	group: Application_Service_Web::GROUP,
	is_mandatory: false,
	name:  'Image gallery manager',
	description: ''
)]
interface Application_Service_Web_ImageManager
{
	public function generateThbURI( string $image, int $max_w, int $max_h ) : string;
}