<?php
/**
 *
 * @copyright 
 * @license  
 * @author  
 */
namespace JetApplicationModule\Test\UI;

use Jet\Http_Request;
use Jet\MVC_Controller_Default;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	/**
	 *
	 */
	public function default_Action() : void
	{
		$POST = Http_Request::POST();
		if($POST->getString('action')=='test') {
			$this->view->setVar('POST_action_data', [
				'value_1' => $POST->getString('value_1'),
				'value_2' => $POST->getInt('value_2'),
				'value_3' => $POST->getFloat('value_3'),
			]);
		}
		
		$this->output('default');
	}
}