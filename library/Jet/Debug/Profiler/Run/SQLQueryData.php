<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected $block_id = "";

	/**
	 * @var string
	 */
	protected $query = "";

	/**
	 * @var array
	 */
	protected $query_data = [];

	/**
	 * @var array
	 */
	protected $backtrace = [];

	/**
	 * @var float
	 */
	protected $timestamp_start = 0.0;

	/**
	 * @var float
	 */
	protected $timestamp_end = 0.0;

	/**
	 * @var int
	 */
	protected $memory_start = 0;

	/**
	 * @var int
	 */
	protected $memory_end = 0;

	/**
	 * @var int
	 */
	protected $memory_peak_start = 0;

	/**
	 * @var int
	 */
	protected $memory_peak_end = 0;


	/**
	 * @var int
	 */
	protected $rows_count = 0;

	/**
	 * @param string $block_id
	 * @param string $query
	 * @param array  $query_data
	 */
	public function __construct( $block_id, $query, $query_data )
	{
		$this->block_id = $block_id;

		$this->query = $query;
		$this->query_data = $query_data;

		$this->backtrace = Debug_Profiler::getBacktrace( 5 );

		$this->timestamp_start = microtime( true );
		$this->memory_start = memory_get_usage( true );
		$this->memory_peak_start = memory_get_peak_usage( true );
	}

	/**
	 * @param int $rows_count
	 */
	public function setDone( $rows_count )
	{
		$this->timestamp_end = microtime( true );
		$this->memory_end = memory_get_usage( true );
		$this->memory_peak_end = memory_get_peak_usage( true );
		$this->rows_count = $rows_count;
	}

	/**
	 * @return string
	 */
	public function getBlockId()
	{
		return $this->block_id;
	}

	/**
	 * @return array
	 */
	public function getBacktrace()
	{
		return $this->backtrace;
	}

	/**
	 * @return int
	 */
	public function getMemoryEnd()
	{
		return $this->memory_end;
	}

	/**
	 * @return int
	 */
	public function getMemoryStart()
	{
		return $this->memory_start;
	}

	/**
	 * @return int
	 */
	public function getMemoryPeakEnd()
	{
		return $this->memory_peak_end;
	}

	/**
	 * @return int
	 */
	public function getMemoryPeakStart()
	{
		return $this->memory_peak_start;
	}


	/**
	 * @return string
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @return array
	 */
	public function getQueryData()
	{
		return $this->query_data;
	}

	/**
	 * @return int
	 */
	public function getRowsCount()
	{
		return $this->rows_count;
	}

	/**
	 * @return float
	 */
	public function getTimestampStart()
	{
		return $this->timestamp_start;
	}

	/**
	 * @return float
	 */
	public function getTimestampEnd()
	{
		return $this->timestamp_end;
	}

	/**
	 * @return float
	 */
	public function getDuration()
	{
		return $this->timestamp_end-$this->timestamp_start;
	}

	/**
	 * @return int
	 */
	public function getMemoryUsageDiff()
	{
		return $this->memory_end-$this->memory_start;
	}

	/**
	 * @return int
	 */
	public function getMemoryPeakDiff()
	{
		return $this->memory_peak_end-$this->memory_peak_start;
	}


}