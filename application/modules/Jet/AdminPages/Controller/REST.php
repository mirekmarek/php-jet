<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_AdminPages
 * @subpackage JetApplicationModule_AdminPages_Controller
 */
namespace JetApplicationModule\Jet\AdminPages;
use Jet;

class Controller_REST extends Jet\Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		"get_page" => "get_page",
		"post_page" => "add_page",
		"put_page" => "update_page",
		"delete_page" => "delete_page",

	);

	public function get_page_Action( $ID=null ) {
		if($ID) {
			$page = $this->_getPage($ID);
			$this->responseData($page);
		} else {
			$this->responseData( Jet\Mvc_Factory::getPageInstance()->getAllPagesTree(false) );
		}
	}

	public function post_page_Action() {
		$rq_data = $this->getRequestData();

		$page = Jet\Mvc_Pages::getNewPage( $rq_data["site_ID"], new Jet\Locale($rq_data["locale"]) , $rq_data["name"], $rq_data["parent_ID"] );
		$form = $page->getCommonForm();

		if($page->catchForm( $form, $rq_data , true )) {
			$page->validateData();
			$page->save();
			$this->responseData($page);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	public function put_page_Action( $ID ) {
		$page = $this->_getpage($ID);

		$form = $page->getCommonForm();

		if($page->catchForm( $form, $this->getRequestData(), true )) {
			$page->validateData();
			$page->save();
			$this->responseData($page);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	public function delete_page_Action( $ID ) {
		$page = $this->_getpage($ID);

		$page->delete();

		$this->responseOK();
	}



	/**
	 * @param $ID
	 * @return Jet\Mvc_Pages_Page_Default
	 */
	protected  function _getPage($ID) {

		$page_ID = Jet\Mvc_Factory::getPageIDInstance();
		$page_ID->unserialize($ID);

		$article = Jet\Mvc_Pages::getPage( $page_ID );

		if(!$article) {
			$this->responseUnknownItem($ID);
		}

		return $article;
	}

}