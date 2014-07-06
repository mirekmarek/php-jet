<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_NavigationData
 */
namespace Jet;

/**
 * Class Mvc_NavigationData_Breadcrumb_Abstract
 *
 * @JetFactory:class = 'Jet\Mvc_NavigationData_Factory'
 * @JetFactory:method = 'getBreadcrumbInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_NavigationData_Breadcrumb_Abstract'
 */
abstract class Mvc_NavigationData_Breadcrumb_Abstract extends Object {

	/**
	 * @var Mvc_Pages_Page_ID_Abstract
	 */
	protected $page_ID;

	/**
	 *
	 * @var Mvc_Pages_Page_Abstract
	 */
	protected $page = null;

	/**
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 *
	 * @var string
	 */
	protected $URI = '';

	/**
	 * @var Mvc_Router_Map_URL_Abstract
	 */
	protected $map_URL_object;

	/**
	 *
	 * @var bool
	 */
	protected $is_last = false;

	/**
	 *
	 * @return Mvc_Pages_Page_Abstract
	 */
	public function getPage() {
		if( !$this->page && $this->page_ID ) {
			$this->page = Mvc_Pages::getPage( $this->page_ID );
		}
		return $this->page;
	}

	/**
	 * @param Mvc_Pages_Page_Abstract $page
	 */
	public function setPage( Mvc_Pages_Page_Abstract $page ) {
		$this->page = $page;
		$this->page_ID = $page->getID();
		$this->URI = $page->getURI();
		$this->title = $page->getBreadcrumbTitle();
	}

	/**
	 *
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 *
	 */
	public function setPageID( Mvc_Pages_Page_ID_Abstract $page_ID) {
		$this->page_ID = $page_ID;
		$this->page = null;
	}

	/**
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 *
	 * @param <type> $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 *
	 * @return string
	 */
	public function getURI() {
		return $this->URI;
	}

	/**
	 *
	 * @param string $URI
	 */
	public function setURI($URI) {
		$this->URI = $URI;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsLast() {
		return $this->is_last;
	}

	/**
	 *
	 * @param bool $is_last
	 */
	public function setIsLast($is_last) {
		$this->is_last = (bool)$is_last;
	}

	/**
	 * @param Mvc_Router_Map_URL_Abstract $map_URL_object
	 */
	public function setMapURLObject( Mvc_Router_Map_URL_Abstract $map_URL_object) {
		$this->map_URL_object = $map_URL_object;
	}

	/**
	 * @return Mvc_Router_Map_URL_Abstract|null
	 */
	public function getMapURLObject() {
		return $this->map_URL_object;
	}


}