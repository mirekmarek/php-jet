<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'Error/BacktraceItem.php';

/**
 *
 */
class Debug_ErrorHandler_Error
{
	/**
	 *
	 * @var array
	 */
	protected static $PHP_errors_txt = [
		E_ERROR             => 'PHP Error', E_WARNING => 'PHP Warning', E_PARSE => 'PHP Parsing Error',
		E_NOTICE            => 'PHP Notice', E_CORE_ERROR => 'PHP Core Error', E_CORE_WARNING => 'PHP Core Warning',
		E_COMPILE_ERROR     => 'PHP Compile Error', E_COMPILE_WARNING => 'PHP Compile Warning',
		E_RECOVERABLE_ERROR => 'PHP Recoverable error', E_USER_ERROR => 'PHP User Error',
		E_USER_WARNING      => 'PHP User Warning', E_USER_NOTICE => 'PHP User Notice', E_STRICT => 'PHP Runtime Notice',
		E_DEPRECATED        => 'PHP Deprecated',

	];
	/**
	 *
	 * @var array
	 */
	protected static $PHP_fatal_errors = [
		E_ERROR,
		E_PARSE,
		E_CORE_ERROR,
		E_CORE_WARNING,
		E_COMPILE_ERROR,
		E_COMPILE_WARNING,
		E_USER_ERROR,
		E_RECOVERABLE_ERROR,
	];

	/**
	 * @var string
	 */
	protected $request_URL = '';

	/**
	 * @var int
	 */
	protected $code = 0;
	/**
	 * @var string
	 */
	protected $txt = '';
	/**
	 * @var string
	 */
	protected $message = '';
	/**
	 * @var string
	 */
	protected $date = '';
	/**
	 * @var string
	 */
	protected $time = '';
	/**
	 * @var string
	 */
	protected $file = '';
	/**
	 * @var int
	 */
	protected $line = 0;
	/**
	 * @var null|\Exception
	 */
	protected $exception = null;
	/**
	 * @var Debug_ErrorHandler_Error_BacktraceItem[]
	 */
	protected $backtrace = [];
	/**
	 * @var array
	 */
	protected $context = [];
	/**
	 * @var bool
	 */
	protected $is_fatal = false;

	/**
	 * @var callable
	 */
	protected static $formatter;

	/**
	 * @param callable $formatter
	 */
	public static function setFormatter( callable $formatter )
	{
		self::$formatter = $formatter;
	}


	/**
	 *
	 * @param int $error_number
	 *
	 * @return string
	 */
	protected static function getPHPErrorText( $error_number )
	{
		return isset( static::$PHP_errors_txt[$error_number] ) ?
			static::$PHP_errors_txt[$error_number]
			:
			'UNKNOWN ('.(int)$error_number.')';
	}

