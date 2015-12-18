<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\UIElements;
use Jet;
use Jet\Object;

class DataGrid_Column extends Object {

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var callable
	 */
	protected $display_callback;

	/**
	 * @var bool
	 */
	protected $allow_sort = true;

	/**
	 * @var string
	 */
	protected $css_style = '';

	/**
	 * @var string
	 */
	protected $css_class = '';

	/**
	 * @param string $name
	 * @param string $title
	 */
	public function __construct( $name, $title ) {
		$this->name = $name;
		$this->title = $title;
	}

	/**
	 * @param callable $display_callback
	 */
	public function setDisplayCallback($display_callback) {
		$this->display_callback = $display_callback;
	}

	/**
	 * @return callable
	 */
	public function getDisplayCallback() {
		return $this->display_callback;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $css_class
	 */
	public function setCssClass($css_class) {
		$this->css_class = $css_class;
	}

	/**
	 * @return string
	 */
	public function getCssClass() {
		return $this->css_class;
	}

	/**
	 * @param string $css_style
	 */
	public function setCssStyle($css_style) {
		$this->css_style = $css_style;
	}

	/**
	 * @return string
	 */
	public function getCssStyle() {
		return $this->css_style;
	}



	/**
	 * @param bool $allow_order_by
	 */
	public function setAllowSort($allow_order_by) {
		$this->allow_sort = (bool)$allow_order_by;
	}

	/**
	 * @return bool
	 */
	public function getAllowSort() {
		return $this->allow_sort;
	}



}