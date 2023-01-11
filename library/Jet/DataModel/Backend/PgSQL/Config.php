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
class DataModel_Backend_PgSQL_Config extends DataModel_Backend_Config
{
	
	/**
	 *
	 * @var string
	 */
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: true
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_SELECT,
		label: 'Connection - read: ',
		is_required: true,
		select_options_creator: [
			DataModel_Backend_PgSQL_Config::class,
			'getDbConnectionsList'
		],
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select database connection',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select database connection'
		]
	
	)]
	protected string $connection_read = '';
	
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
		is_required: true,
		label: 'Connection - write: ',
		select_options_creator: [
			DataModel_Backend_PgSQL_Config::class,
			'getDbConnectionsList'
		],
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select database connection',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select database connection'
		]
	)]
	protected string $connection_write = '';
	
	
	/**
	 * @return array
	 */
	public static function getDbConnectionsList(): array
	{
		return Db_Config::getConnectionsList( Db::DRIVER_MYSQL );
	}
	

	
	/**
	 * @return string
	 */
	public function getConnectionRead(): string
	{
		return $this->connection_read;
	}
	
	/**
	 * @param string $connection_read
	 */
	public function setConnectionRead( string $connection_read ): void
	{
		$this->connection_read = $connection_read;
	}
	
	/**
	 * @return string
	 */
	public function getConnectionWrite(): string
	{
		return $this->connection_write;
	}
	
	/**
	 * @param string $connection_write
	 */
	public function setConnectionWrite( string $connection_write ): void
	{
		$this->connection_write = $connection_write;
	}
	
}