<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Images\Browser;

use Jet\Mvc_Controller_Default;
use Jet\Mvc;
use Jet\Navigation_Breadcrumb;

use JetApplication\Content_Gallery;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{

	/**
	 * @var ?Content_Gallery
	 */
	protected ?Content_Gallery $gallery = null;

	/**
	 *
	 *
	 * @return bool|string
	 */
	public function resolve(): bool|string
	{
		$path = Mvc::getRouter()->getUrlPath();

		if( $path ) {
			$gallery = Content_Gallery::resolveGalleryByURL( $path, Mvc::locale() );
			if( !$gallery ) {
				return false;
			}

			$this->gallery = $gallery;
			Mvc::getRouter()->setUsedUrlPath( $path );
		}


		return true;
	}

	/**
	 *
	 */
	public function default_Action(): void
	{
		$gallery = $this->gallery;

		if( !$gallery ) {
			$this->view->setVar( 'galleries', Content_Gallery::getRootGalleries() );

			$this->output( 'default' );

		} else {

			foreach( $gallery->getPath() as $g ) {
				Navigation_Breadcrumb::addURL( $g->getTitle(), $g->getURL() );
			}

			$this->view->setVar( 'gallery', $gallery );
			$this->output( 'gallery' );
		}
	}


}