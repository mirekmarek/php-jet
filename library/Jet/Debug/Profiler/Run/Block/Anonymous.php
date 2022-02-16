<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected bool $is_anonymous = true;

	/** @noinspection PhpMissingParentConstructorInspection */
	/**
	 * @param int $level
	 * @param ?Debug_Profiler_Run_Block|null $parent_block
	 */
	public function __construct( int $level, ?Debug_Profiler_Run_Block $parent_block = null )
	{
		$this->label = '?';
		$this->level = $level;


		$this->timestamp_start = microtime( true );
		$this->memory_start = memory_get_usage();
		$this->memory_peak_start = memory_get_peak_usage();

		$this->id = md5( $this->label . $this->timestamp_start );

		if( $parent_block ) {
			$this->parent_block = $parent_block;
			$parent_block->addChild( $this );
		}

	}

	/**
	 * @param float|null $timestamp_end (optional, default: current)
	 */
	public function setEnd( float|null $timestamp_end = null ): void
	{
		if( $this->timestamp_end ) {
			return;
		}

		$this->timestamp_end = $timestamp_end ? : microtime( true );
		$this->memory_end = memory_get_usage();
		$this->memory_peak_end = memory_get_peak_usage();
	}


}