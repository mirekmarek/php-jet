<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
#[Config_Definition]
abstract class Db_Backend_Config extends Config_Section
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
		label: 'Connection name',
		is_required: true,
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter connection name'
		]
	)]
	protected string $name = 'default';


	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}

	/**
	 * @return array
	 */
	public static function getDrivers(): array
	{
		return [];
	}

	/**
	 * @param string $driver
	 */
	public function setDriver( string $driver ): void
	{
		$this->driver = $driver;
	}


	/**
	 * @return string
	 */
	public function getDriver(): string
	{
		return $this->driver;
	}


}