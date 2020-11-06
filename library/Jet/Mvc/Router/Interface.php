<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
interface Mvc_Router_Interface 
{

	/**
	 *
	 *
	 * @param string $request_URL
	 *
	 * @throws Mvc_Router_Exception
	 */
	public function resolve( $request_URL );

	/**
	 * @return bool
	 */
	public function getSetMvcState();

	/**
	 * @param bool $set_mvc_state
	 */
	public function setSetMvcState( $set_mvc_state );

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public function getSite();

	/**
	 * @return Locale
	 */
	public function getLocale();

	/**
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getPage();


	/**
	 * @return bool
	 */
	public function getIsFile();

	/**
	 * @param string $file_path
	 */
	public function setIsFile( $file_path );

	/**
	 * @return string
	 */
	public function getFilePath();

	/**
	 *
	 * @return bool
	 */
	public function getIs404();

	/**
	 *
	 * @return bool
	 */
	public function getIsRedirect();

	/**
	 *
	 * @return string
	 */
	public function getRedirectTargetURL();

	/**
	 *
	 * @return string
	 */
	public function getRedirectType();

	/**
	 * @return bool
	 */
	public function getLoginRequired();

	/**
	 * @return string
	 */
	public function getPath();


	/**
	 * @return string
	 */
	public function getUsedPath();

	/**
	 * @param string $used_path
	 */
	public function setUsedPath( $used_path );

}