<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

trait Db_Backend_PDO_Config_mysql
{
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