	/**
	 *
	 * @return string
	 */
	protected function getCurrentURL()
	{
		if( php_sapi_name()=='cli' ) {
			return isset( $_SERVER['SCRIPT_FILENAME'] ) ? $_SERVER['SCRIPT_FILENAME'] : 'CLI';
		} else {
			if(
				!isset( $_SERVER['HTTP_HOST'] ) ||
				!isset( $_SERVER['HTTP_HOST'] )
			) {
				return 'unknown';
			}

			return $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
	}


	/**
	 *
	 * @param mixed $var
	 *
	 * @return string
	 */
	public static function formatVariable( $var )
	{

		if( is_object( $var ) ) {
			return get_class( $var );
		}

		$result = print_r( $var, true );
		if( strlen( $result )>2048 ) {
			$result = substr( $result, 0, 2048 ).' ...';
		}

		return $result;
	}

	/**
	 * @param \Exception $exception
	 *
	 * @return Debug_ErrorHandler_Error
	 */
	public static function newException( \Exception $exception )
	{
		$e = new static();

		$e->exception = $exception;
		$e->txt = 'Uncaught exception: '.get_class( $exception );
		$e->code = $exception->getCode();
		$e->message = $exception->getMessage();
		$e->file = $exception->getFile();
		$e->line = $exception->getLine();
		$backtrace = $exception->getTrace();

		$e->setBacktrace($backtrace);

		$e->is_fatal = true;

		return $e;
	}


	/**
	 * @param int    $code
	 * @param string $message
	 * @param string $file
	 * @param int    $line
	 * @param array  $context
	 *
	 * @return Debug_ErrorHandler_Error
	 */
	public static function newError( $code, $message, $file, $line, $context )
	{

		$e = new static();

		$e->code = $code;
		$e->txt = self::getPHPErrorText( $code );
		$e->message = $message;
		$e->file = $file;
		$e->line = $line;

		$e->setContext($context);

		$backtrace = debug_backtrace();
		array_shift( $backtrace );
		array_shift( $backtrace );
		$e->setBacktrace($backtrace);

		$e->is_fatal = in_array( $e->code, static::$PHP_fatal_errors );

		return $e;
	}

	/**
	 * @param array $error
	 *
	 * @return Debug_ErrorHandler_Error
	 */
	public static function newShutdownError( $error )
	{
		$e = new static();

		$e->code = $error['type'];
		$e->txt = self::getPHPErrorText( $error['type'] );
		$e->message = $error['message'];
		$e->file = $error['file'];
		$e->line = $error['line'];

		$e->is_fatal = in_array( $e->code, static::$PHP_fatal_errors );

		return $e;
	}


	/**
	 *
	 */
	public function __construct()
	{

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$this->date = @date( 'Y-m-d' );
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$this->time = @date( 'H:i:s' );

		$this->request_URL = $this->getCurrentURL();
	}


	/**
	 *
	 * @param array $error_context
	 *
	 */
	protected function setContext( array $error_context )
	{
		$this->context = [];

		foreach( $error_context as $k => $v ) {
			$this->context[$k] = $v;
		}

	}

	/**
	 *
	 * @param array $debug_backtrace
	 *
	 *
	 */
	protected function setBacktrace( array $debug_backtrace )
	{
		$this->backtrace = [];
		foreach( $debug_backtrace as $d ) {
			$this->backtrace[] = new Debug_ErrorHandler_Error_BacktraceItem( $d );
		}

	}

	/**
	 * @return string
	 */
	public function getRequestURL()
	{
		return $this->request_URL;
	}

	/**
	 * @return string
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * @return string
	 */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getTxt()
	{
		return $this->txt;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
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
	 * @return \Exception|null
	 */
	public function getException()
	{
		return $this->exception;
	}

	/**
	 * @return Debug_ErrorHandler_Error_BacktraceItem[]
	 */
	public function getBacktrace()
	{
		return $this->backtrace;
	}

	/**
	 * @return array
	 */
	public function getContext()
	{
		return $this->context;
	}

	/**
	 * @return bool
	 */
	public function isFatal()
	{
		return $this->is_fatal;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString() {

		if(static::$formatter) {
			$formatter = static::$formatter;

			return $formatter( $this );
		}

		$output = '';

		$output .=$this->getTxt().JET_EOL;
		$output .=$this->getMessage().JET_EOL;
		$output .=''.JET_EOL;
		$output .='script: '.$this->getFile().JET_EOL;
		$output .='line: '.$this->getLine().JET_EOL;
		$output .='time: '.$this->getDate().' '.$this->getTime().JET_EOL;
		$output .='URL: '.$this->getRequestURL().JET_EOL;
		$output .=''.JET_EOL;


		if( $this->getContext() ) {
			$output .='Error context:'.JET_EOL;
			$output .=''.JET_EOL;

			foreach( $this->getContext() as $var_name => $var_value ) {
				$output .=JET_TAB.'$'.$var_name.' = '.static::formatVariable($var_value).JET_EOL;
			}
			$output .=''.JET_EOL;
		}


		if( $this->getBacktrace() ) {
			$output .='Debug backtrace:'.JET_EOL;
			$output .=''.JET_EOL;


			foreach( $this->getBacktrace() as $d ) {
				$output .=$d->getFile();
				$output .=JET_TAB.'Line: '.$d->getLine().JET_EOL;
				$output .=JET_TAB.'Call: '.$d->getCall().JET_EOL;
				$output .=''.JET_EOL;
			}
			$output .=''.JET_EOL;
		}

		$output .=''.JET_EOL;

		return $output;

	}

}