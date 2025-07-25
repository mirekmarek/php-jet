<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

trait Db_Backend_PDO_Config_sqlsrv
{

	
	protected function sqlsrv_getDnsEntries(): array
	{
		
		return [
			'Server'    => $this->host.','.$this->port,
			'Database'  => $this->dbname,
			'TrustServerCertificate' => 'yes',
			'MultipleActiveResultSets' => 'False',
		];
	}
	
	protected function sqlsrv_getEntriesSchema(): array
	{
		return [
			'host'        => 'localhost',
			'port'        => 1433,
			'dbname'      => '',
			'username'    => '',
			'password'    => '',
		];
	}
	
}