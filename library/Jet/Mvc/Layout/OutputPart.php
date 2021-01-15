<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected string $output = '';

	/**
	 *
	 * @var string
	 */
	protected string $position = '';

	/**
	 *
	 * @var int
	 */
	protected int $position_order = 0;

	/**
	 * @param string $output
	 * @param string $position
	 * @param int $position_order
	 */
	public function __construct( string $output, string $position, int $position_order )
	{
		$this->output = $output;
		$this->position = $position;
		$this->position_order = $position_order;
	}

	/**
	 * @return string
	 */
	public function getOutput(): string
	{
		return $this->output;
	}

	/**
	 * @param string $output
	 */
	public function setOutput( string $output ): void
	{
		$this->output = $output;
	}

	/**
	 * @return string
	 */
	public function getPosition(): string
	{
		return $this->position;
	}

	/**
	 * @param string $position
	 */
	public function setPosition( string $position ): void
	{
		$this->position = $position;
	}

	/**
	 * @return int
	 */
	public function getPositionOrder(): int
	{
		return $this->position_order;
	}

	/**
	 * @param int $position_order
	 */
	public function setPositionOrder( int $position_order ): void
	{
		$this->position_order = $position_order;
	}

}