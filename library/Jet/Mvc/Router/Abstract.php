<?php
/**
 *
 *
 *
 * System router abstract class
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

abstract class Mvc_Router_Abstract extends BaseObject {



	/**
	 *
	 *
	 * @abstract
	 * @param string $request_URL
	 *
	 * @throws Mvc_Router_Exception
	 */
	abstract public function initialize( $request_URL );


	/**
	 * @abstract
	 *
	 * @param string $public_file_name
	 */
	abstract public function setIsFile( $public_file_name );

	/**
	 * @abstract
	 *
	 * @return bool
	 */
	abstract public function getIsFile();

    /**
     * @return string
     */
    abstract public function getFileName();


	/**
	 * Sets the request is unknown page
	 *
	 * @abstract
	 */
	abstract public function setIs404();


	/**
	 * Returns true is request is unknown page.
	 *
	 * @abstract
	 *
	 * @return bool
	 */
	abstract public function getIs404();

	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function getIsRedirect();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getRedirectTargetURL();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getRedirectType();

	/**
	 * Sets the redirect. Redirection is not performed immediately.
	 *
	 * @abstract
	 * @param string $target_URL
	 * @param string $http_code (optional), options: temporary, permanent, default: Mvc_Router::REDIRECT_TYPE_TEMPORARY
	 */
	abstract public function setIsRedirect( $target_URL, $http_code=null );


	/**
	 * Redirect if needed
	 * @abstract
	 */
	abstract public function handleRedirect();

	/**
	 * @return bool
	 */
	abstract public function getLoginRequired();

	/**
	 * @param bool $login_required
	 */
	abstract public function setLoginRequired($login_required);

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getRequestURL();

	/**
	 * @return Http_URL
	 */
	abstract public function getParsedRequestURL();

	/**
	 * @abstract
	 * @return array
	 */
	abstract public function getPathFragments();

	/**
	 * @return array
	 */
	abstract public function shiftPathFragments();


	/**
	 * @param string $template  (example: 'page:%VAL%' )
	 * @param mixed $default_value
	 * @param int $fragment_index (optional, default: 0)
	 *
	 * @return int
	 */
	abstract public function parsePathFragmentIntValue( $template, $default_value=null, $fragment_index=0 );

	/**
	 * @param string $template
	 * @param string $fragment_index
	 * @param string $reg_exp_part
	 *
	 * @return mixed
	 * @throws Exception
	 */
	abstract public function parsePathFragmentValue( $template, $fragment_index, $reg_exp_part );

	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function getIsSSLRequest();

}