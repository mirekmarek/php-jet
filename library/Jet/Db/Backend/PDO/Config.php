<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
	
	use Db_Backend_PDO_Config_mysql;
	use Db_Backend_PDO_Config_sqlite;
	use Db_Backend_PDO_Config_pgsql;
	use Db_Backend_PDO_Config_another;
	
	#[Config_Definition(
		type: Config::TYPE_STRING,
		is_required: false,
	)]
	protected string $dsn = '';
	
	/**
	 * @return array
	 */
	public static function getDrivers(): array
	{
		$drivers = PDO::getAvailableDrivers();

		return array_combine( $drivers, $drivers );
	}
	
	/**
	 * @param string $dsn
	 */
	public function setDsn( string $dsn ) : void
	{
		$this->dsn = $dsn;
	}

	/**
	 * @return string
	 */
	public function getDsn(): string
	{
		if(!$this->dsn) {
			$method = $this->driver.'_getDnsEntries';
			
			if(!method_exists($this, $method)) {
				return '';
			}
			
			$entries =  $this->{$method}();
			$dsn = [];
			
			foreach($entries as $key=>$val ) {
				if($val) {
					if(is_int($key)) {
						$dsn[] = $val;
						
					} else {
						$dsn[] = $key.'='.$val;
					}
				}
			}

			$this->dsn = $this->driver.':'.implode(';', $dsn);
		}
		
		return $this->dsn;
	}
	
}