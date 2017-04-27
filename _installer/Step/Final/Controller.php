<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\Data_DateTime;
use Jet\IO_Dir;
use Jet\IO_Dir_Exception;
use Jet\IO_File;
use Jet\IO_File_Exception;

/**
 * 
 */
class Installer_Step_Final_Controller extends Installer_Step_Controller {

	/**
	 * @var string
	 */
	protected $label = 'Done';

	/**
	 * @var string
	 */
	protected $error_config_install = '';
	/**
	 * @var string
	 */
	protected $error_dictionaries_install = '';
	/**
	 * @var string
	 */
	protected $error_symptom_create = '';

	/**
	 * @var string
	 */
	protected $install_symptom_file_path = '';

	/**
	 * @var string
	 */
	protected $config_file_source_path = '';

	/**
	 * @var string
	 */
	protected $config_file_target_path = '';

	/**
	 *
	 */
	public function main() {

		$OK = true;

		$this->install_symptom_file_path = JET_DATA_PATH.'installed.txt';

		$this->config_file_source_path = Installer::getTmpConfigFilePath();
		$this->config_file_target_path = JET_CONFIG_PATH.'_common/'.JET_APPLICATION_CONFIGURATION_NAME.'.php';

		if(!$this->installConfig()) {
			$OK = false;
		}

		if(!$this->installDictionaries()) {
			$OK = false;
		}

		if($OK) {
			try {
				IO_File::write( $this->install_symptom_file_path, Data_DateTime::now()->toString() );
			} catch(IO_File_Exception $e) {
				$OK = false;
				$this->error_symptom_create = $e->getMessage();
			}
		}

		$this->view->setVar('controller', $this);

		if($OK) {
			$this->render('done');
		} else {
			$this->render('error');
		}
	}

	/**
	 *
	 * @return bool
	 */
	public function installConfig() {

		try {
			IO_File::copy($this->config_file_source_path, $this->config_file_target_path);
		} catch(IO_File_Exception $e) {
			$this->error_config_install = $e->getMessage();

			return false;
		}

		return true;
	}

	/**
	 *
	 * @return bool
	 */
	public function installDictionaries() {

		foreach( Installer::getSelectedLocales() as $locale ) {

			$source = JET_APP_INSTALLER_DATA_PATH.'dictionaries/'.$locale;
			$target = JET_TRANSLATOR_DICTIONARIES_BASE_PATH_PATH.$locale;

			try {
				if(IO_Dir::exists($target)) {
					continue;
				}
			} catch(IO_Dir_Exception $e) {
				$this->error_dictionaries_install = $e->getMessage();

				return false;
			}

			try {
				IO_Dir::copy(
					$source,
					$target
				);
			} catch(IO_Dir_Exception $e) {
				$this->error_dictionaries_install = $e->getMessage();

				return false;
			}

		}

		return true;
	}

	/**
	 * @return string
	 */
	public function getErrorConfigInstall()
	{
		return $this->error_config_install;
	}

	/**
	 * @return string
	 */
	public function getErrorDictionariesInstall()
	{
		return $this->error_dictionaries_install;
	}

	/**
	 * @return string
	 */
	public function getErrorSymptomCreate()
	{
		return $this->error_symptom_create;
	}

	/**
	 * @return string
	 */
	public function getInstallSymptomFilePath()
	{
		return $this->install_symptom_file_path;
	}

	/**
	 * @return string
	 */
	public function getConfigFileSourcePath()
	{
		return $this->config_file_source_path;
	}

	/**
	 * @return string
	 */
	public function getConfigFileTargetPath()
	{
		return $this->config_file_target_path;
	}



}
