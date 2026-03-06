<?php

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


class Lock_Backend_MariaDB extends Lock_Backend
{
	
	protected static ?Db_Backend_Interface $db = null;
	protected static string $table_name = 'system_locks';
	
	public static function getDb() : Db_Backend_Interface
	{
		if(!static::$db) {
			static::$db = Db::get();
		}
		return static::$db;
	}
	
	public static function setDb( ?Db_Backend_Interface $db ): void
	{
		static::$db = $db;
	}
	
	public static function getTableName(): string
	{
		return static::$table_name;
	}
	
	public static function setTableName( string $table_name ): void
	{
		static::$table_name = $table_name;
	}
	
	
	
	public static function createDbTable() : void
	{
		static::getDb()->execute("CREATE TABLE IF NOT EXISTS `".static::$table_name."` (
				`lock_name` varchar(150) NOT NULL,
				`lock_date_time` datetime NOT NULL,
				PRIMARY KEY (`lock_name`)
			) ENGINE=InnoDB");
	}
	
	
	
	
	public function __construct( string $lock_name )
	{
		$lock_name = addslashes($lock_name);
		parent::__construct( $lock_name );
	}
	
	
	public function lockIfPossible() : bool
	{
		$db = static::getDb();
		
		$db->execute("LOCK TABLES `".static::$table_name."` WRITE");
		
		$now = date('Y-m-d H:i:s');
		
		$e_lock = $db->fetchRow("SELECT * FROM `".static::$table_name."` WHERE lock_name='$this->name'");
		
		if($e_lock) {
			$db->execute("UNLOCK TABLES");
			return false;
		}
		
		$db->execute("INSERT INTO `".static::$table_name."` SET
							lock_name='$this->name',
							lock_date_time=now()
						ON DUPLICATE KEY UPDATE
							lock_date_time=now()
							");
		
		
		$db->execute("UNLOCK TABLES");
		
		return true;

	}
	
	
	public function waitForLock( ?float $max_wait_seconds = null ) : bool
	{
		if($max_wait_seconds) {
			$start = microtime( true );
		} else {
			$start = null;
		}
		
		while (!$this->lockIfPossible()) {
			usleep( static::$wait_ms );
			
			if($start) {
				$diff = microtime(true) - $start;
				if($diff>$max_wait_seconds) {
					return false;
				}
			}
		}
		
		return true;
	}
	
	public function unlock() : void
	{
		$db = static::getDb();
		
		$db->execute("LOCK TABLES `".static::$table_name."` WRITE");
		
		$e_lock = $db->execute("DELETE FROM `".static::$table_name."` WHERE lock_name='$this->name' ");
		
		$db->execute("UNLOCK TABLES");

	}
	
	
	
	public function setLock() : bool
	{
		
		$db = static::getDb();
		$db->execute("LOCK TABLES `".static::$table_name."` WRITE");
		
		$db->execute("INSERT INTO `".static::$table_name."` SET
							lock_name='$this->name',
							lock_date_time=now()
						ON DUPLICATE KEY UPDATE
							lock_date_time=now()");
		
		$db->execute("UNLOCK TABLES");
		
		return true;
	}
	
	
	
}