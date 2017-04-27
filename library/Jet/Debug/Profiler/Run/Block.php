<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 * Class Debug_Profiler_Run_Block
 * @package Jet
 */
class Debug_Profiler_Run_Block {
	/**
	 * @var string
	 */
	protected $id = "";

	/**
	 * @var bool
	 */
	protected $is_anonymous = false;

	/**
	 * @var string
	 */
	protected $label = "";

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
	 * @var array
	 */
	protected $backtrace_start = [];

	/**
	 * @var array
	 */
	protected $backtrace_end = [];

	/**
	 * @var Debug_Profiler_Run_SQLQueryData[]
	 */
	protected $SQL_queries = [];

	/**
	 * @var Debug_Profiler_Run_Block_Message[]
	 */
	protected $messages = [];

	/**
	 * @var int
	 */
	protected $level = 0;

	/**
	 * @var Debug_Profiler_Run_Block
	 */
	protected $parent_block;

	/**
	 * @var Debug_Profiler_Run_Block[]
	 */
	protected $children = [];

	/**
	 * @var null|Debug_Profiler_Run_SQLQueryData
	 */
	protected $__current_query;


	/**
	 * @param bool $is_anonymous
	 * @param string $label
	 * @param int $level
	 * @param Debug_Profiler_Run_Block $parent_block
	 */
	public function __construct( $is_anonymous, $label, $level, Debug_Profiler_Run_Block $parent_block=null ) {
		$this->label = $label;
		$this->level = (int)$level;
		$this->is_anonymous = $is_anonymous;

		$this->backtrace_start = Debug_Profiler::getBacktrace( 3 );

		$this->timestamp_start = microtime(true);
		$this->memory_start = memory_get_usage( true );
		$this->memory_peak_start = memory_get_peak_usage( true );

		$this->id = md5( $this->label.$this->timestamp_start );

		if($parent_block) {
			$this->parent_block = $parent_block;
			$parent_block->addChild($this);
		}
	}

	/**
	 * @param Debug_Profiler_Run_Block $child
	 */
	protected function addChild( Debug_Profiler_Run_Block $child ) {
		$this->children[] = $child;
	}

	/**
	 * @param float $timestamp_end (optional, default: current)
	 */
	public function setEnd( $timestamp_end=null ) {
		if($this->timestamp_end) {
			return;
		}

		$this->timestamp_end = $timestamp_end ? $timestamp_end : microtime(true);
		$this->memory_end = memory_get_usage( true );
		$this->memory_peak_end = memory_get_peak_usage( true );
		$this->backtrace_end = Debug_Profiler::getBacktrace( 3 );
	}

	/**
	 * @return bool
	 */
	public function getIsRoot() {
		return ($this->level==0);
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return boolean
	 */
	public function getIsAnonymous() {
		return $this->is_anonymous;
	}

	/**
	 * @return array
	 */
	public function getBacktraceEnd() {
		return $this->backtrace_end;
	}

	/**
	 * @return array
	 */
	public function getBacktraceStart() {
		return $this->backtrace_start;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return array|Debug_Profiler_Run_Block[]
	 */
	public function getChildren() {
		return $this->children;
	}



	/**
	 * @return int
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @return int
	 */
	public function getMemoryEnd() {
		return $this->memory_end;
	}

	/**
	 * @return int
	 */
	public function getMemoryStart() {
		return $this->memory_start;
	}

	/**
	 * @return int
	 */
	public function getMemoryPeakEnd() {
		return $this->memory_peak_end;
	}

	/**
	 * @return int
	 */
	public function getMemoryPeakStart() {
		return $this->memory_peak_start;
	}

	/**
	 * @return Debug_Profiler_Run_Block
	 */
	public function getParentBlock() {
		return $this->parent_block;
	}



	/**
	 * @return float
	 */
	public function getTimestampStart() {
		return $this->timestamp_start;
	}

	/**
	 * @return float
	 */
	public function getTimestampEnd() {
		return $this->timestamp_end;
	}

	/**
	 * @return float
	 */
	public function getDuration() {
		return $this->timestamp_end - $this->timestamp_start;
	}

	/**
	 * @return int
	 */
	public function getMemoryUsageDiff() {
		return $this->memory_end - $this->memory_start;
	}

	/**
	 * @return int
	 */
	public function getMemoryPeakDiff() {
		return $this->memory_peak_end - $this->memory_peak_start;
	}


	/**
	 * @return Debug_Profiler_Run_SQLQueryData[]
	 */
	public function getSQLQueries() {
		return $this->SQL_queries;
	}



	/**
	 * @param $query
	 * @param $query_data
	 *
	 * @return Debug_Profiler_Run_SQLQueryData
	 */
	public function SQLQueryStart(  $query, $query_data  ) {
		$q = new Debug_Profiler_Run_SQLqueryData( $this->id, $query, $query_data);

		$this->SQL_queries[] = $q;

		$this->__current_query = $q;

		return $q;
	}

	/**
	 * @param $rows_count
	 */
	public function SQLQueryDone( $rows_count ) {
		$this->__current_query->setDone($rows_count);
	}


	/**
	 * @param $text
	 */
	public function message( $text ) {
		$this->messages[] = new Debug_Profiler_Run_Block_Message( $text );
	}

	/**
	 * @return Debug_Profiler_Run_Block_Message[]
	 */
	public function getMessages() {
		return $this->messages;
	}


	/**
	 * Default serialize rules (don't serialize __* properties)
	 *
	 * @return array
	 */
	public function __sleep(){
		$vars = get_object_vars($this);
		foreach($vars as $k => $v){
			if(substr($k, 0, 2) === '__'){
				unset($vars[$k]);
			}
		}
		return array_keys($vars);
	}

}