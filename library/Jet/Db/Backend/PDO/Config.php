<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

use PDO;

/**
 *
 */
class Db_Backend_PDO_Config extends Db_Backend_Config
{
	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type : Config::TYPE_STRING)]
	#[Config_Definition(description : 'PDO driver')]
	#[Config_Definition(default_value : 'mysql')]
	#[Config_Definition(is_required : true)]
	#[Config_Definition(form_field_type : Form::TYPE_SELECT)]
	#[Config_Definition(form_field_get_select_options_callback : [self::class, 'getDrivers'])]
	#[Config_Definition(form_field_label : 'Driver')]
	#[Config_Definition(form_field_error_messages : [Form_Field::ERROR_CODE_EMPTY=>'Please select driver', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select driver'])]
	protected string $driver = '';


	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type : Config::TYPE_STRING)]
	#[Config_Definition(default_value : '')]
	#[Config_Definition(is_required : true)]
	#[Config_Definition(form_field_label : 'DSN')]
	#[Config_Definition(form_field_error_messages : [Form_Field::ERROR_CODE_EMPTY=>'Please enter connection DSN'])]
	protected string $DSN = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(form_field_label : 'Username')]
	#[Config_Definition(type : Config::TYPE_STRING)]
	#[Config_Definition(default_value : null)]
	#[Config_Definition(is_required : false)]
	protected string $username = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(form_field_type : Form::TYPE_PASSWORD)]
	#[Config_Definition(form_field_label : 'Password')]
	#[Config_Definition(type : Config::TYPE_STRING)]
	#[Config_Definition(default_value : null)]
	#[Config_Definition(is_required : false)]
	protected string $password  ='';

	/**
	 * @return array
	 */
	public static function getDrivers() : array
	{
		$drivers = PDO::getAvailableDrivers();

		return array_combine( $drivers, $drivers );
	}

	/**
	 *
	 * @return string
	 */
	public function getUsername() : string
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername( string $username ) : void
	{
		$this->username = $username;
	}

	/**
	 *
	 * @return string
	 */
	public function getPassword() : string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword( string $password ) : void
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getDsn() : string
	{
		return $this->driver.':'.$this->DSN;
	}

	/**
	 * @param string $DSN
	 */
	public function setDSN( string $DSN ) : void
	{
		$this->DSN = $DSN;
	}

}