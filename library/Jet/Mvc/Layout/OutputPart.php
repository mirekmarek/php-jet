<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_Layout_OutputPart
 * @package Jet
 */
class Mvc_Layout_OutputPart extends BaseObject{
	/**
	 * @var string
	 */
	protected $output_id = '';

	/**
	 *
	 * @var string
	 */
	protected $output = '';

	/**
	 *
	 * @var string
	 */
	protected $position = '';

	/**
	 *
	 * @var bool
	 */
	protected $position_required = false;

	/**
	 *
	 * @var float
	 */
	protected $position_order = 0.0;

	/**
	 * @param string $output_id
	 * @param string $output
	 * @param string $position
	 * @param bool $position_required
	 * @param int $position_order
	 */
	public function __construct($output, $position, $position_required, $position_order, $output_id ) {
		$this->output_id = $output_id;
		$this->output = $output;
		$this->position = $position;
		$this->position_required = $position_required;
		$this->position_order = $position_order;
	}

	/**
	 * @return string
	 */
	public function getOutputId() {
		return $this->output_id;
	}

	/**
	 * @return string
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * @param $output
	 */
	public function setOutput($output) {
		$this->output = $output;
	}

	/**
	 * @return string
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @param string $position
	 */
	public function setPosition($position) {
		$this->position = $position;
	}

	/**
	 * @return bool
	 */
	public function getPositionRequired() {
		return $this->position_required;
	}

	/**
	 * @param bool $position_required
	 */
	public function setPositionRequired($position_required) {
		$this->position_required = (bool)$position_required;
	}

	/**
	 * @return int
	 */
	public function getPositionOrder() {
		return $this->position_order;
	}

	/**
	 * @param int $position_order
	 */
	public function setPositionOrder($position_order) {
		$this->position_order = (int)$position_order;
	}

}