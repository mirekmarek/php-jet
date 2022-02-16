<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Throwable;

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
	protected static array $PHP_errors_txt = [
		E_ERROR             => 'PHP Error',
		E_PARSE             => 'PHP Parsing Error',
		E_CORE_ERROR        => 'PHP Core Error',
		E_CORE_WARNING      => 'PHP Core Warning',
		E_COMPILE_ERROR     => 'PHP Compile Error',
		E_COMPILE_WARNING   => 'PHP Compile Warning',
		E_RECOVERABLE_ERROR => 'PHP Recoverable error',
		E_USER_ERROR        => 'PHP User Error',

		E_WARNING           => 'PHP Warning',
		E_NOTICE            => 'PHP Notice',
		E_USER_WARNING      => 'PHP User Warning',
		E_USER_NOTICE       => 'PHP User Notice',
		E_STRICT            => 'PHP Runtime Notice',
		E_DEPRECATED        => 'PHP Deprecated',

	];
	/**
	 *
	 * @var array
	 */
	protected static array $PHP_fatal_errors = [
		E_ERROR,
		E_PARSE,
		E_CORE_ERROR,
		E_CORE_WARNING,
		E_COMPILE_ERROR,
		E_COMPILE_WARNING,
		E_RECOVERABLE_ERROR,
		E_USER_ERROR,
	];

	/**
	 * @var string
	 */
	protected string $request_URL = '';

	/**
	 * @var int|string
	 */
	protected int|string $code = 0;

	/**
	 * @var string
	 */
	protected string $txt = '';

	/**
	 * @var string
	 */
	protected string $message = '';

	/**
	 * @var string
	 */
	protected string $date = '';

	/**
	 * @var string
	 */
	protected string $time = '';

	/**
	 * @var string
	 */
	protected string $file = '';

	/**
	 * @var int
	 */
	protected int $line = 0;

	/**
	 * @var null|Throwable
	 */
	protected null|Throwable $exception = null;

	/**
	 * @var Debug_ErrorHandler_Error_BacktraceItem[]
	 */
	protected array $backtrace = [];

	/**
	 * @var array
	 */
	protected array $context = [];

	/**
	 * @var bool
	 */
	protected bool $is_fatal = false;

	/**
	 * @var bool
	 */
	protected bool $is_silenced = false;

	/**
	 * @var callable
	 */
	protected static $formatter;

	/**
	 * @param callable $formatter
	 */
	public static function setFormatter( callable $formatter ): void
	{
		self::$formatter = $formatter;
	}


	/**
	 *
	 * @param int $error_number
	 *
	 * @return string
	 */
	protected static function getPHPErrorText( int $error_number ): string
	{
		return static::$PHP_errors_txt[$error_number] ?? 'UNKNOWN (' . $error_number . ')';
	}

	/**
	 *
	 * @return string
	 */
	protected function getCurrentURL(): string
	{
		if( php_sapi_name() == 'cli' ) {
			return $_SERVER['SCRIPT_FILENAME'] ?? 'CLI';
		} else {
			if(
				!isset( $_SERVER['HTTP_HOST'] ) ||
				!isset( $_SERVER['REQUEST_URI'] )
			) {
				return 'unknown';
			}

			return $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}
	}

	/**
	 * @param Throwable $exception
	 *
	 * @return static
	 */
	public static function newException( Throwable $exception ): static
	{
		$e = new static();

		$e->exception = $exception;
		$e->txt = 'Uncaught exception: ' . get_class( $exception );
		$e->code = $exception->getCode();
		$e->message = $exception->getMessage();
		$e->file = $exception->getFile();
		$e->line = $exception->getLine();
		$backtrace = $exception->getTrace();

		$e->setBacktrace( $backtrace );

		$e->is_fatal = true;

		return $e;
	}


	/**
	 * @param int $code
	 * @param string $message
	 * @param string $file
	 * @param int $line
	 *
	 * @return static
	 */
	public static function newError( int $code, string $message, string $file, int $line ): static
	{

		$e = new static();

		$e->code = $code;
		$e->txt = self::getPHPErrorText( $code );
		$e->message = $message;
		$e->file = $file;
		$e->line = $line;

		$backtrace = debug_backtrace();
		array_shift( $backtrace );
		array_shift( $backtrace );
		$e->setBacktrace( $backtrace );

		$e->is_fatal = in_array( $e->code, static::$PHP_fatal_errors );

		return $e;
	}


	/**
	 *
	 */
	public function __construct()
	{

		Debug_ErrorHandler::doItSilent(function() {
			$this->date = date( 'Y-m-d' );
			$this->time = date( 'H:i:s' );
		});

		$this->request_URL = $this->getCurrentURL();
	}


	/**
	 *
	 * @param array $debug_backtrace
	 *
	 *
	 */
	protected function setBacktrace( array $debug_backtrace ): void
	{
		$this->backtrace = [];
		foreach( $debug_backtrace as $d ) {
			$this->backtrace[] = new Debug_ErrorHandler_Error_BacktraceItem( $d );
		}

	}

	/**
	 * @return string
	 */
	public function getRequestURL(): string
	{
		return $this->request_URL;
	}

	/**
	 * @return string
	 */
	public function getDate(): string
	{
		return $this->date;
	}

	/**
	 * @return string
	 */
	public function getTime(): string
	{
		return $this->time;
	}

	/**
	 * @return int
	 */
	public function getCode(): int
	{
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getTxt(): string
	{
		return $this->txt;
	}

	/**
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * @return string
	 */
	public function getFile(): string
	{
		return $this->file;
	}

	/**
	 * @return int
	 */
	public function getLine(): int
	{
		return $this->line;
	}

	/**
	 * @return Throwable|null
	 */
	public function getException(): Throwable|null
	{
		return $this->exception;
	}

	/**
	 * @return Debug_ErrorHandler_Error_BacktraceItem[]
	 */
	public function getBacktrace(): array
	{
		return $this->backtrace;
	}

	/**
	 * @return bool
	 */
	public function isFatal(): bool
	{
		return $this->is_fatal;
	}

	/**
	 * @return bool
	 */
	public function isSilenced(): bool
	{
		if($this->is_fatal) {
			return false;
		}

		return $this->is_silenced;
	}

	/**
	 * @param bool $is_silenced
	 */
	public function setIsSilenced( bool $is_silenced ): void
	{
		$this->is_silenced = $is_silenced;
	}



	/**
	 *
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{

		if( static::$formatter ) {
			$formatter = static::$formatter;

			return $formatter( $this );
		}

		$output = $this->getTxt() . PHP_EOL;
		$output .= $this->getMessage() . PHP_EOL;
		$output .= '' . PHP_EOL;
		$output .= 'script: ' . $this->getFile() . PHP_EOL;
		$output .= 'line: ' . $this->getLine() . PHP_EOL;
		$output .= 'time: ' . $this->getDate() . ' ' . $this->getTime() . PHP_EOL;
		$output .= 'URL: ' . $this->getRequestURL() . PHP_EOL;
		$output .= '' . PHP_EOL;

		if( $this->getBacktrace() ) {
			$output .= 'Debug backtrace:' . PHP_EOL;
			$output .= '' . PHP_EOL;


			foreach( $this->getBacktrace() as $d ) {
				$output .= $d->getFile();
				$output .= "\t" . 'Line: ' . $d->getLine() . PHP_EOL;
				$output .= "\t" . 'Call: ' . $d->getCall() . PHP_EOL;
				$output .= '' . PHP_EOL;
			}
			$output .= '' . PHP_EOL;
		}

		$output .= '' . PHP_EOL;

		return $output;

	}

}