<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

class Installer_Step_CreateDB_Controller extends Installer_Step_Controller {


	public function main() {
		if(Http_Request::POST()->exists('go')) {
			$this->installer->goNext();
		}

		$classes = [
            JET_AUTH_ROLE_CLASS,
            JET_AUTH_ROLE_PRIVILEGE_CLASS,
            JET_AUTH_USER_CLASS,
            JET_AUTH_USER_ROLES_CLASS,
		];

		$result = [];
		$OK = true;

		foreach($classes as $class ) {
			$result[$class] = true;

			try {
				DataModel::helper_create( $class );
			} catch(Exception $e) {
				$result[$class] = $e->getMessage();
				$OK = false;
			}

		}

		$this->view->setVar('result', $result);
		$this->view->setVar('OK', $OK);

		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Create database', [], 'CreateDB');
	}
}
