<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface Mvc_Site_LocalizedData_URL_Interface
 * @package Jet
 */
interface Mvc_Site_LocalizedData_URL_Interface {


	/**
	 * @param string $site_id
	 */
	public function setSiteId($site_id);

	/**
	 * @return string
	 */
	public function getSiteId();


    /**
     * @param Locale $locale
     */
    public function setLocale( Locale $locale );

    /**
     * @return Locale
     */
    public function getLocale();

	/**
	 * @return string
	 */
	public function __toString();

	/**
	 * @return string
	 */
	public function toString();

	/**
	 * @return string
	 */
	public function getAsNonSchemaURL();


	/**
	 * @return string
	 */
	public function getURL();

	/**
	 * @param string $URL
	 * @throws Mvc_Site_Exception
	 */
	public function setURL($URL);

	/**
	 * @return bool
	 */
	public function getIsDefault();

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault($is_default);

	/**
	 * @return bool|string
	 */
	public function getSchemePart();

	/**
	 * @return bool|string
	 */
	public function getHostPart();

	/**
	 * @return bool|string
	 */
	public function getPostPart();

	/**
	 * @return bool|string
	 */
	public function getPathPart();

	/**
	 * @return bool
	 */
	public function getIsSSL();

	/**
	 * @param bool $is_SSL
	 */
	public function setIsSSL( $is_SSL );

}