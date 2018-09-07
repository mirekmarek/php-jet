<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 */
class Debug_Profiler_Run_Block_Anonymous extends Debug_Profiler_Run_Block
{
	/**
	 * @var bool
	 */
	protected $is_anonymous = true;

	/** @noinspection PhpMissingParentConstructorInspection */
	/**
	 * @param int                      $level
	 * @param Debug_Profiler_Run_Block $parent_block
	 */
	public function __construct( $level, Debug_Profiler_Run_Block $parent_block = null )
	{
		$this->label = '?';
		$this->level = (int)$level;


		$this->timestamp_start = microtime( true );
		$this->memory_start = memory_get_usage( true );
		$this->memory_peak_start = memory_get_peak_usage( true );

		$this->id = md5( $this->label.$this->timestamp_start );

		if( $parent_block ) {
			$this->parent_block = $parent_block;
			$parent_block->addChild( $this );
		}

	}

	/**
	 * @param float $timestamp_end (optional, default: current)
	 */
	public function setEnd( $timestamp_end = null )
	{
		if( $this->timestamp_end ) {
			return;
		}

		$this->timestamp_end = $timestamp_end ? $timestamp_end : microtime( true );
		$this->memory_end = memory_get_usage( true );
		$this->memory_peak_end = memory_get_peak_usage( true );
	}


}