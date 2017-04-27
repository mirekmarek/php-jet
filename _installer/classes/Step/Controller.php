<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\Mvc_Layout;
use Jet\Mvc_View;
use Jet\Tr;

/**
 *
 */
abstract class Installer_Step_Controller {
	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $label = '';

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
	protected $is_past = false;

	/**
	 * @var bool
	 */
	protected $is_previous = false;

	/**
	 * @var bool
	 */
	protected $is_current = false;

	/**
	 * @var bool
	 */
	protected $is_coming = false;

	/**
	 * @var bool
	 */
	protected $is_future = false;
	/**
	 * @var bool
	 */
	protected $is_last = false;


	/**
	 *
	 * @param string $name
	 * @param string $step_base_path
	 */
	public function __construct( $name, $step_base_path ) {
		$this->name = $name;

		$this->view = new Mvc_View($step_base_path.'view/');
		$this->view->setVar('controller', $this);

	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 *
	 */
	abstract function main();

	/**
	 * @param string $name
	 *
	 */
	public function render( $name ) {
		$output = $this->view->render( $name );

		Installer::getLayout()->addOutputPart(
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
	 * @param bool $is_current
	 */
	public function setIsCurrent($is_current)
	{
		$this->is_current = $is_current;
	}

	/**
	 * @return bool
	 */
	public function getIsFuture() {
		return $this->is_future;
	}

	/**
	 * @param bool $is_future
	 */
	public function setIsFuture($is_future)
	{
		$this->is_future = $is_future;
	}

	/**
	 * @return bool
	 */
	public function getIsPast() {
		return $this->is_past;
	}

	/**
	 * @param bool $is_past
	 */
	public function setIsPast($is_past)
	{
		$this->is_past = $is_past;
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
	 * @return bool
	 */
	public function getIsPrevious()
	{
		return $this->is_previous;
	}

	/**
	 * @param bool $is_previous
	 */
	public function setIsPrevious($is_previous)
	{
		$this->is_previous = $is_previous;
	}

	/**
	 * @return bool
	 */
	public function getIsComing()
	{
		return $this->is_coming;
	}

	/**
	 * @param bool $is_coming
	 */
	public function setIsComing($is_coming)
	{
		$this->is_coming = $is_coming;
	}

	/**
	 * @return string
	 */
	public function getURL() {
		return '?step='.$this->name;
	}

	/**
	 * @return bool
	 */
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

	/**
	 * @return string
	 */
	public function getLabel() {
		return Tr::_($this->label, [], $this->name);
	}

}
