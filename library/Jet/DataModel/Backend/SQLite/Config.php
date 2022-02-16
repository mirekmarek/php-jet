<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: true,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_SELECT,
		label: 'Connection: ',
		is_required: true,
		select_options_creator: [
			DataModel_Backend_SQLite_Config::class,
			'getDbConnectionsList'
		],
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please select database connection',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select database connection'
		]
	)]
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