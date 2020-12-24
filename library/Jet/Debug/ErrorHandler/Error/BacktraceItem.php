<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected string $file     = '';
	/**
	 * @var int|string
	 */
	protected int|string $line     = 0;
	/**
	 * @var string
	 */
	protected string $class    = '';
	/**
	 * @var string
	 */
	protected string $type     = '';
	/**
	 * @var string
	 */
	protected string $function = '';
	/**
	 * @var array
	 */
	protected array $args     = [];
	/**
	 * @var string
	 */
	protected string $call     = '';
	

	/**
	 *
	 * @param array $d
	 */
	public function __construct( array $d )
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
	public function getFile() : string
	{
		return $this->file;
	}

	/**
	 * @return int|string
	 */
	public function getLine() : int|string
	{
		return $this->line;
	}

	/**
	 * @return string
	 */
	public function getClass() : string
	{
		return $this->class;
	}

	/**
	 * @return string
	 */
	public function getType() : string
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getFunction() : string
	{
		return $this->function;
	}

	/**
	 * @return array
	 */
	public function getArgs() : array
	{
		return $this->args;
	}

	/**
	 * @return string
	 */
	public function getCall() : string
	{
		return $this->call;
	}


}
