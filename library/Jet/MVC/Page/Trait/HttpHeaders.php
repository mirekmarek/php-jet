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
trait MVC_Page_Trait_HttpHeaders
{
	/**
	 *
	 * @var array<string,string>
	 */
	protected array $http_headers = [];

	/**
	 * @return array<string,string>
	 */
	public function getHttpHeaders(): array
	{
		if(
			!$this->http_headers &&
			$this->getParent()
		) {
			return $this->getParent()->getHttpHeaders();
		}
		return $this->http_headers;
	}

	/**
	 * @param array<string,string> $http_headers
	 */
	public function setHttpHeaders( array $http_headers ): void
	{
		$this->http_headers = $http_headers;
	}

}