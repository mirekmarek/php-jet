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
interface Mvc_Router_Interface 
{

	/**
	 * @param callable $after_site_resolved
	 */
	public function afterSiteResolved( callable $after_site_resolved );

	/**
	 * @param callable $after_page_resolved
	 */
	public function afterPageResolved( callable $after_page_resolved );

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
	 * @return string
	 */
	public function getRequestURL();

	/**
	 * @return Http_URL
	 */
	public function getParsedRequestURL();

	/**
	 * @return bool
	 */
	public function getIsSSLRequest();

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
	 * @return array
	 */
	public function getPathFragments();

	/**
	 * @return array
	 */
	public function shiftPathFragments();


	/**
	 * @param string $template (example: 'page:%VAL%' )
	 * @param mixed  $default_value
	 * @param int    $fragment_index (optional, default: 0)
	 *
	 * @return int
	 */
	public function parsePathFragmentIntValue( $template, $default_value = null, $fragment_index = 0 );

	/**
	 * @param string $template
	 * @param string $fragment_index
	 * @param string $reg_exp_part
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function parsePathFragmentValue( $template, $fragment_index, $reg_exp_part );

}