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

		$classes = array(
            /*
			Factory::getClassName( Mvc_Factory::DEFAULT_PAGE_CLASS ),
			Factory::getClassName( Mvc_Factory::DEFAULT_PAGE_META_TAG_CLASS ),
			Factory::getClassName( Mvc_Factory::DEFAULT_PAGE_CONTENT_CLASS ),

			Factory::getClassName( Mvc_Factory::DEFAULT_SITE_CLASS ),
			Factory::getClassName( Mvc_Factory::DEFAULT_LOCALIZED_SITE_CLASS ),
			Factory::getClassName( Mvc_Factory::DEFAULT_LOCALIZED_SITE_META_TAG_CLASS ),
			Factory::getClassName( Mvc_Factory::DEFAULT_LOCALIZED_SITE_URL_CLASS ),
            */

			Factory::getClassName( Auth_Factory::DEFAULT_ROLE_CLASS ),
			Factory::getClassName( Auth_Factory::DEFAULT_PRIVILEGE_CLASS ),
			Factory::getClassName( Auth_Factory::DEFAULT_USER_CLASS ),
			Factory::getClassName( Auth_Factory::DEFAULT_USER_ROLES_CLASS ),
		);

		$result = array();
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
		return Tr::_('Create database', array(), 'CreateDB');
	}
}
