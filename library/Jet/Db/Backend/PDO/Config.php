<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use PDO;

/**
 *
 */
class Db_Backend_PDO_Config extends Db_Backend_Config
{
	
	protected ?string $dsn = null;
	

	/**
	 * @return array
	 */
	public static function getDrivers(): array
	{
		$drivers = PDO::getAvailableDrivers();

		return array_combine( $drivers, $drivers );
	}

	/**
	 * @return string
	 */
	public function getDsn(): string
	{
		if(!$this->dsn) {
			$method = $this->driver.'_getDnsEntries';
			
			$entries =  $this->{$method}();
			$dsn = [];
			
			foreach($entries as $key ) {
				$val = $this->{$key};
				if($val) {
					$dsn[] = $key.'='.$val;
				}
			}

			$this->dsn = $this->driver.':'.implode(';', $dsn);
		}
		
		return $this->dsn;
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
		
		return $this->{$method}();
	}
	
	protected function mysql_getDnsEntries() : array
	{
		if($this->unix_socket) {
			return ['unix_socket', 'dbname', 'charset'];
		}
		
		return ['host', 'port', 'dbname', 'charset'];
	}
	
	protected function mysql_getEntriesSchema() : array
	{
		return ['host' => 'localhost', 'port'=>3306, 'dbname'=>'', 'username'=>'', 'password'=>'', 'charset'=>'utf8', 'unix_socket'=>''];
	}
	
	protected function sqlite_getDnsEntries() : array
	{
		return ['path'];
	}
	
	protected function sqlite_getEntriesSchema() : array
	{
		return ['path'=>SysConf_Path::getData() . 'database.sq3'];
	}

	
	//TODO: other ...

}