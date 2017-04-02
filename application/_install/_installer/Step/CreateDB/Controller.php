<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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
            'JetExampleApp\Auth_Administrator_Role',
			'JetExampleApp\Auth_Administrator_Role_Privilege',
			'JetExampleApp\Auth_Administrator_User',
			'JetExampleApp\Auth_Administrator_User_Roles',

			'JetExampleApp\Auth_Visitor_Role',
			'JetExampleApp\Auth_Visitor_Role_Privilege',
			'JetExampleApp\Auth_Visitor_User',
			'JetExampleApp\Auth_Visitor_User_Roles',
		];

		$result = [];
		$OK = true;

		foreach($classes as $class ) {
			$result[$class] = true;

			try {
				DataModel_Helper::create( $class );
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
