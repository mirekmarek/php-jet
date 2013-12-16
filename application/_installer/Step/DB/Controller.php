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



	public function main() {
		$main_config = new Db_Config(true);

		$GET = Http_Request::GET();

		if($GET->exists("add_connection")) {
			$connection_config = Db_Factory::getAdapterConfigInstance($main_config, $_SESSION["nc_type"]);
			//$connection_config->setSoftMode(true);
			//$connection_config->parseData();

			$form = $connection_config->getCommonForm();

			if( $connection_config->catchForm($form) ) {
				$main_config->addConnection($_SESSION["nc_name"], $connection_config);
				$main_config->save();
				Http_Headers::movedTemporary("?test_connection=".$_SESSION["nc_name"]);
			}

			$this->view->setVar("form", $form);
			$this->view->setVar("connection_name", $_SESSION["nc_name"]);
			$this->view->setVar("config", $connection_config);

			$this->render("add-connection");

		} else
		if( ( $edit_connection_name = $GET->getString("edit_connection") ) ) {
			$connection_config = $main_config->getConnection($edit_connection_name);
			//$connection_config->setSoftMode(true);
			//$connection_config->parseData();

			$form = $connection_config->getCommonForm();

			if( $connection_config->catchForm($form) ) {
				$main_config->addConnection($edit_connection_name, $connection_config);
				$main_config->save();
				Http_Headers::movedTemporary("?test_connection=".$edit_connection_name);
			}

			$this->view->setVar("form", $form);
			$this->view->setVar("connection_name", $edit_connection_name);
			$this->view->setVar("config", $connection_config);

			$this->render("edit-connection");

		} else
		if( ( $delete_connection_name = $GET->getString("delete_connection") ) ) {
			$connection_config = $main_config->getConnection($delete_connection_name);
			//$connection_config->setSoftMode(true);
			//$connection_config->parseData();

			$form = $connection_config->getCommonForm();

			if($form->catchValues()) {
				$main_config->deleteConnection($delete_connection_name);
				$main_config->save();
				Http_Headers::movedTemporary("?");

			}

			$this->view->setVar("form", $form);
			$this->view->setVar("connection_name", $delete_connection_name);
			$this->view->setVar("config", $connection_config);

			$this->render("delete-connection");

		} else
		if( ( $test_connection_name = $GET->getString("test_connection") ) ) {
			$connection_config = $main_config->getConnection($test_connection_name);
			//$connection_config->setSoftMode(true);
			//$connection_config->parseData();

			$form = $connection_config->getCommonForm();

			if($form->catchValues()) {
				Http_Headers::movedTemporary("?");
			}

			$OK = true;
			$error_message = "";
			try {
				Db::get($test_connection_name);
			} catch(Exception $e) {
				$error_message = $e->getMessage();
				$OK = false;
			}


			$this->view->setVar("form", $form);
			$this->view->setVar("connection_name", $test_connection_name);
			$this->view->setVar("config", $connection_config);
			$this->view->setVar("OK", $OK);
			$this->view->setVar("error_message", $error_message);


			$this->render("test-connection");

		}
		else {
			$add_form = new Form("connection_add",
					array(
						Form_Factory::field("Input","name", "New connection name: "),
						Form_Factory::field("Select","type", "Select new connection type: "),
					)
				);

			$add_form->getField("name")->setIsRequired(true);
			$add_form->getField("name")->setDefaultValue("default");
			$add_form->getField("type")->setSelectOptions( $main_config->getAvailableAdapterTypes() );
			$add_form->getField("type")->setIsRequired(true);

			if(
				$add_form->catchValues() &&
				$add_form->validateValues()
			) {

				$d = $add_form->getValues();
				if($main_config->getConnection($d["name"])) {
					$add_form->setCommonErrorMessage(
						Tr::_("Connection '%CONNECTION%' already exists!", array("CONNECTION"=>$d["name"]))
					);
				} else {
					$_SESSION["nc_type"] = $d["type"];
					$_SESSION["nc_name"] = $d["name"];

					Http_Headers::movedTemporary("?add_connection");
				}
			}

			$this->view->setVar("add_form", $add_form);



			$form = $main_config->getCommonForm();

			if( $main_config->catchForm($form) ) {
				$main_config->save();

				$this->installer->goNext();
			}

			$this->view->setVar("config", $main_config);
			$this->view->setVar("form", $form);

			$this->render("add-connection-start");

			if(!$main_config->getConnections()) {
				$this->render("empty-warning");

			} else {
				$this->render("default");
			}
		}

	}

	public function getLabel() {
		return Tr::_("DB connections configuration", array(), "DB");
	}
}
