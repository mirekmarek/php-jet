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
 * @category JetApplicationModule
 * @package JetApplicationModule\JetExample\UIElements
 */
namespace JetApplicationModule\JetExample\UIElements;
use Jet;

class Tree extends Jet\Object {

	/**
	 * @var Main
	 */
	protected $module_instance;

	/**
	 * @var Jet\Data_Tree
	 */
	protected $data;

	/**
	 * @var string
	 */
	protected $selected_ID = '';

	/**
	 * @var bool
	 */
	protected $show_all = false;

	/**
	 * @var callable
	 */
	protected $selected_display_callback;

	/**
	 * @var callable
	 */
	protected $opened_display_callback;

	/**
	 * @var callable
	 */
	protected $normal_display_callback;

	/**
	 * @param Main $module_instance
	 */
	public function __construct( Main $module_instance ) {
		$this->module_instance = $module_instance;
	}

	/**
	 * @param Jet\Data_Tree $data
	 */
	public function setData(Jet\Data_Tree $data) {
		$this->data = $data;
	}

	/**
	 * @return Jet\Data_Tree
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @param string $selected_ID
	 */
	public function setSelectedID($selected_ID) {
		$this->selected_ID = $selected_ID;
	}

	/**
	 * @return string
	 */
	public function getSelectedID() {
		return $this->selected_ID;
	}

	/**
	 * @param boolean $show_all
	 */
	public function setShowAll($show_all) {
		$this->show_all = $show_all;
	}

	/**
	 * @return boolean
	 */
	public function getShowAll() {
		return $this->show_all;
	}

	/**
	 * @param callable $normal_display_callback
	 */
	public function setNormalDisplayCallback( callable $normal_display_callback) {
		$this->normal_display_callback = $normal_display_callback;
	}

	/**
	 * @return callable
	 */
	public function getNormalDisplayCallback() {
		return $this->normal_display_callback;
	}

	/**
	 * @param callable $opened_display_callback
	 */
	public function setOpenedDisplayCallback( callable $opened_display_callback) {
		$this->opened_display_callback = $opened_display_callback;
	}

	/**
	 * @return callable
	 */
	public function getOpenedDisplayCallback() {
		return $this->opened_display_callback;
	}

	/**
	 * @param callable $selected_display_callback
	 */
	public function setSelectedDisplayCallback( callable $selected_display_callback) {
		$this->selected_display_callback = $selected_display_callback;
	}

	/**
	 * @return callable
	 */
	public function getSelectedDisplayCallback() {
		return $this->selected_display_callback;
	}



	/**
	 * @return string
	 */
	public function render() {

		$view = $this->module_instance->getViewInstance();
		$view->setVar( 'tree', $this );

		$view->setVar( 'images_uri', $this->module_instance->getPublicURI().'images/' );

		return $view->render('Tree/tree');

	}



}