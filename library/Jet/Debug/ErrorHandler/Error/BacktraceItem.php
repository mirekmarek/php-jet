<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Debug_ErrorHandler_Error_BacktraceItem
{
	/**
	 * @var string
	 */
	protected $file     = '';
	/**
	 * @var int
	 */
	protected $line     = 0;
	/**
	 * @var string
	 */
	protected $class    = '';
	/**
	 * @var string
	 */
	protected $type     = '';
	/**
	 * @var string
	 */
	protected $function = '';
	/**
	 * @var array
	 */
	protected $args     = [];
	/**
	 * @var string
	 */
	protected $call     = '';
	

	/**
	 *
	 * @param array $d
	 */
	public function __construct( $d )
	{

		$class = isset( $d['class'] ) ? $d['class'] : '';
		$type = isset( $d['type'] ) ? $d['type'] : '';
		$function = isset( $d['function'] ) ? $d['function'] : '';
		$args = isset( $d['args'] ) ? $d['args'] : [];

		$call = '';
		if( $class ) {
			$call .= $class.$type;
		}

		if( $function ) {
			$call .= $function;
		}


		$this->file     = isset( $d['file'] ) ? $d['file'] : '?';
		$this->line     = isset( $d['line'] ) ? $d['line'] : '?';
		$this->class    = $class;
		$this->type     = $type;
		$this->function = $function;
		$this->call     = $call;
		$this->args     = $args;

	}

	/**
	 * @return string
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->line;
	}

	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getFunction()
	{
		return $this->function;
	}

	/**
	 * @return array
	 */
	public function getArgs()
	{
		return $this->args;
	}

	/**
	 * @return string
	 */
	public function getCall()
	{
		return $this->call;
	}


}
