<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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

abstract class Mvc_NavigationData_Breadcrumb_Abstract extends BaseObject {

	/**
	 *
	 * @var Mvc_Page_Interface
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
	 *
	 * @var bool
	 */
	protected $is_last = false;

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function setPage( Mvc_Page_Interface $page ) {
		$this->page = $page;
		$this->URI = $page->getURI();
		$this->title = $page->getBreadcrumbTitle();
	}

    /**
     *
     * @return Mvc_Page_Interface|null
     */
    public function getPage() {
        return $this->page;
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
	 * @param string $title
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