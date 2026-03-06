<?php

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


abstract class Lock_Backend {
	
	protected string $name;
	
	protected static int $wait_ms = 100;
	
	public static function getWaitMs(): int
	{
		return static::$wait_ms;
	}
	
	public static function setWaitMs( int $wait_ms ): void
	{
		static::$wait_ms = $wait_ms;
	}
	
	public function __construct( string $lock_name ) {
		$this->name = $lock_name;
	}
	
	abstract public function waitForLock( ?float $max_wait_seconds = null ) : bool;
	
	abstract public function lockIfPossible() : bool;
	
	abstract public function unlock() : void;
}