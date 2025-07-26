<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\ImageGallery\Web;

use Jet\MVC_Controller_Default;
use Jet\MVC;
use Jet\Navigation_Breadcrumb;

use JetApplicationModule\Content\ImageGallery\Entity\Gallery;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	/**
	 * @var ?Gallery
	 */
	protected ?Gallery $gallery = null;

	/**
	 *
	 *
	 * @return bool|string
	 */
	public function resolve(): bool|string
	{
		$path = MVC::getRouter()->getUrlPath();

		if( $path ) {
			$gallery = Gallery::resolveGalleryByURL( $path, MVC::getLocale() );
			if( !$gallery ) {
				return false;
			}

			$this->gallery = $gallery;
			MVC::getRouter()->setUsedUrlPath( $path );

			MVC::getPage()->setCacheContext( $gallery->getId() );
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
			$this->view->setVar( 'galleries', Gallery::getRootGalleries() );

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