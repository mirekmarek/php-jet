<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Debug
 * @subpackage Debug_ErrorHandler
 */

namespace Jet;

class Debug_ErrorHandler_Error {
	/**
	 * @var int
	 */
	public $code = 0;
	/**
	 * @var string
	 */
	public $txt = "";

	/**
	 * @var string
	 */
	public $message = "";

	/**
	 * @var string
	 */
	public $date = "";
	/**
	 * @var string
	 */
	public $time = "";

	/**
	 * @var string
	 */
	public $file = "";
	/**
	 * @var int
	 */
	public $line = 0;

	/**
	 * @var null|\Exception
	 */
	public $exception = null;

	/**
	 * @var array
	 */
	public $backtrace = array();
	/**
	 * @var array
	 */
	public $context = array();

	/**
	 * @var bool
	 */
	public $is_fatal = false;

	/**
	 * PHP error codes to human readable
	 *
	 * @var array
	 */
	public static $PHP_errors_txt = array(
		E_ERROR => "PHP Error",
		E_WARNING => "PHP Warning",
		E_PARSE => "PHP Parsing Error",
		E_NOTICE => "PHP Notice",
		E_CORE_ERROR => "PHP Core Error",
		E_CORE_WARNING => "PHP Core Warning",
		E_COMPILE_ERROR => "PHP Compile Error",
		E_COMPILE_WARNING => "PHP Compile Warning",
		E_RECOVERABLE_ERROR => "PHP Recoverable error",
		E_USER_ERROR => "PHP User Error",
		E_USER_WARNING => "PHP User Warning",
		E_USER_NOTICE => "PHP User Notice",
		E_STRICT => "PHP Runtime Notice"
	);

	/**
	 * Fatal errors list
	 *
	 * @var array
	 */
	public static $PHP_fatal_errors = array(
		E_ERROR,
		E_PARSE,
		E_CORE_ERROR,
		E_CORE_WARNING,
		E_COMPILE_ERROR,
		E_COMPILE_WARNING,
		E_USER_ERROR,
		E_RECOVERABLE_ERROR
	);

	/**
	 *
	 */
	public function  __construct() {

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$this->date = @date("Y-m-d");
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$this->time = @date("H:i:s");
	}


	/**
	 * @param \Exception $exception
	 *
	 * @return Debug_ErrorHandler_Error
	 */
	public static function newException( \Exception $exception ) {
		$e = new self();

		$e->exception = $exception;
		$e->txt = "Uncaught exception: ".get_class($exception);
		$e->code = $exception->getCode();
		$e->message = $exception->getMessage();
		$e->file = $exception->getFile();
		$e->line = $exception->getLine();
		$e->backtrace = $exception->getTrace();

		$e->is_fatal = true;

		return $e;
	}

	/**
	 * @param int $code
	 * @param string $message
	 * @param string $file
	 * @param int $line
	 * @param array $context
	 *
	 * @return Debug_ErrorHandler_Error
	 */
	public static function newError( $code, $message, $file, $line, $context ) {

		$e = new self();

		$e->code = $code;
		$e->txt = self::getErrorCodeText($code);
		$e->message = $message;
		$e->file = $file;
		$e->line = $line;

		$e->backtrace = debug_backtrace();
		$e->context = $context;
		
		array_shift($e->backtrace);
		array_shift($e->backtrace);

		$e->is_fatal = in_array($e->code, self::$PHP_fatal_errors);

		return $e;
	}

	/**
	 * @param string $error
	 *
	 * @return Debug_ErrorHandler_Error
	 */
	public static function newShutdownError( $error ) {
		$e = new self();

		$e->code = $error["type"];
		$e->txt = self::getErrorCodeText($error["type"]);
		$e->message = $error["message"];
		$e->file = $error["file"];
		$e->line = $error["line"];

		$e->is_fatal = in_array($e->code, self::$PHP_fatal_errors);

		return $e;
	}

	/**
	 * Gets human-readable version of error code
	 *
	 * @param int $error_number
	 * @return string
	 */
	public static function getErrorCodeText($error_number){
		return isset(self::$PHP_errors_txt[$error_number]) ?
				self::$PHP_errors_txt[$error_number]
				:
				"UNKNOWN (" . (int)$error_number . ")";
	}
}