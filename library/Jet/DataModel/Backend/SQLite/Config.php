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
class DataModel_Backend_SQLite_Config extends DataModel_Backend_Config
{

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(form_field_type: Form::TYPE_SELECT)]
	#[Config_Definition(form_field_get_select_options_callback: [
		DataModel_Backend_SQLite_Config::class,
		'getDbConnectionsList'
	])]
	#[Config_Definition(form_field_label: 'Connection: ')]
	#[Config_Definition(form_field_error_messages: [
		Form_Field::ERROR_CODE_EMPTY => 'Please select database connection',
		Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => 'Please select database connection'
	])]
	protected string $connection = '';

	/**
	 * @return array
	 */
	public static function getDbConnectionsList(): array
	{
		return Db_Config::getConnectionsList( Db::DRIVER_SQLITE );
	}

	/**
	 * @return string
	 */
	public function getConnection(): string
	{
		return $this->connection;
	}

	/**
	 * @param string $connection
	 */
	public function setConnection( string $connection ): void
	{
		$this->connection = $connection;
	}

}