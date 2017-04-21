<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

use Jet\Form;
use Jet\Form_Field_Select;
use Jet\Db_Factory;
use Jet\Db_Config;
use Jet\Db_Connection_Config_Abstract;
use Jet\DataModel_Config;
use Jet\DataModel_Backend_MySQL_Config;
use Jet\DataModel_Backend_SQLite_Config;
use Jet\DataModel_Factory;
use Jet\Mvc_Site;


class Installer_Step_SelectDbType_Controller extends Installer_Step_Controller {

	/**
	 * @var string
	 */
	protected $label = 'Select Database type';

	/**
	 * @var array
	 */
	protected static $database_types = [
		'mysql' => 'MySQL / MariaDB',
		'sqlite' => 'SQLite',
	];

	/**
	 * @return bool
	 */
	public function getIsAvailable()
	{
		return count(Mvc_Site::getList() )==0;
	}

	/**
	 * @return array
	 */
	public static function getDbTypes() {
		$drivers = Db_Connection_Config_Abstract::getPDODrivers();

		foreach(static::$database_types as $type=>$label) {
			if(!in_array($type, $drivers)) {
				unset(static::$database_types[$type]);
			}

		}

		return static::$database_types;
	}

	/**
	 * @return string
	 */
	public static function getSelectedDbType() {
		$session = Installer::getSession();

		if(!$session->getValueExists('db_type')) {
			list($default) = array_keys(static::getDbTypes());

			$session->setValue('db_type', $default);
		}

		return $session->getValue('db_type');
	}

	/**
	 * @param string $type
	 */
	public static function setSelectedDbType($type) {
		if(!isset(static::getDbTypes()[$type])) {
			return;
		}

		Installer::getSession()->setValue('db_type', $type);

	}


	/**
	 *
	 */
	public function main() {



		$db_type_field = new Form_Field_Select('type', 'Please database type: ');
		$db_type_field->setSelectOptions( static::getDbTypes() );
		$db_type_field->setDefaultValue(static::getSelectedDbType());
		$db_type_field->setIsRequired(true);

		$db_type_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_INVALID_VALUE=>'Please database type',
			Form_Field_Select::ERROR_CODE_EMPTY=>'Please database type'
		]);

		$select_db_type_form = new Form('select_db_type_form',
			[
				$db_type_field,
			]
		);



		if($select_db_type_form->catchValues() && $select_db_type_form->validateValues()) {
			static::setSelectedDbType($db_type_field->getValue());

			$data_model_config = new DataModel_Config(true);
			$db_config = new Db_Config(true);

			$data_model_backend_config = null;

			switch(static::getSelectedDbType()) {
				case 'mysql':
					$connection_config = Db_Factory::getConnectionConfigInstance([], $db_config);
					$connection_config->setName('default');
					$connection_config->setDriver('mysql');
					$connection_config->setUsername('');
					$connection_config->setPassword('');
					$connection_config->setDSN('host=localhost;port=3306;dbname=;charset=utf8');

					$db_config->addConnection( 'default', $connection_config);
					$db_config->save();


					$data_model_config->setBackendType('MySQL');
					/**
					 * @var DataModel_Backend_MySQL_Config $data_model_backend_config
					 */
					$data_model_backend_config = DataModel_Factory::getBackendConfigInstance($data_model_config->getBackendType(), true);
					$data_model_backend_config->setConnectionRead('default');
					$data_model_backend_config->setConnectionWrite('default');



					break;
				case 'sqlite':
					$data_path = JET_DATA_PATH;
					$data_file_name = 'database';

					$connection_config = Db_Factory::getConnectionConfigInstance([], $db_config);
					$connection_config->setName('default');
					$connection_config->setDriver('sqlite');
					$connection_config->setUsername('');
					$connection_config->setPassword('');
					$connection_config->setDSN($data_path.$data_file_name.'.sq3');

					$db_config->addConnection( 'default', $connection_config);
					$db_config->save();


					$data_model_config->setBackendType('SQLite');
					/**
					 * @var DataModel_Backend_SQLite_Config $data_model_backend_config
					 */
					$data_model_backend_config = DataModel_Factory::getBackendConfigInstance($data_model_config->getBackendType(), true);
					$data_model_backend_config->setDirectoryPath($data_path);
					$data_model_backend_config->setDatabaseName($data_file_name);

					break;
			}

			$data_model_config->save();
			$data_model_backend_config->save();

			Installer::goToNext();
		}

		$this->view->setVar('form', $select_db_type_form);


		$this->render('default');

	}

	/**
	 * @return array|bool
	 */
	public function getStepsAfter()
	{
		if(static::getSelectedDbType()=='mysql') {
			return ['ConfigureDb'];
		}

		return false;
	}
}
