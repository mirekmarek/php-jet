<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Images;
use Jet;
use Jet\Mvc_Controller_Standard;
use Jet\Mvc_Page_Content_Abstract;
use Jet\Mvc;

class Controller_Public_Standard extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = [
		'default' => false
	];

	/**
	 *
	 */
	public function initialize() {
	}

	/**
	 * @param string $gallery_ID
	 * @param Gallery $gallery (optional)
	 */
	public function default_Action( $gallery_ID=Gallery::ROOT_ID, Gallery $gallery=null ) {

        if(!$gallery) {
            $gallery = Gallery::get($gallery_ID);
        }

		$children = Gallery::getChildren( $gallery_ID );

		$this->view->setVar('children', $children);
		$this->view->setVar('gallery', $gallery);
		$this->view->setVar('icons_URI', $this->module_manifest->getPublicURI().'icons/');

		$this->render('default');
	}


    /**
     * @param Mvc_Page_Content_Abstract $page_content
     * @return bool
     */
    public function parseRequestURL_Public( Mvc_Page_Content_Abstract $page_content=null ) {
        $gallery_ID = Gallery::ROOT_ID;
        $gallery = null;


        $path_fragments = Mvc::getCurrentRouter()->getPathFragments();

        $URI = Mvc::getCurrentPage()->getURI();

        if($path_fragments) {

            foreach( $path_fragments as $pf ) {

                if( ($_g = Gallery::getByTitle( rawurldecode( $pf ), $gallery_ID )) ) {
                    $gallery = $_g;
                    $gallery_ID = $gallery->getID();
                    $URI .= rawurlencode($gallery->getTitle()).'/';

                    Mvc::getCurrentPage()->addBreadcrumbNavigationData( $gallery->getTitle(), $URI );

                } else {
                    return false;
                }

            }
        }

        $page_content->setControllerActionParameters( [ $gallery_ID, $gallery ]);

        return true;
    }
}