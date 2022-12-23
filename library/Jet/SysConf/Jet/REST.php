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
class SysConf_Jet_REST
{
	protected static string $sort_get_param = 'sort';
	protected static string $page_get_param = 'page';
	protected static string $items_per_page_get_param = 'items_per_page';
	protected static int $default_items_per_page = 20;
	protected static int $max_items_per_page = 100;

	/**
	 *
	 * Format:
	 *
	 * error_code => array(http_error_code, error_message)
	 *
	 * Example:
	 *
	 * <code>
	 * const ERROR_CODE_OBJECT_NOT_FOUND = 'ObjectNotFound';
	 *
	 * protected static $errors = [
	 *      REST::ERROR_CODE_OBJECT_NOT_FOUND => [Http_Headers::CODE_404_NOT_FOUND, 'Object not found']
	 * ];
	 * </code>
	 *
	 * @var array
	 */
	protected static array $errors = [
		RESTServer::ERR_CODE_AUTHORIZATION_REQUIRED => [
			Http_Headers::CODE_401_UNAUTHORIZED,
			'Access denied! Authorization required! ',
		],
		RESTServer::ERR_CODE_ACCESS_DENIED          => [
			Http_Headers::CODE_401_UNAUTHORIZED,
			'Access denied! Insufficient permissions! ',
		],
		RESTServer::ERR_CODE_VALIDATION_ERROR       => [
			Http_Headers::CODE_400_BAD_REQUEST,
			'Validation error',
		],
		RESTServer::ERR_CODE_REQUEST_ERROR          => [
			Http_Headers::CODE_400_BAD_REQUEST,
			'Bad request'
		],
		RESTServer::ERR_CODE_UNKNOWN_ITEM           => [
			Http_Headers::CODE_404_NOT_FOUND,
			'Unknown item'
		],
		RESTServer::ERR_CODE_COMMON                 => [
			Http_Headers::CODE_400_BAD_REQUEST,
			'Common error'
		],
	];

	/**
	 * @return string
	 */
	public static function getSortGetParam(): string
	{
		return static::$sort_get_param;
	}

	/**
	 * @param string $sort_get_param
	 */
	public static function setSortGetParam( string $sort_get_param ): void
	{
		static::$sort_get_param = $sort_get_param;
	}

	/**
	 * @return string
	 */
	public static function getPageGetParam(): string
	{
		return static::$page_get_param;
	}

	/**
	 * @param string $page_get_param
	 */
	public static function setPageGetParam( string $page_get_param ): void
	{
		static::$page_get_param = $page_get_param;
	}

	/**
	 * @return string
	 */
	public static function getItemsPerPageGetParam(): string
	{
		return static::$items_per_page_get_param;
	}

	/**
	 * @param string $items_per_page_get_param
	 */
	public static function setItemsPerPageGetParam( string $items_per_page_get_param ): void
	{
		static::$items_per_page_get_param = $items_per_page_get_param;
	}

	/**
	 * @return int
	 */
	public static function getDefaultItemsPerPage(): int
	{
		return static::$default_items_per_page;
	}

	/**
	 * @param int $default_items_per_page
	 */
	public static function setDefaultItemsPerPage( int $default_items_per_page ): void
	{
		static::$default_items_per_page = $default_items_per_page;
	}

	/**
	 * @return int
	 */
	public static function getMaxItemsPerPage(): int
	{
		return static::$max_items_per_page;
	}

	/**
	 * @param int $max_items_per_page
	 */
	public static function setMaxItemsPerPage( int $max_items_per_page ): void
	{
		static::$max_items_per_page = $max_items_per_page;
	}

	/**
	 * @return array
	 */
	public static function getErrors(): array
	{
		return static::$errors;
	}

	/**
	 * @param array $errors
	 */
	public static function setErrors( array $errors ): void
	{
		static::$errors = $errors;
	}

	/**
	 * @param string $code
	 * @param int $http_code
	 * @param string $message
	 */
	public static function defineError( string $code, int $http_code, string $message ) : void
	{
		static::$errors[$code] = [
			$http_code,
			$message
		];
	}

}