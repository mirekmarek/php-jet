<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	#[Config_Definition(
		type: Config::TYPE_STRING,
		description: 'PDO driver',
		default_value: 'mysql',
		is_required: true,
		form_field_type: Form::TYPE_SELECT,
		form_field_get_select_options_callback: [
			self::class,
			'getDrivers'
		],
		form_field_label: 'Driver',
		form_field_error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please select driver',
			Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => 'Please select driver'
		]
	)]
	protected string $driver = '';


	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: true,
		form_field_label: 'DSN',
		form_field_error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter connection DSN'
		]
	)]
	protected string $DSN = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		form_field_label: 'Username',
		type: Config::TYPE_STRING,
		is_required: false
	)]
	protected string $username = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		form_field_type: Form::TYPE_PASSWORD,
		form_field_label: 'Password',
		type: Config::TYPE_STRING,
		is_required: false
	)]
	protected string $password = '';

	/**
	 * @return array
	 */
	public static function getDrivers(): array
	{
		$drivers = PDO::getAvailableDrivers();

		return array_combine( $drivers, $drivers );
	}

	/**
	 *
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername( string $username ): void
	{
		$this->username = $username;
	}

	/**
	 *
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword( string $password ): void
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getDsn(): string
	{
		return $this->driver . ':' . $this->DSN;
	}

	/**
	 * @param string $DSN
	 */
	public function setDSN( string $DSN ): void
	{
		$this->DSN = $DSN;
	}

}