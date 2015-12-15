<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

abstract class Installer_Step_Controller {
	/**
	 * @var Installer
	 */
	protected $installer;
	/**
	 * @var Mvc_Layout
	 */
	protected $layout;
	/**
	 * @var Mvc_View
	 */
	protected $view;

	/**
	 * @var bool
	 */
	protected $is_prev = false;
	/**
	 * @var bool
	 */
	protected $is_current = false;
	/**
	 * @var bool
	 */
	protected $is_next = false;
	/**
	 * @var bool
	 */
	protected $is_last = false;

	/**
	 * @var string
	 */
	protected $URL = '';


	public function __construct( Installer $installator, $step_base_path, $is_prev, $is_current, $is_next, $is_last, $URL ) {
		$this->installer = $installator;
		$this->layout = $this->installer->getLayout();
		$this->is_prev = $is_prev;
		$this->is_current = $is_current;
		$this->is_next = $is_next;
		$this->is_last = $is_last;
		$this->URL = $URL;
		$this->view = new Mvc_View($step_base_path.'view/');
		$this->view->setVar('controller', $this);

	}

	abstract function main();

	/**
	 * @abstract
	 * @return string
	 */
	abstract function getLabel();

	/**
	 * @param string $name
	 *
	 */
	public function render( $name ) {
		$output = $this->view->render( $name );

		$this->layout->addOutputPart(
				$output
			);
	}

	/**
	 * @return bool
	 */
	public function getIsCurrent() {
		return $this->is_current;
	}

	/**
	 * @return bool
	 */
	public function getIsNext() {
		return $this->is_next;
	}

	/**
	 * @return bool
	 */
	public function getIsPrev() {
		return $this->is_prev;
	}

	/**
	 * @param bool $is_last
	 *
	 * @return bool
	 */
	public function setIsLast($is_last) {
		return $this->is_last=(bool)$is_last;
	}

	/**
	 * @return bool
	 */
	public function getIsLast() {
		return $this->is_last;
	}

	/**
	 * @return string
	 */
	public function getURL() {
		return $this->URL;
	}

	public function getIsSubStep() {
		return false;
	}

	/**
	 * @return bool|array
	 */
	public function getStepsAfter() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function getIsAvailable() {
		return true;
	}

}
