<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\JetExample\Images
 */
namespace JetApplicationModule\JetExample\Images;
use Jet;

class Controller_Public_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		'default' => false
	);


	public function default_Action() {
		$parent_ID = '_root_';
		$gallery = null;


		$path_fragments = Jet\Mvc::getPathFragments();

		$URI = Jet\Mvc::getCurrentURI();

		if($path_fragments) {

			foreach( $path_fragments as $pf ) {

				if( ($_g = Gallery::getByTitle( rawurldecode( $pf ), $parent_ID )) ) {
					$gallery = $_g;
					$parent_ID = $gallery->getID();
					$URI .= rawurlencode($gallery->getTitle()).'/';

					$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData( $gallery->getTitle(), $URI );

					Jet\Mvc::putUsedPathFragment( $pf );
				} else {
					break;
				}

			}

		}

		$children = Gallery::getChildren( $parent_ID );

		$this->view->setVar('children', $children);
		$this->view->setVar('gallery', $gallery);
		$this->view->setVar('root_URI', $URI);
		$this->view->setVar('icons_URI', $this->module_instance->getPublicURI().'icons/');

		$this->render('default');
	}
}