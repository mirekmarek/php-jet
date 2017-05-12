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
	 *
	 *
	 * @param string $request_URL
	 *
	 * @throws Mvc_Router_Exception
	 */
	public function initialize( $request_URL );


	/**
	 * @param string $public_file_name
	 */
	public function setIsFile( $public_file_name );

	/**
	 * @return bool
	 */
	public function getIsFile();

	/**
	 * @return string
	 */
	public function getFileName();


	/**
	 *
	 */
	public function setIs404();


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
	 *
	 * @param string $target_URL
	 * @param string $http_code (optional), options: temporary, permanent, default: Mvc_Router::REDIRECT_TYPE_TEMPORARY
	 */
	public function setIsRedirect( $target_URL, $http_code = null );


	/**
	 */
	public function handleRedirect();

	/**
	 * @return bool
	 */
	public function getLoginRequired();

	/**
	 * @param bool $login_required
	 */
	public function setLoginRequired( $login_required );

	/**
	 * @return string
	 */
	public function getRequestURL();

	/**
	 * @return Http_URL
	 */
	public function getParsedRequestURL();

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

	/**
	 * @return bool
	 */
	public function getIsSSLRequest();

}