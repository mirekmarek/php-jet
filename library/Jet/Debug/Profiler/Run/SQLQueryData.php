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
class Debug_Profiler_Run_SQLQueryData
{
	/**
	 * @var string
	 */
	protected string $block_id = '';

	/**
	 * @var string
	 */
	protected string $query = '';

	/**
	 * @var array
	 */
	protected array $query_params = [];

	/**
	 * @var array
	 */
	protected array $backtrace = [];

	/**
	 * @var float
	 */
	protected float $timestamp_start = 0.0;

	/**
	 * @var float
	 */
	protected float $timestamp_end = 0.0;

	/**
	 * @var int
	 */
	protected int $memory_start = 0;

	/**
	 * @var int
	 */
	protected int $memory_end = 0;

	/**
	 * @var int
	 */
	protected int $memory_peak_start = 0;

	/**
	 * @var int
	 */
	protected int $memory_peak_end = 0;


	/**
	 * @var int
	 */
	protected int $rows_count = 0;

	/**
	 * @param string $block_id
	 * @param string $query
	 * @param array $query_params
	 */
	public function __construct( string $block_id, string $query, array $query_params )
	{
		$this->block_id = $block_id;

		$this->query = $query;
		$this->query_params = $query_params;

		$this->backtrace = Debug_Profiler_Run::getBacktrace( 5 );

		$this->timestamp_start = microtime( true );
		$this->memory_start = memory_get_usage();
		$this->memory_peak_start = memory_get_peak_usage();
	}

	/**
	 * @param int $rows_count
	 */
	public function setDone( int $rows_count ): void
	{
		$this->timestamp_end = microtime( true );
		$this->memory_end = memory_get_usage();
		$this->memory_peak_end = memory_get_peak_usage();
		$this->rows_count = $rows_count;
	}

	/**
	 * @return string
	 */
	public function getBlockId(): string
	{
		return $this->block_id;
	}

	/**
	 * @return array
	 */
	public function getBacktrace(): array
	{
		return $this->backtrace;
	}

	/**
	 * @return int
	 */
	public function getMemoryEnd(): int
	{
		return $this->memory_end;
	}

	/**
	 * @return int
	 */
	public function getMemoryStart(): int
	{
		return $this->memory_start;
	}

	/**
	 * @return int
	 */
	public function getMemoryPeakEnd(): int
	{
		return $this->memory_peak_end;
	}

	/**
	 * @return int
	 */
	public function getMemoryPeakStart(): int
	{
		return $this->memory_peak_start;
	}


	/**
	 * @return string
	 */
	public function getQuery(): string
	{
		return $this->query;
	}

	/**
	 * @return array
	 */
	public function getQueryParams(): array
	{
		return $this->query_params;
	}

	/**
	 * @return int
	 */
	public function getRowsCount(): int
	{
		return $this->rows_count;
	}

	/**
	 * @return float
	 */
	public function getTimestampStart(): float
	{
		return $this->timestamp_start;
	}

	/**
	 * @return float
	 */
	public function getTimestampEnd(): float
	{
		return $this->timestamp_end;
	}

	/**
	 * @return float
	 */
	public function getDuration(): float
	{
		return $this->timestamp_end - $this->timestamp_start;
	}

	/**
	 * @return int
	 */
	public function getMemoryUsageDiff(): int
	{
		return $this->memory_end - $this->memory_start;
	}

	/**
	 * @return int
	 */
	public function getMemoryPeakDiff(): int
	{
		return $this->memory_peak_end - $this->memory_peak_start;
	}


}