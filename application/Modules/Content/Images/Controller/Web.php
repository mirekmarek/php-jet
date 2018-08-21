<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Images;

use Jet\Mvc_Controller_Default;
use Jet\Mvc;
use Jet\Navigation_Breadcrumb;

/**
 *
 */
class Controller_Web extends Mvc_Controller_Default
{
	/**
	 * @var array
	 */
	const ACL_ACTIONS_MAP = [
		'default' => false,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module = null;

	/**
	 *
	 */
	public function default_Action()
	{

		/**
		 * @var Gallery $gallery
		 */
		$gallery = $this->getParameter( 'gallery' );

		if(!$gallery) {
			$this->view->setVar( 'galleries', Gallery::getRootGalleries() );

			$this->render( 'web/default' );

		} else {

			foreach( $gallery->getPath() as $g ) {
				Navigation_Breadcrumb::addURL( $g->getTitle(), $g->getURL() );
			}

			$this->view->setVar( 'gallery', $gallery );
			$this->render( 'web/gallery' );
		}
	}


	/**
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function resolve( $path )
	{

		$gallery = Gallery::resolveGalleryByURL( $path, Mvc::getCurrentLocale() );

		if(!$gallery) {
			return false;
		}

		$this->content->setParameter('gallery', $gallery );

		return true;
	}
}