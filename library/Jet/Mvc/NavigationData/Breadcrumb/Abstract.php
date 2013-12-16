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

abstract class Mvc_NavigationData_Breadcrumb_Abstract extends Object {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\Mvc_NavigationData_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getBreadcrumbInstance";
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Mvc_NavigationData_Breadcrumb_Abstract";

	/**
	 * @var Mvc_Pages_Page_ID_Abstract
	 */
	protected $page_ID;

	/**
	 *
	 * @var Mvc_Pages_Page_Abstract
	 */
	protected $page = NULL;

	/**
	 *
	 * @var string
	 */
	protected $title = "";

	/**
	 *
	 * @var string
	 */
	protected $URI = "";

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


}