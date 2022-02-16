<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

trait Db_Backend_PDO_Config_mysql
{
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
		label: 'Charset:',
		is_required: false
	)]
	protected string $charset = '';
	
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
		label: 'Unix socket path:',
		is_required: false
	)]
	protected string $unix_socket = '';
	
	/**
	 * @return string
	 */
	public function getCharset(): string
	{
		return $this->charset;
	}
	
	/**
	 * @param string $charset
	 */
	public function setCharset( string $charset ): void
	{
		$this->charset = $charset;
	}
	
	
	/**
	 * @return string
	 */
	public function getUnixSocket(): string
	{
		return $this->unix_socket;
	}
	
	/**
	 * @param string $unix_socket
	 */
	public function setUnixSocket( string $unix_socket ): void
	{
		$this->unix_socket = $unix_socket;
	}
	
	
	protected function mysql_getDnsEntries(): array
	{
		if( $this->unix_socket ) {
			return [
				'unix_socket' => $this->unix_socket,
				'dbname'      => $this->dbname,
				'charset'     => $this->charset
			];
		}
		
		return [
			'host'    => $this->host,
			'port'    => $this->port,
			'dbname'  => $this->dbname,
			'charset' => $this->charset
		];
	}
	
	protected function mysql_getEntriesSchema(): array
	{
		return [
			'host'        => 'localhost',
			'port'        => 3306,
			'dbname'      => '',
			'username'    => '',
			'password'    => '',
			'charset'     => 'utf8',
			'unix_socket' => ''
		];
	}
	
}