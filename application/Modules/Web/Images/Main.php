<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Web\Images;

use Exception;
use Jet\Application_Module;
use JetApplication\Application_Web_Services_ImageManager;
use JetApplication\Content_Gallery_Image;

/**
 *
 */
class Main extends Application_Module implements Application_Web_Services_ImageManager
{
	public function generateThbURI( string $image, int $max_w, int $max_h ): string
	{
		$image = Content_Gallery_Image::get( $image );
		if(!$image) {
			return '';
		}
		
		try {
			$thb = $image->getThumbnail($max_w, $max_h);
		} catch( Exception $e ) {
			return '';
		}
		
		return $thb->getURI();
	}
}