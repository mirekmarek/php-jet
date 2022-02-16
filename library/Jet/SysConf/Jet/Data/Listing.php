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
class SysConf_Jet_Data_Listing
{
	protected static string $pagination_page_no_get_param = 'p';
	protected static string $pagination_items_per_page_param = 'items_per_page';
	protected static int $pagination_max_items_per_page = 500;
	protected static int $pagination_default_items_per_page = 50;
	protected static string $sort_get_param = 'sort';

	/**
	 * @return string
	 */
	public static function getPaginationPageNoGetParam(): string
	{
		return static::$pagination_page_no_get_param;
	}

	/**
	 * @param string $pagination_page_no_get_param
	 */
	public static function setPaginationPageNoGetParam( string $pagination_page_no_get_param ): void
	{
		static::$pagination_page_no_get_param = $pagination_page_no_get_param;
	}

	/**
	 * @return string
	 */
	public static function getPaginationItemsPerPageParam(): string
	{
		return static::$pagination_items_per_page_param;
	}

	/**
	 * @param string $pagination_items_per_page_param
	 */
	public static function setPaginationItemsPerPageParam( string $pagination_items_per_page_param ): void
	{
		static::$pagination_items_per_page_param = $pagination_items_per_page_param;
	}

	/**
	 * @return int
	 */
	public static function getPaginationMaxItemsPerPage(): int
	{
		return static::$pagination_max_items_per_page;
	}

	/**
	 * @param int $pagination_max_items_per_page
	 */
	public static function setPaginationMaxItemsPerPage( int $pagination_max_items_per_page ): void
	{
		static::$pagination_max_items_per_page = $pagination_max_items_per_page;
	}

	/**
	 * @return int
	 */
	public static function getPaginationDefaultItemsPerPage(): int
	{
		return static::$pagination_default_items_per_page;
	}

	/**
	 * @param int $pagination_default_items_per_page
	 */
	public static function setPaginationDefaultItemsPerPage( int $pagination_default_items_per_page ): void
	{
		static::$pagination_default_items_per_page = $pagination_default_items_per_page;
	}

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


}