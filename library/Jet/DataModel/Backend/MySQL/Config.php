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
class DataModel_Backend_MySQL_Config extends DataModel_Backend_Config
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
			DataModel_Backend_MySQL_Config::class,
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
			DataModel_Backend_MySQL_Config::class,
			'getDbConnectionsList'
		],
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select database connection',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select database connection'
		]
	)]
	protected string $connection_write = '';

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
		label: 'Engine: ',
		is_required: true,
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter table engine'
		]
	)]
	protected string $engine = 'InnoDB';

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
		label: 'Default charset: ',
		is_required: true,
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter charset'
		]
	)]
	protected string $default_charset = 'utf8';

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
		label: 'Default collate: ',
		is_required: true,
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter default collate'
		]
		
	)]
	protected string $collate = 'utf8_general_ci';

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
	public function getCollate(): string
	{
		return $this->collate;
	}

	/**
	 * @param string $collate
	 */
	public function setCollate( string $collate ): void
	{
		$this->collate = $collate;
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

	/**
	 * @return string
	 */
	public function getDefaultCharset(): string
	{
		return $this->default_charset;
	}

	/**
	 * @param string $default_charset
	 */
	public function setDefaultCharset( string $default_charset ): void
	{
		$this->default_charset = $default_charset;
	}

	/**
	 * @return string
	 */
	public function getEngine(): string
	{
		return $this->engine;
	}

	/**
	 * @param string $engine
	 */
	public function setEngine( string $engine ): void
	{
		$this->engine = $engine;
	}
}