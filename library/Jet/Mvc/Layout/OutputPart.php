<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Mvc_Layout_OutputPart extends BaseObject
{

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
	 * @var int
	 */
	protected $position_order = 0;

	/**
	 * @param string $output
	 * @param string $position
	 * @param int    $position_order
	 */
	public function __construct( $output, $position, $position_order )
	{
		$this->output = $output;
		$this->position = $position;
		$this->position_order = $position_order;
	}

	/**
	 * @return string
	 */
	public function getOutput()
	{
		return $this->output;
	}

	/**
	 * @param string $output
	 */
	public function setOutput( $output )
	{
		$this->output = $output;
	}

	/**
	 * @return string
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @param string $position
	 */
	public function setPosition( $position )
	{
		$this->position = $position;
	}

	/**
	 * @return int
	 */
	public function getPositionOrder()
	{
		return $this->position_order;
	}

	/**
	 * @param int $position_order
	 */
	public function setPositionOrder( $position_order )
	{
		$this->position_order = (int)$position_order;
	}

}