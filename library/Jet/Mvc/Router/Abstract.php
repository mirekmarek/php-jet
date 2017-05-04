<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_Router_Abstract
 * @package Jet
 */
abstract class Mvc_Router_Abstract extends BaseObject {



	/**
	 *
	 *
	 * @param string $request_URL
	 *
	 * @throws Mvc_Router_Exception
	 */
	abstract public function initialize( $request_URL );


	/**
	 * @param string $public_file_name
	 */
	abstract public function setIsFile( $public_file_name );

	/**
	 * @return bool
	 */
	abstract public function getIsFile();

    /**
     * @return string
     */
    abstract public function getFileName();


	/**
	 *
	 */
	abstract public function setIs404();


	/**
	 *
	 * @return bool
	 */
	abstract public function getIs404();

	/**
	 *
	 * @return bool
	 */
	abstract public function getIsRedirect();

	/**
	 *
	 * @return string
	 */
	abstract public function getRedirectTargetURL();

	/**
	 *
	 * @return string
	 */
	abstract public function getRedirectType();

	/**
	 *
	 * @param string $target_URL
	 * @param string $http_code (optional), options: temporary, permanent, default: Mvc_Router::REDIRECT_TYPE_TEMPORARY
	 */
	abstract public function setIsRedirect( $target_URL, $http_code=null );


	/**
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
	 * @return string
	 */
	abstract public function getRequestURL();

	/**
	 * @return Http_URL
	 */
	abstract public function getParsedRequestURL();

	/**
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
	 * @return bool
	 */
	abstract public function getIsSSLRequest();

}