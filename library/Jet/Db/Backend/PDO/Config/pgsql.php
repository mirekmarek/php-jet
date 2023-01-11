<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

trait Db_Backend_PDO_Config_pgsql
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
		label: 'SSL Mode:',
		is_required: false
	)]
	protected string $ssl_mode = '';
	
	
	protected function pgsql_getDnsEntries(): array
	{
		$rec = [
			'host'     => $this->host,
			'port'     => $this->port,
			'dbname'   => $this->dbname,
			'user'     => $this->getUsername(),
			'password' => $this->getPassword(),
		];
		
		if($this->ssl_mode) {
			$rec['sslmode'] = $this->ssl_mode;
		}
		
		return $rec;
	}
	
	protected function pgsql_getEntriesSchema(): array
	{
		return [
			'host'        => 'localhost',
			'port'        => 5432,
			'dbname'      => '',
			'username'    => '',
			'password'    => '',
		];
	}
	
}