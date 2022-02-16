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
class Debug_Profiler_Run_Block
{
	/**
	 * @var string
	 */
	protected string $id = '';

	/**
	 * @var bool
	 */
	protected bool $is_anonymous = false;

	/**
	 * @var string
	 */
	protected string $label = '';

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
	 * @var array
	 */
	protected array $backtrace_start = [];

	/**
	 * @var array
	 */
	protected array $backtrace_end = [];

	/**
	 * @var Debug_Profiler_Run_SQLQueryData[]
	 */
	protected array $SQL_queries = [];

	/**
	 * @var Debug_Profiler_Run_Block_Message[]
	 */
	protected array $messages = [];

	/**
	 * @var int
	 */
	protected int $level = 0;

	/**
	 * @var ?Debug_Profiler_Run_Block
	 */
	protected ?Debug_Profiler_Run_Block $parent_block = null;

	/**
	 * @var Debug_Profiler_Run_Block[]
	 */
	protected array $children = [];

	/**
	 * @var null|Debug_Profiler_Run_SQLQueryData
	 */
	protected ?Debug_Profiler_Run_SQLQueryData $__current_query = null;


	/**
	 * @param string $label
	 * @param int $level
	 * @param ?Debug_Profiler_Run_Block $parent_block
	 */
	public function __construct( string $label, int $level, ?Debug_Profiler_Run_Block $parent_block = null )
	{
		$this->label = $label;
		$this->level = $level;

		$this->backtrace_start = Debug_Profiler_Run::getBacktrace( 3 );

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
	 * @param Debug_Profiler_Run_Block $child
	 */
	protected function addChild( Debug_Profiler_Run_Block $child ): void
	{
		$this->children[] = $child;
	}

	/**
	 * @param float|null $timestamp_end (optional, default: current)
	 */
	public function setEnd( ?float $timestamp_end = null ): void
	{
		if( $this->timestamp_end ) {
			return;
		}

		$this->timestamp_end = $timestamp_end ? : microtime( true );
		$this->memory_end = memory_get_usage();
		$this->memory_peak_end = memory_get_peak_usage();
		$this->backtrace_end = Debug_Profiler_Run::getBacktrace( 3 );
	}

	/**
	 * @return bool
	 */
	public function getIsRoot(): bool
	{
		return ($this->level == 0);
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function getIsAnonymous(): bool
	{
		return $this->is_anonymous;
	}

	/**
	 * @return array
	 */
	public function getBacktraceEnd(): array
	{
		return $this->backtrace_end;
	}

	/**
	 * @return array
	 */
	public function getBacktraceStart(): array
	{
		return $this->backtrace_start;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * @return Debug_Profiler_Run_Block[]
	 */
	public function getChildren(): array
	{
		return $this->children;
	}


	/**
	 * @return int
	 */
	public function getLevel(): int
	{
		return $this->level;
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
	 * @return Debug_Profiler_Run_Block
	 */
	public function getParentBlock(): Debug_Profiler_Run_Block
	{
		return $this->parent_block;
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


	/**
	 * @return Debug_Profiler_Run_SQLQueryData[]
	 */
	public function getSQLQueries(): array
	{
		return $this->SQL_queries;
	}


	/**
	 * @param string $query
	 * @param array $query_params
	 *
	 * @return Debug_Profiler_Run_SQLQueryData
	 */
	public function SQLQueryStart( string $query, array $query_params ): Debug_Profiler_Run_SQLQueryData
	{
		$q = new Debug_Profiler_Run_SQLqueryData( $this->id, $query, $query_params );

		$this->SQL_queries[] = $q;

		$this->__current_query = $q;

		return $q;
	}

	/**
	 * @param int $rows_count
	 */
	public function SQLQueryDone( int $rows_count ): void
	{
		$this->__current_query->setDone( $rows_count );
	}


	/**
	 * @param string $text
	 */
	public function message( string $text ): void
	{
		$this->messages[] = new Debug_Profiler_Run_Block_Message( $text );
	}

	/**
	 * @return Debug_Profiler_Run_Block_Message[]
	 */
	public function getMessages(): array
	{
		return $this->messages;
	}


	/**
	 *
	 * @return array
	 */
	public function __sleep(): array
	{
		$vars = get_object_vars( $this );
		foreach( $vars as $k => $v ) {
			if( str_starts_with( $k, '__' ) ) {
				unset( $vars[$k] );
			}
		}

		return array_keys( $vars );
	}

}