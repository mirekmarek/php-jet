<?php

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


class Lock_Backend_File extends Lock_Backend
{
	protected string $file_path;
	/**
	 * @var null|resource
	 */
	protected $fp;
	
	
	public function __construct( string $lock_name )
	{
		parent::__construct( $lock_name );
		$this->file_path = SysConf_Path::getTmp().'lock/'.$lock_name;
		
		if(!IO_File::exists($this->file_path)) {
			IO_File::write($this->file_path, '');
		}
		
		$this->fp = fopen( $this->file_path, 'c+');
	}
	
	
	public function lockIfPossible() : bool
	{
		if(!flock($this->fp, LOCK_EX | LOCK_NB)) {
			return false;
		}
		
		ftruncate($this->fp, 0);
		fwrite($this->fp, date('Y-m-d H:i:s')." LOCKED\n");
		
		return true;
	}
	
	
	public function waitForLock( ?float $max_wait_seconds = null ) : bool
	{
		if($max_wait_seconds) {
			$start = microtime( true );
		} else {
			$start = null;
		}
		
		
		while (!flock($this->fp, LOCK_EX | LOCK_NB)) {
			usleep( static::$wait_ms );
			
			if($start) {
				$diff = microtime(true) - $start;
				if($diff>$max_wait_seconds) {
					return false;
				}
			}
		}
		
		ftruncate($this->fp, 0);
		fwrite($this->fp, date('Y-m-d H:i:s')." LOCKED\n");
		
		return true;
	}
	
	public function unlock() : void
	{
		if(!$this->fp) {
			return;
		}
		
		fwrite($this->fp, date('Y-m-d H:i:s')." UNLOCKED\n");
		flock( $this->fp, LOCK_UN );
		fclose( $this->fp);
		$this->fp = null;
	}
	
}