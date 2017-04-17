<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;

use Jet\Mvc_Controller_Standard;
use JetExampleApp\Application_Modules_Module_Manifest as App_Application_Modules_Module_Manifest;
use Jet\Form;
use JetUI\messages;

abstract class Mvc_Controller_AdminStandard extends Mvc_Controller_Standard
{

	/**
	 *
	 * @var App_Application_Modules_Module_Manifest
	 */
	protected $module_manifest;

	/**
	 * @var array
	 */
	protected static $action_URI = [
	];

	/**
	 * @var array
	 */
	protected static $action_regexp = [
	];


	/**
	 * @return App_Application_Modules_Module_Manifest
	 */
	public function getModuleManifest()
	{
		return $this->module_manifest;
	}


	/**
	 * @param string $action
	 * @return string
	 */
	public static function getActionURI( $action )
	{
		return static::$action_URI[$action];
	}

	/**
	 * @param string $action
	 * @return string
	 */
	public static function getActionRegexp( $action )
	{
		return static::$action_regexp[$action];
	}


	/**
	 * @param Form $form
	 * @param bool $success
	 * @param array $snippets
	 * @param bool $send_messages
	 * @param array $data
	 */
	protected function ajaxFormResponse( Form $form, $success, array $snippets=[], $send_messages=false, $data=[]  ) {

		$response = [
			'form_id' => $form->getId(),
			'result' => $success ? 'ok':'error',
			'data' => $data
		];

		if($snippets) {
			$response['snippets'] = $snippets;
		}

		if($send_messages) {
			if(!isset($response['snippets'])) {
				$response['snippets'] = [];
			}

			$response['snippets']['system-messages-area'] = implode( '', messages::get() );
		}

		$this->ajaxResponse( $response );
	}
}