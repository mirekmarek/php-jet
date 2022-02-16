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
trait MVC_Page_Trait_Cache
{
	/**
	 * @var string
	 */
	protected string $_cache_context = '';

	/**
	 * @return string
	 */
	public function getCacheContext(): string
	{
		return $this->_cache_context;
	}

	/**
	 * @param string $cache_context
	 */
	public function setCacheContext( string $cache_context ): void
	{
		$this->_cache_context = $cache_context;
	}
}