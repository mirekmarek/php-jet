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
interface MVC_Base_LocalizedData_Interface
{

	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 * @param array $data
	 *
	 * @return MVC_Base_LocalizedData_Interface
	 */
	public static function _createByData( MVC_Base_Interface $base, Locale $locale, array $data ) : MVC_Base_LocalizedData_Interface;

	/**
	 * @return MVC_Base_Interface
	 */
	public function getBase(): MVC_Base_Interface;

	/**
	 * @param MVC_Base_Interface $base
	 */
	public function setBase( MVC_Base_Interface $base ): void;


	/**
	 * @return Locale
	 */
	public function getLocale(): Locale;

	/**
	 * @param Locale $locale
	 *
	 */
	public function setLocale( Locale $locale ): void;

	/**
	 * @return bool
	 */
	public function getIsActive(): bool;

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( bool $is_active ): void;


	/**
	 * @return string
	 */
	public function getTitle(): string;

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void;

	/**
	 * @return array
	 */
	public function getURLs(): array;

	/**
	 * @param array $URLs
	 */
	public function setURLs( array $URLs ): void;

	/**
	 * @return string
	 */
	public function getDefaultURL(): string;

	/**
	 * @return bool
	 */
	public function getSSLRequired(): bool;

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( bool $SSL_required ): void;


	/**
	 *
	 * @return MVC_Base_LocalizedData_MetaTag_Interface[]
	 */
	public function getDefaultMetaTags(): array;

	/**
	 *
	 * @param MVC_Base_LocalizedData_MetaTag_Interface $default_meta_tag
	 */
	public function addDefaultMetaTag( MVC_Base_LocalizedData_MetaTag_Interface $default_meta_tag ): void;

	/**
	 *
	 * @param int $index
	 */
	public function removeDefaultMetaTag( int $index ): void;

	/**
	 *
	 * @param MVC_Base_LocalizedData_MetaTag_Interface[] $default_meta_tags
	 */
	public function setDefaultMetaTags( array $default_meta_tags ): void;


	/**
	 * @return array
	 */
	public function getParameters(): array;

	/**
	 * @param array $parameters
	 */
	public function setParameters( array $parameters ): void;

	/**
	 * @param string $key
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	public function getParameter( string $key, mixed $default_value = null ): mixed;

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setParameter( string $key, mixed $value ): void;
	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function parameterExists( string $key ): bool;

	/**
	 * @return array
	 */
	public function toArray(): array;

}