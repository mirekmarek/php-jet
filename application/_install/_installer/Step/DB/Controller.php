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

class Installer_Step_DB_Controller extends Installer_Step_Controller {

	/**
	 * @var Db_Config
	 */
	protected $main_config;


	public function main() {
		if(Http_Request::POST()->exists('go')) {
			$this->installer->goNext();
		}

		$this->main_config = new Db_Config(true);

		$GET = Http_Request::GET();

		if( $GET->exists('add_connection') ) {
			$this->_addConnection();
		} else
		if( ( $edit_connection_name = $GET->getString('edit_connection') ) ) {
			$this->_editConnection( $edit_connection_name );
		} else
		if( ( $delete_connection_name = $GET->getString('delete_connection') ) ) {
			$this->_deleteConnection( $delete_connection_name );
		} else
		if( ( $test_connection_name = $GET->getString('test_connection') ) ) {
			$this->_testConnection( $test_connection_name );
		}
		else {

			if(!$this->main_config->getConnectionsList()) {
				$this->_addConnection();
			}

			$form = $this->main_config->getCommonForm();

			if( $this->main_config->catchForm($form) ) {
				$this->main_config->save();

				$this->installer->goNext();
			}

			$this->view->setVar('config', $this->main_config);
			$this->view->setVar('form', $form);

			$this->render('default');
		}

	}

	/**
	 *
	 */
	protected function _addConnection() {
		$connection_config = Db_Factory::getConnectionConfigInstance([], $this->main_config);

		$form = $connection_config->getCommonForm();

		if( $connection_config->catchForm($form) ) {
			$this->main_config->addConnection( $connection_config->getName(), $connection_config);
			$this->main_config->save();
			Http_Headers::movedTemporary('?test_connection='.$connection_config->getName());
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('config', $connection_config);

		$this->render('add-connection');

	}

	/**
	 * @param string $delete_connection_name
	 */
	protected function _deleteConnection( $delete_connection_name) {
		$connection_config = $this->main_config->getConnection($delete_connection_name);
		if(!$connection_config) {
			return;
		}

		$connection_config = $this->main_config->getConnection($delete_connection_name);
		//$connection_config->setSoftMode(true);
		//$connection_config->parseData();

		$form = $connection_config->getCommonForm();

		if($form->catchValues()) {
			$this->main_config->deleteConnection($delete_connection_name);
			$this->main_config->save();
			Http_Headers::movedTemporary('?');

		}

		$this->view->setVar('form', $form);
		$this->view->setVar('connection_name', $delete_connection_name);
		$this->view->setVar('config', $connection_config);

		$this->render('delete-connection');

	}

	/**
	 * @param string $edit_connection_name
	 */
	protected function _editConnection( $edit_connection_name) {
		$connection_config = $this->main_config->getConnection($edit_connection_name);
		if(!$connection_config) {
			return;
		}

		$form = $connection_config->getCommonForm();

		if( $connection_config->catchForm($form) ) {
			$this->main_config->addConnection($edit_connection_name, $connection_config);
			$this->main_config->save();
			Http_Headers::movedTemporary('?test_connection='.$edit_connection_name);
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('connection_name', $edit_connection_name);
		$this->view->setVar('config', $connection_config);

		$this->render('edit-connection');
	}

	/**
	 * @param string $test_connection_name
	 */
	protected function _testConnection( $test_connection_name) {
		$connection_config = $this->main_config->getConnection($test_connection_name);
		if(!$connection_config) {
			return;
		}

		$form = $connection_config->getCommonForm();

		if($form->catchValues()) {
			Http_Headers::movedTemporary('?');
		}

		$OK = true;
		$error_message = '';
		try {
			Db::get($test_connection_name);
		} catch(\Exception $e) {
			$error_message = $e->getMessage();
			$OK = false;
		}


		$this->view->setVar('form', $form);
		$this->view->setVar('connection_name', $test_connection_name);
		$this->view->setVar('config', $connection_config);
		$this->view->setVar('OK', $OK);
		$this->view->setVar('error_message', $error_message);


		$this->render('test-connection');

	}

	public function getLabel() {
		return Tr::_('DB (PDO) connections', [], 'DB');
	}
}
