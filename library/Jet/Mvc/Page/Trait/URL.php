<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_URL {
	/**
	 *
	 * @var string
	 */
	protected $URL_fragment = '';

	/**
	 *
	 * @var string
	 */
	protected $relative_URI = '';

	/**
	 * @param string $URL_fragment
	 */
	public function setUrlFragment( $URL_fragment ) {
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_URL $this
		 */

		$this->URL_fragment = rawurlencode($URL_fragment);

		if( ($parent=$this->getParent()) ) {
			$this->setRelativeUrl($parent->getRelativeUrl().$this->URL_fragment.'/');
		}
	}

	/**
	 * @return string
	 */
	public function getUrlFragment() {
		return $this->URL_fragment;
	}

	/**
	 * @return string
	 */
	protected  function getRelativeUrl() {
		return $this->relative_URI;
	}

	/**
	 * @param string $URI
	 */
	protected function setRelativeUrl( $URI ) {
		$this->relative_URI = $URI;
	}

	/**
	 * @param $base_URL
	 * @param array $GET_params
	 * @param array $path_fragments
	 * @return string
	 */
	protected function _createURL( $base_URL, array $GET_params, array $path_fragments ) {
		$URL = $base_URL;
		$URL .= $this->relative_URI;


		if($path_fragments) {
			foreach($path_fragments as $i=>$p) {
				$path_fragments[$i] = rawurlencode( $p );
			}

			$path_fragments = implode('/', $path_fragments).'/';

			$URL .= $path_fragments;
		}

		if($GET_params) {
			foreach( $GET_params as $k=>$v ) {
				if(is_object($v)) {
					$GET_params[$k] = (string)$v;
				}
			}

			$query = http_build_query( $GET_params );

			$URL .= '?'.$query;
		}

		return $URL;

	}


	/**
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getURL(array $GET_params= [], array $path_fragments= []) {
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_URL $this
		 */

		if(
			(string)$this->getSite()->getId() == Mvc::getCurrentSite()->getId() &&
			(string)$this->locale == Mvc::getCurrentLocale() &&
			$this->getSSLRequired() == Mvc::getCurrentRouter()->getIsSSLRequest()
		) {

			return $this->getURI( $GET_params, $path_fragments );
		} else {

			return $this->getFullURL( $GET_params, $path_fragments );
		}
	}

	/**
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getURI(array $GET_params= [], array $path_fragments= []) {
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_URL $this
		 */

		$site = $this->getSite();

		if($this->getSSLRequired()) {
			$base_URL = $site->getDefaultSslURL( $this->locale )->getPathPart();
		} else {
			$base_URL = $site->getDefaultURL( $this->locale )->getPathPart();
		}


		return $this->_createURL($base_URL, $GET_params, $path_fragments);
	}

	/**
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getFullURL(array $GET_params= [], array $path_fragments= []) {
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_URL $this
		 */

		$site = $this->getSite();

		if($this->getSSLRequired()) {
			$base_URL = $site->getDefaultSslURL( $this->locale );
		} else {
			$base_URL = $site->getDefaultURL( $this->locale );
		}


		return $this->_createURL($base_URL, $GET_params, $path_fragments);
	}


	/**
	 * Example: //domain/page/
	 *
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getNonSchemaURL(array $GET_params= [], array $path_fragments= []) {
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_URL $this
		 */

		$site = $this->getSite();

		$base_URL = $site->getDefaultURL( $this->locale );

		$schema = $base_URL->getSchemePart();

		$base_URL = substr($base_URL, strlen($schema));

		return $this->_createURL($base_URL, $GET_params, $path_fragments);
	}

	/**
	 * Example: http://domain/page/
	 *
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getNonSslURL(array $GET_params= [], array $path_fragments= []) {
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_URL $this
		 */

		$site = $this->getSite();

		$base_URL = $site->getDefaultURL( $this->locale );

		return $this->_createURL($base_URL, $GET_params, $path_fragments);
	}

	/**
	 * Example: https://domain/page/
	 *
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getSslURL(array $GET_params= [], array $path_fragments= []) {
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_URL $this
		 */

		$site = $this->getSite();

		$base_URL = $site->getDefaultSslURL( $this->locale );

		return $this->_createURL($base_URL, $GET_params, $path_fragments);
	}

}