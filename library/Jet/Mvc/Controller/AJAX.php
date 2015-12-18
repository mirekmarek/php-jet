<?php
/**
 *
 *
 *
 * AJAX controller class
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Controller
 */
namespace Jet;

abstract class Mvc_Controller_AJAX extends Mvc_Controller_Abstract {


	const ERR_CODE_AUTHORIZATION_REQUIRED = 'AuthorizationRequired';
	const ERR_CODE_ACCESS_DENIED = 'AccessDenied';

	/**
	 *
	 * Format:
	 *
	 * error_code => array(http_error_code, error_message)
	 *
	 * Example:
	 *
	 * <code>
	 * const ERROR_CODE_OBJECT_NOT_FOUND = 'ObjectNotFound';
	 *
	 * protected static $errors = array(
	 *      self::ERROR_CODE_OBJECT_NOT_FOUND => array(self::HTTP_NOT_FOUND, 'Object not found')
	 * );
	 * </code>
	 *
	 * @var array
	 */
	protected static $errors = [
		self::ERR_CODE_AUTHORIZATION_REQUIRED => [Http_Headers::CODE_401_UNAUTHORIZED, 'Access denied! Authorization required! '],
		self::ERR_CODE_ACCESS_DENIED => [Http_Headers::CODE_401_UNAUTHORIZED, 'Access denied! Insufficient permissions! '],
	];

    /**
     * @param Mvc_Page_Content_Abstract $page_content
     * @return bool
     */
    public function parseRequestURL( Mvc_Page_Content_Abstract $page_content) {

        $router = Mvc::getCurrentRouter();

        $path_fragments = $router->getPathFragments();

        $module_name =  $path_fragments[0];

        $path_fragments = $router->shiftPathFragments();


        if(!$this->getServiceRequestAllowed( $module_name )) {
            return false;
        }

        $controller_action = '';
        if($path_fragments){
            $controller_action = $path_fragments[0];
        }

        $path_fragments = $router->shiftPathFragments();


        $page_content->setModuleName($module_name);
        $page_content->setControllerAction($controller_action);
        $page_content->setControllerActionParameters($path_fragments);
        $page_content->setIsDynamic(true);

        return true;

    }

    /**
     * @param string $module_name
     *
     * @return bool
     */
    public function getServiceRequestAllowed( $module_name ) {
        if(!Application_Modules::getModuleIsActivated($module_name)) {
            return false;
        }

        $page = Mvc::getCurrentPage();

        if($page->getIsAdminUI()) {
            return true;
        }

        foreach( $page->getContents() as $content ) {
            if($content->getModuleName()==$module_name) {
                return true;
            }
        }

        return false;
    }

	/**
	 * @param string $module_action
	 * @param string $controller_action
	 * @param array $action_parameters
	 */
	public function responseAclAccessDenied( $module_action, $controller_action, $action_parameters ) {
		if(!Auth::getCurrentUser()) {
			$this->responseError( self::ERR_CODE_AUTHORIZATION_REQUIRED );
		} else {
			$this->responseError( self::ERR_CODE_ACCESS_DENIED , [
				'module_action' => $module_action,
				'controller_action' => $controller_action
			]);
		}
	}

	/**
	 * @param string|int $code
	 * @param mixed  $data
	 * @throws Mvc_Controller_Exception
	 */
	public function responseError( $code, $data=null ) {
		if(!isset(static::$errors[$code])) {
			throw new Mvc_Controller_Exception(
				'AJAX Error (code:'.$code.') is not specified! Please specify the error. Add '.get_class($this).'::$errors['.$code.'] entry.  ',
				Mvc_Controller_Exception::CODE_INVALID_RESPONSE_CODE
			);
		}

		list($http_code, $error_message) = static::$errors[$code];

		$error_code = get_class($this).':'.$code;
		$error = [
			'error_code' => $error_code,
			'error_msg' => $error_message
		];

		if($data) {
			$error['error_data'] = $data;
		}

		$this->_response(json_encode( $error ), [], $http_code, $error_message);
	}

	/**
	 * @param string $response_text
	 * @param array $http_headers
	 * @param int $http_code
	 * @param string $http_message
	 */
	protected function _response($response_text, array $http_headers= [], $http_code = 200, $http_message='OK' ) {
		header( 'HTTP/1.1 '.$http_code.' '.$http_message );
		header('Content-type:text/json;charset=UTF-8');

		foreach( $http_headers as $header=>$header_value ) {
			header( $header.': '.$header_value );
		}

		Debug_Profiler::setOutputIsJSON(true);

		echo $response_text;
		Application::end();

	}

	/**
	 * Renders the output and adds it into the default layout.
	 * @see Mvc/readme.txt
	 *
	 * @param string $script
	 * @param string $position (optional, default: by current dispatcher queue item)
	 * @param bool $position_required (optional, default: by current dispatcher queue item)
	 * @param int $position_order (optional, default: by current dispatcher queue item)
	 */
	public function render(
		$script,
		$position = null,
		$position_required = null,
		$position_order = null
	) {
		Debug_Profiler::setOutputIsXML( true );

        Mvc::getCurrentPage()->renderView(
			$this->view,
			$script,
			$position,
			$position_required,
			$position_order
		);

		return;
	}


}