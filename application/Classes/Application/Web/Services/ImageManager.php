<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

interface Application_Web_Services_ImageManager
{
	public function generateThbURI( string $image, int $max_w, int $max_h ) : string;
}