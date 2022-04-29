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
	protected string $driver = Db::DRIVER_MYSQL;
	
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
	 *
	 * @var string
	 */
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: false
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Username:',
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
		label: 'Password:',
		is_required: false
	)]
	protected string $password = '';
	
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
		label: 'Database:',
		is_required: false
	)]
	protected string $dbname = '';
	
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
		label: 'Host:',
		is_required: false
	)]
	protected string $host = '';
	
	/**
	 *
	 * @var int
	 */
	#[Config_Definition(
		type: Config::TYPE_INT,
		is_required: false
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INT,
		label: 'Port:',
		is_required: false
	)]
	protected int $port = 0;
	
	
	/**
	 * @return array
	 */
	public static function getDrivers(): array
	{
		return [];
	}

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
	public function getDbname(): string
	{
		return $this->dbname;
	}
	
	/**
	 * @param string $dbname
	 */
	public function setDbname( string $dbname ): void
	{
		$this->dbname = $dbname;
	}
	
	/**
	 * @return string
	 */
	public function getHost(): string
	{
		return $this->host;
	}
	
	/**
	 * @param string $host
	 */
	public function setHost( string $host ): void
	{
		$this->host = $host;
	}
	
	/**
	 * @return int
	 */
	public function getPort(): int
	{
		return $this->port;
	}
	
	/**
	 * @param int $port
	 */
	public function setPort( int $port ): void
	{
		$this->port = $port;
	}

	
	/**
	 * @return array
	 */
	public function toArray(): array
	{
		$res = [
			'driver' => $this->driver,
			'name' => $this->name,
		];
		
		$entries = $this->getEntriesSchema();
		
		foreach($entries as $key=>$val) {
			$res[$key] = $this->{$key};
		}
		
		return $res;
	}
	
	
	/**
	 *
	 */
	public function initDefault() : void
	{
		$entries = $this->getEntriesSchema();
		
		foreach($entries as $key=>$val) {
			$this->{$key} = $val;
		}
	}
	
	/**
	 * @return array
	 */
	public function getEntriesSchema() : array
	{
		$method = $this->driver.'_getEntriesSchema';
		
		if(!method_exists($this, $method)) {
			$method = 'another_getEntriesSchema';
		}
		
		return $this->{$method}();
	}
	
	/**
	 * @param string $form_name
	 * @param array $only_fields
	 * @param array $exclude_fields
	 * @return Form
	 * @throws Form_Definition_Exception
	 */
	public function createForm( string $form_name, array $only_fields=[], array $exclude_fields=[]  ): Form
	{
		$form = parent::createForm($form_name, $only_fields, $exclude_fields);
		
		$entries = $this->getEntriesSchema();
		
		foreach($form->getFields() as $field) {
			if(!isset($entries[$field->getName()])) {
				$form->removeField( $field->getName() );
			}
		}
		
		return $form;
	}
	
}