<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


/**
 *
 */
class Debug_Profiler_Run_Block_Message
{
	/**
	 * @var string
	 */
	protected string $text = '';

	/**
	 * @var float
	 */
	protected float $timestamp = 0.0;

	/**
	 * @var array
	 */
	protected array $backtrace = [];

	/**
	 * @param string $text
	 */
	public function __construct( string $text )
	{
		$this->text = $text;

		$this->backtrace = Debug_Profiler_Run::getBacktrace( 4 );

		$this->timestamp = microtime( true );
	}

	/**
	 * @return array
	 */
	public function getBacktrace(): array
	{
		return $this->backtrace;
	}

	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}

	/**
	 * @return float
	 */
	public function getTimestamp(): float
	{
		return $this->timestamp;
	}

}