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
interface Mvc_Site_LocalizedData_Interface {


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
	public function setIsActive($is_active);


	/**
	 * @return string
	 */
	public function getTitle();

	/**
	 * @param $title
	 */
	public function setTitle($title);

	/**
	 * @return string
	 */
	public function getDefaultHeadersSuffix();

	/**
	 * @param string $default_headers_suffix
	 */
	public function setDefaultHeadersSuffix($default_headers_suffix);

	/**
	 * @return string
	 */
	public function getDefaultBodyPrefix();

	/**
	 * @param string $default_body_prefix
	 */
	public function setDefaultBodyPrefix($default_body_prefix);

	/**
	 * @return string
	 */
	public function getDefaultBodySuffix();

	/**
	 * @param string $default_body_suffix
	 */
	public function setDefaultBodySuffix($default_body_suffix);

	/**
	 * @return Mvc_Site_LocalizedData_URL_Interface[]|DataModel_Related_1toN
	 */
	public function getURLs();

	/**
	 * @param Mvc_Site_LocalizedData_URL_Interface[]|string[] $URLs
	 */
	public function setURLs($URLs);

	/**
	 * @param Mvc_Site_LocalizedData_URL_Interface|string $URL
	 */
	public function addURL( $URL );

	/**
	 * @param Mvc_Site_LocalizedData_URL_Interface|string $URL
	 */
	public function removeURL( $URL );

	/**
	 * @param Mvc_Site_LocalizedData_URL_Interface|string $URL
	 * @return bool
	 */
	public function setDefaultURL( $URL );

	/**
	 * @return Mvc_Site_LocalizedData_URL_Interface
	 */
	public function getDefaultURL();


	/**
	 * @param Mvc_Site_LocalizedData_URL_Interface|string $URL
	 * @return bool
	 */
	public function setDefaultSslURL( $URL );

	/**
	 * @return Mvc_Site_LocalizedData_URL_Interface
	 */
	public function getDefaultSslURL();

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
	public function setDefaultMetaTags($default_meta_tags);

}