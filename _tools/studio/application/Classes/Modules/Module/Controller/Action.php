<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;

class Modules_Module_Controller_Action extends BaseObject
{
	/**
	 * @var string
	 */
	protected $controller_action = '';

	/**
	 * @var string
	 */
	protected $ACL_action = '';


	/**
	 * @return string
	 */
	public function getControllerAction()
	{
		return $this->controller_action;
	}

	/**
	 * @param string $controller_action
	 */
	public function setControllerAction( $controller_action )
	{
		$this->controller_action = $controller_action;
	}

	/**
	 * @return string
	 */
	public function getACLAction()
	{
		return $this->ACL_action;
	}

	/**
	 * @param string $ACL_action
	 */
	public function setACLAction( $ACL_action )
	{
		$this->ACL_action = $ACL_action;
	}


}