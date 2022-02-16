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
class SysConf_Jet_Http
{
	protected static string $header_function_name = '\header';
	protected static string $http_version = '1.1';
	protected static bool $hide_request = true;

	protected static array $response_messages = [
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',

		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',

		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		444 => 'No Response',
		451 => 'Unavailable For Legal Reasons',

		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
		598 => 'Network read timeout error',
		599 => 'Network connect timeout error',
	];


	/**
	 * @return string
	 */
	public static function getHeaderFunctionName(): string
	{
		return static::$header_function_name;
	}

	/**
	 * @param string $header_function_name
	 */
	public static function setHeaderFunctionName( string $header_function_name ): void
	{
		static::$header_function_name = $header_function_name;
	}

	/**
	 * @return string
	 */
	public static function getHttpVersion(): string
	{
		return static::$http_version;
	}

	/**
	 * @param string $http_version
	 */
	public static function setHttpVersion( string $http_version ): void
	{
		static::$http_version = $http_version;
	}

	/**
	 * @return bool
	 */
	public static function getHideRequest(): bool
	{
		return static::$hide_request;
	}

	/**
	 * @param bool $hide_request
	 */
	public static function setHideRequest( bool $hide_request ): void
	{
		static::$hide_request = $hide_request;
	}

	/**
	 * @return array
	 */
	public static function getResponseMessages(): array
	{
		return self::$response_messages;
	}

	/**
	 * @param array|string[] $response_messages
	 */
	public static function setResponseMessages( array $response_messages ): void
	{
		self::$response_messages = $response_messages;
	}



}