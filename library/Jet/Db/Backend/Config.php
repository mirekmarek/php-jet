<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
#[Config_Definition(name: 'db')]
abstract class Db_Backend_Config extends Config_Section
{
	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(description: 'PDO driver')]
	#[Config_Definition(default_value: 'mysql')]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(form_field_type: Form::TYPE_SELECT)]
	#[Config_Definition(form_field_get_select_options_callback: [
		self::class,
		'getDrivers'
	])]
	#[Config_Definition(form_field_label: 'Driver')]
	#[Config_Definition(form_field_error_messages: [
		Form_Field::ERROR_CODE_EMPTY => 'Please select driver',
		Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => 'Please select driver'
	])]
	protected string $driver = '';


	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(default_value: 'default')]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(form_field_label: 'Connection name')]
	#[Config_Definition(form_field_error_messages: [Form_Field::ERROR_CODE_EMPTY => 'Please enter connection name'])]
	protected string $name = '';


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