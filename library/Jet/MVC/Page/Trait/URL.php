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
trait MVC_Page_Trait_URL
{
	/**
	 * @var string
	 */
	protected string $relative_path_fragment = '';

	/**
	 * @var string
	 */
	protected string $relative_path = '';


	/**
	 * @param string|null|bool $schema
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	protected function _createURL( string|null|bool $schema, array $path_fragments, array $GET_params ): string
	{
		/**
		 * @var MVC_Page|MVC_Page_Trait_URL $this
		 */

		$base = $this->getBase();


		$URL = $base->getLocalizedData( $this->locale )->getDefaultURL() . $this->relative_path;

		if( $schema === false ) {
			$URL = strchr( $URL, '/' );
		} else {
			if( $schema === null ) {
				if( $this->getSSLRequired() ) {
					$schema = 'https';
				} else {
					$schema = 'http';
				}
			}

			if( !$schema ) {
				$URL = '//' . $URL;

			} else {
				$URL = $schema . '://' . $URL;
			}
		}


		if( $path_fragments ) {

			$_path_fragments = $path_fragments;
			$path_fragments = [];
			$p = '';
			foreach( $_path_fragments as $p ) {
				if( !$p ) {
					continue;
				}
				$p = rawurlencode( $p );
				$p = str_replace( '%3A', ':', $p );

				$path_fragments[] = $p;

			}

			$do_not_add_slash = false;


			if( str_contains( $p, '.' ) ) {
				$do_not_add_slash = true;
			}

			$path_fragments = implode( '/', $path_fragments );

			if( $URL[strlen( $URL ) - 1] != '/' ) {
				$URL .= '/';
			}


			$URL .= $path_fragments;

			if(
				SysConf_Jet_MVC::getForceSlashOnURLEnd() &&
				!$do_not_add_slash
			) {
				$URL .= '/';
			}
		} else {
			if(
				$this->relative_path &&
				SysConf_Jet_MVC::getForceSlashOnURLEnd()
			) {
				$URL .= '/';
			}

		}

		if( $GET_params ) {
			foreach( $GET_params as $k => $v ) {
				if( is_object( $v ) ) {
					$GET_params[$k] = (string)$v;
				}
			}

			$query = http_build_query( $GET_params );

			$URL .= '?' . $query;
		}

		return $URL;

	}


	/**
	 *
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getURL( array $path_fragments = [], array $GET_params = [] ): string
	{
		return $this->_createURL( null, $path_fragments, $GET_params );
	}

	/**
	 *
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getURLPath( array $path_fragments = [], array $GET_params = [] ): string
	{
		return $this->_createURL( false, $path_fragments, $GET_params );
	}


	/**
	 *
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getNonSchemaURL( array $path_fragments = [], array $GET_params = [] ): string
	{
		/**
		 * @var MVC_Page|MVC_Page_Trait_URL $this
		 */

		return $this->_createURL( '', $path_fragments, $GET_params );
	}

	/**
	 *
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getNonSslURL( array $path_fragments = [], array $GET_params = [] ): string
	{
		return $this->_createURL( 'http', $path_fragments, $GET_params );
	}

	/**
	 *
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getSslURL( array $path_fragments = [], array $GET_params = [] ): string
	{
		return $this->_createURL( 'https', $path_fragments, $GET_params );
	}

	/**
	 * @param string $relative_path_fragment
	 */
	public function setRelativePathFragment( string $relative_path_fragment ): void
	{
		$this->relative_path_fragment = $relative_path_fragment;


		$parent = $this->getParent();
		if(
			$parent &&
			$parent->getRelativePath()
		) {
			$this->relative_path = $parent->getRelativePath() . '/' . $this->relative_path_fragment;
		} else {
			$this->relative_path = $this->relative_path_fragment;

		}

		foreach( $this->getChildren() as $ch ) {
			$ch->setRelativePathFragment( $ch->getRelativePathFragment() );

		}
	}


	/**
	 * @return string
	 */
	public function getRelativePathFragment(): string
	{
		return $this->relative_path_fragment;
	}


	/**
	 * @return string
	 */
	public function getRelativePath(): string
	{
		return $this->relative_path;
	}


	/**
	 * @param string $relative_path
	 */
	public function setRelativePath( string $relative_path ): void
	{
		$this->relative_path = $relative_path;
	}

}