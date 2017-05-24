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
class Debug_Profiler_Run_Block_Message
{
	/**
	 * @var string
	 */
	protected $text = "";

	/**
	 * @var float
	 */
	protected $timestamp = 0.0;

	/**
	 * @var array
	 */
	protected $backtrace = [];

	/**
	 * @param string $text
	 */
	public function __construct( $text )
	{
		$this->text = $text;

		$this->backtrace = Debug_Profiler::getBacktrace( 4 );

		$this->timestamp = microtime( true );
	}

	/**
	 * @return array
	 */
	public function getBacktrace()
	{
		return $this->backtrace;
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @return float
	 */
	public function getTimestamp()
	{
		return $this->timestamp;
	}

}