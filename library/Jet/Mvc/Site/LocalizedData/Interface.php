<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface Mvc_Site_LocalizedData_Interface
 * @package Jet
 */
interface Mvc_Site_LocalizedData_Interface
{


	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite();

	/**
	 * @param Mvc_Site_Interface $site
	 */
	public function setSite( $site );

	/**
	 * @return Locale
	 */
	public function getLocale();

	/**
	 * @return bool
	 */
	public function getIsActive();

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( $is_active );


	/**
	 * @return string
	 */
	public function getTitle();

	/**
	 * @param string $title
	 */
	public function setTitle( $title );

	/**
	 * @return array
	 */
	public function getURLs();

	/**
	 * @param array $URLs
	 */
	public function setURLs( array $URLs );

	/**
	 * @return string
	 */
	public function getDefaultURL();

	/**
	 * @return bool
	 */
	public function getSSLRequired();

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( $SSL_required );


	/**
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Interface[]
	 */
	public function getDefaultMetaTags();

	/**
	 *
	 * @param Mvc_Site_LocalizedData_MetaTag_Interface $default_meta_tag
	 */
	public function addDefaultMetaTag( Mvc_Site_LocalizedData_MetaTag_Interface $default_meta_tag );

	/**
	 *
	 * @param int $index
	 */
	public function removeDefaultMetaTag( $index );

	/**
	 *
	 * @param Mvc_Site_LocalizedData_MetaTag_Interface[] $default_meta_tags
	 */
	public function setDefaultMetaTags( $default_meta_tags );

}