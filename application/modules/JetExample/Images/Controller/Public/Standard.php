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

	/**
	 *
	 */
	public function initialize() {
	}

	/**
	 * @param string $gallery_ID
	 * @param Gallery $gallery (optional)
	 */
	public function default_Action( $gallery_ID, Gallery $gallery=null ) {

		$children = Gallery::getChildren( $gallery_ID );

		$this->view->setVar('children', $children);
		$this->view->setVar('gallery', $gallery);
		$this->view->setVar('icons_URI', $this->module_instance->getPublicURI().'icons/');

		$this->render('default');
	}
}