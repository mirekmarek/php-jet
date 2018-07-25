<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Db_Backend_PDO_Config extends Db_Backend_Config
{
	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:description = 'PDO driver'
	 * @JetConfig:default_value = 'mysql'
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['this', 'getDrivers']
	 * @JetConfig:form_field_label = 'Driver'
	 * @JetConfig:form_field_error_messages = [Form_Field::ERROR_CODE_EMPTY=>'Please select driver', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select driver']
	 *
	 * @var string
	 */
	protected $driver;


	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = ''
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'DSN'
	 * @JetConfig:form_field_error_messages = [Form_Field::ERROR_CODE_EMPTY=>'Please enter connection DSN']
	 *
	 * @var string
	 */
	protected $DSN;

	/**
	 * @JetConfig:form_field_label = 'Username'
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = null
	 * @JetConfig:is_required = false
	 *
	 * @var string
	 */
	protected $username;

	/**
	 * @JetConfig:form_field_type = Form::TYPE_PASSWORD
	 * @JetConfig:form_field_label = 'Password'
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = null
	 * @JetConfig:is_required = false
	 *
	 * @var string
	 */
	protected $password;

	/**
	 * @return array
	 */
	public static function getDrivers()
	{
		$drivers = \PDO::getAvailableDrivers();

		return array_combine( $drivers, $drivers );
	}

	/**
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername( $username )
	{
		$this->username = $username;
	}

	/**
	 * Get authorization password
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword( $password )
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getDsn()
	{
		return $this->driver.':'.$this->DSN;
	}

	/**
	 * @param string $DSN
	 */
	public function setDSN( $DSN )
	{
		$this->DSN = $DSN;
	}

}