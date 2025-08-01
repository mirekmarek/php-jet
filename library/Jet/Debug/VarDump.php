<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Closure;

require_once 'ErrorHandler/Error/BacktraceItem.php';


class Debug_VarDump
{
	
	protected mixed $var = [];
	
	/**
	 * @var Debug_ErrorHandler_Error_BacktraceItem[]
	 */
	protected array $backtrace = [];
	
	protected string $caption = '';
	
	/**
	 * @var static[]
	 */
	public static array $var_dumps = [];
	
	public static bool $displayer_registered = false;
	
	protected static ?Closure $displayer = null;
	
	public function __construct( mixed $var, string $caption='' )
	{
		$this->var = $var;
		$this->backtrace = Debug_Profiler_Run::getBacktrace( 2 );
		$this->caption = $caption;
		
		static::$var_dumps[] = $this;
		
		if( !static::$displayer_registered ) {
			register_shutdown_function(function() {
				if( static::$displayer ) {
					$displayer = static::$displayer;
					
					$displayer( static::$var_dumps );
				}
			});
			static::$displayer_registered = true;
		}
	}
	
	public function getCation() : string
	{
		return $this->caption ? : $this->backtrace[0]->getFileDisplayable().':'.$this->backtrace[0]->getLine();
	}
	
	public function getVar(): mixed
	{
		return $this->var;
	}
	
	/**
	 * @return Debug_ErrorHandler_Error_BacktraceItem[]
	 */
	public function getBacktrace(): array
	{
		return $this->backtrace;
	}
	
	public static function getDisplayer(): ?Closure
	{
		return self::$displayer;
	}
	
	public static function setDisplayer( ?Closure $displayer ): void
	{
		self::$displayer = $displayer;
	}
	
	/**
	 * @return static[]
	 */
	public static function getVarDumps(): array
	{
		return self::$var_dumps;
	}
	
	
	
}