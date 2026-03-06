<?php

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


class Lock {
	
	/**
	 * @var array<string,Lock_Backend>
	 */
	protected static array $locks = [];
	
	protected static string $lock_backend_class = Lock_Backend_File::class;
	
	public static function getLockBackendClass(): string
	{
		return static::$lock_backend_class;
	}
	
	public static function setLockBackendClass( string $lock_backend_class ): void
	{
		static::$lock_backend_class = $lock_backend_class;
	}
	
	protected static function getLock( string $lock_name ) : Lock_Backend
	{
		if(!isset(static::$locks[$lock_name])) {
			static::$locks[$lock_name] = new static::$lock_backend_class( $lock_name );
		}
		
		return static::$locks[$lock_name];
	}
	
	public static function waitForLock( string $lock_name, ?float $max_wait_seconds = null ) : bool
	{
		$lock = static::getLock( $lock_name );
		
		if(!$lock->waitForLock( $max_wait_seconds )) {
			return false;
		}
		
		register_shutdown_function( function() use ($lock) {
			$lock->unlock();
		} );
		
		return true;
	}
	
	public static function lockIfPossible( string $lock_name ) : bool
	{
		$lock = static::getLock( $lock_name );
		
		if(!$lock->lockIfPossible()) {
			return false;
		}
		
		register_shutdown_function( function() use ($lock) {
			$lock->unlock();
		} );
		
		return true;
	}
	
	public static function unlock( string $lock_name ) : void
	{
		static::getLock( $lock_name )->unlock();
	}
	
	public static function unlockAll() : void
	{
		foreach(static::$locks as $lock) {
			$lock->unlock();
		}
	}
	
}