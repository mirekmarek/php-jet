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
		is_required: true,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_SELECT,
		label: 'Driver',
		help_text: 'PDO driver',
		is_required: true,
		select_options_creator: [
			self::class,
			'getDrivers'
		],
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please select driver',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select driver'
		]
	)]
	protected string $driver = 'mysql';


	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: true,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'DSN',
		is_required: true,
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter connection DSN'
		]
	)]
	protected string $DSN = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: false
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Username',
		is_required: false
	)]
	protected string $username = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: false
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_PASSWORD,
		label: 'Password',
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