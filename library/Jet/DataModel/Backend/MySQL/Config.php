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
class DataModel_Backend_MySQL_Config extends DataModel_Backend_Config
{

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(form_field_type: Form::TYPE_SELECT)]
	#[Config_Definition(form_field_get_select_options_callback: [
		DataModel_Backend_MySQL_Config::class,
		'getDbConnectionsList'
	])]
	#[Config_Definition(form_field_label: 'Connection - read: ')]
	#[Config_Definition(form_field_error_messages: [
		Form_Field::ERROR_CODE_EMPTY                     => 'Please select database connection',
		Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => 'Please select database connection'
	])]
	protected string $connection_read = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(form_field_type: Form::TYPE_SELECT)]
	#[Config_Definition(form_field_get_select_options_callback: [
		DataModel_Backend_MySQL_Config::class,
		'getDbConnectionsList'
	])]
	#[Config_Definition(form_field_label: 'Connection - write: ')]
	#[Config_Definition(form_field_error_messages: [
		Form_Field::ERROR_CODE_EMPTY                     => 'Please select database connection',
		Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => 'Please select database connection'
	])]
	protected string $connection_write = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(default_value: 'InnoDB')]
	#[Config_Definition(form_field_label: 'Engine: ')]
	#[Config_Definition(form_field_error_messages: [Form_Field::ERROR_CODE_EMPTY => 'Please enter table engine'])]
	protected string $engine = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(default_value: 'utf8')]
	#[Config_Definition(form_field_label: 'Default charset: ')]
	#[Config_Definition(form_field_error_messages: [Form_Field::ERROR_CODE_EMPTY => 'Please enter charset'])]
	protected string $default_charset = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(default_value: 'utf8_general_ci')]
	#[Config_Definition(form_field_label: 'Default collate: ')]
	#[Config_Definition(form_field_error_messages: [Form_Field::ERROR_CODE_EMPTY => 'Please enter default collate'])]
	protected string $collate = '';

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