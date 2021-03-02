<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'LocalizedData/Interface.php';
require_once 'LocalizedData/MetaTag.php';

/**
 *
 */
class Mvc_Site_LocalizedData extends BaseObject implements Mvc_Site_LocalizedData_Interface
{

	/**
	 *
	 * @var ?Mvc_Site_Interface
	 */
	protected ?Mvc_Site_Interface $__site = null;

	/**
	 *
	 * @var ?Locale
	 */
	protected ?Locale $__locale = null;

	/**
	 *
	 * @var bool
	 */
	protected bool $is_active = true;


	/**
	 * @var bool
	 */
	protected bool $SSL_required = false;

	/**
	 *
	 * @var string
	 */
	protected string $title = '';

	/**
	 *
	 * @var array
	 */
	protected array $URLs = [];

	/**
	 *
	 * @var Mvc_Site_LocalizedData_MetaTag[]
	 */
	protected array $default_meta_tags = [];

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 * @param array $data
	 *
	 * @return static
	 */
	public static function createByData( Mvc_Site_Interface $site, Locale $locale, array $data ): static
	{
		/**
		 * @var Mvc_Site_LocalizedData $ld
		 */
		$ld = Mvc_Factory::getSiteLocalizedInstance();

		$ld->setSite( $site );
		$ld->setLocale( $locale );

		$ld->setData( $data );

		return $ld;
	}

	/**
	 * @param array $data
	 */
	protected function setData( array $data ): void
	{
		$meta_tags = [];

		if( isset( $data['default_meta_tags'] ) ) {
			foreach( $data['default_meta_tags'] as $m_data ) {
				$meta_tags[] = Mvc_Site_LocalizedData_MetaTag::createByData( $this, $m_data );
			}

			$this->setDefaultMetaTags( $meta_tags );

			unset( $data['default_meta_tags'] );
		}

		foreach( $data as $key => $val ) {
			$this->{$key} = $val;
		}
	}

	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite(): Mvc_Site_Interface
	{
		return $this->__site;
	}

	/**
	 * @param Mvc_Site_Interface $site
	 */
	public function setSite( Mvc_Site_Interface $site ): void
	{
		$this->__site = $site;
	}


	/**
	 * @return Locale
	 */
	public function getLocale(): Locale
	{
		return $this->__locale;
	}

	/**
	 * @param Locale $locale
	 *
	 */
	public function setLocale( Locale $locale ): void
	{
		$this->__locale = $locale;
	}

	/**
	 * @return bool
	 */
	public function getIsActive(): bool
	{
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( bool $is_active ): void
	{
		$this->is_active = (bool)$is_active;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void
	{
		$this->title = $title;
	}

	/**
	 * @return array
	 */
	public function getURLs(): array
	{
		return $this->URLs;
	}

	/**
	 * @param array $URLs
	 */
	public function setURLs( array $URLs ): void
	{
		foreach( $URLs as $i => $URL ) {
			$URL = trim( $URL, '/' );
			$URL .= '/';

			$URLs[$i] = $URL;
		}

		$this->URLs = $URLs;
	}


	/**
	 * @return string
	 */
	public function getDefaultURL(): string
	{
		return $this->URLs[0];
	}


	/**
	 * @return bool
	 */
	public function getSSLRequired(): bool
	{
		if( $this->__site->getSSLRequired() ) {
			return true;
		}

		return $this->SSL_required;
	}

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( bool $SSL_required ): void
	{
		$this->SSL_required = $SSL_required;
	}


	/**
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag[]
	 */
	public function getDefaultMetaTags(): array
	{
		return $this->default_meta_tags;
	}

	/**
	 *
	 * @param Mvc_Site_LocalizedData_MetaTag_Interface[] $default_meta_tags
	 */
	public function setDefaultMetaTags( array $default_meta_tags ): void
	{
		$this->default_meta_tags = [];

		foreach( $default_meta_tags as $default_meta_tag ) {
			$this->addDefaultMetaTag( $default_meta_tag );
		}
	}

	/**
	 *
	 * @param Mvc_Site_LocalizedData_MetaTag_Interface $default_meta_tag
	 */
	public function addDefaultMetaTag( Mvc_Site_LocalizedData_MetaTag_Interface $default_meta_tag ): void
	{
		$this->default_meta_tags[] = $default_meta_tag;
	}

	/**
	 *
	 * @param int $index
	 */
	public function removeDefaultMetaTag( int $index ): void
	{
		unset( $this->default_meta_tags[$index] );
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		$data = get_object_vars( $this );
		foreach( $data as $k => $v ) {
			if( $k[0] == '_' ) {
				unset( $data[$k] );
			}
		}
		$data['default_meta_tags'] = [];

		foreach( $this->default_meta_tags as $meta_tag ) {
			$data['default_meta_tags'][] = $meta_tag->toArray();
		}


		return $data;
	}

	/**
	 *
	 */
	public function __wakeup()
	{
		foreach( $this->default_meta_tags as $mt ) {
			$mt->setLocalizedData( $this );
		}
	}
}