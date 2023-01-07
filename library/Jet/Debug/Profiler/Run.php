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
class Debug_Profiler_Run
{

	/**
	 * @var string
	 */
	protected string $id = '';

	/**
	 * @var string
	 */
	protected string $request_URL = '';

	/**
	 * @var string
	 */
	protected string $date_and_time = '';

	/**
	 * @var Debug_Profiler_Run_Block[]
	 */
	protected array $blocks = [];

	/**
	 * @var mixed
	 */
	protected mixed $XHP_data = null;

	/**
	 * @var ?Debug_Profiler_Run_Block
	 */
	protected ?Debug_Profiler_Run_Block $__root_block = null;

	/**
	 * @var ?Debug_Profiler_Run_Block
	 */
	protected ?Debug_Profiler_Run_Block $__current_block = null;

	/**
	 * @var Debug_Profiler_Run_Block[]
	 */
	protected array $__block_stack = [];

	/**
	 * @var int
	 */
	protected int $__current_block_level = 0;

	/**
	 *
	 */
	public function __construct()
	{

		if( php_sapi_name() == 'cli' ) {
			$this->request_URL = $_SERVER['SCRIPT_FILENAME'] ?? 'CLI';
		} else {
			if(
				!isset( $_SERVER['HTTP_HOST'] ) ||
				!isset( $_SERVER['REQUEST_URI'] )
			) {
				$this->request_URL = 'unknown';
			}
			$this->request_URL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}

		$this->date_and_time = date( 'Y-m-d H:i:s' );

		srand();
		$this->id = md5( $this->request_URL . microtime( true ) . rand() . rand() . rand() );
		$root_block = new Debug_Profiler_Run_Block( 'root', 0 );
		$this->blocks[] = $root_block;
		$this->__root_block = $root_block;
		$this->__block_stack[] = $root_block;


		$this->__current_block_level = 1;

		$block = new Debug_Profiler_Run_Block_Anonymous(
			$this->__current_block_level,
			$this->__root_block
		);

		$this->appendBlock( $block );


		if( extension_loaded( 'xhprof' ) ) {
			xhprof_enable(
				XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY,
				[
				]
			);
		}

		if( extension_loaded( 'tideways' ) ) {
			/** @noinspection PhpUndefinedConstantInspection */
			/** @noinspection PhpUndefinedFunctionInspection */
			tideways_enable(
				TIDEWAYS_FLAGS_CPU + TIDEWAYS_FLAGS_MEMORY,
				[
				]
			);
		}
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getXHPData(): mixed
	{
		return $this->XHP_data;
	}

	/**
	 * @return string
	 */
	public function getDateAndTime(): string
	{
		return $this->date_and_time;
	}

	/**
	 * @return Debug_Profiler_Run_Block[]
	 */
	public function getBlocks(): array
	{
		return $this->blocks;
	}

	/**
	 * @return string
	 */
	public function getRequestURL(): string
	{
		return $this->request_URL;
	}

	/**
	 * @return Debug_Profiler_Run_SQLQueryData[]
	 */
	public function getSqlQueries(): array
	{
		$r = [];

		foreach( $this->blocks as $block ) {
			$r = array_merge( $r, $block->getSQLQueries() );
		}

		return $r;
	}


	/**
	 *
	 * @param string $label
	 *
	 * @return Debug_Profiler_Run_Block
	 */
	public function blockStart( string $label ): Debug_Profiler_Run_Block
	{
		if( $this->__current_block->getIsAnonymous() ) {
			$this->__current_block->setEnd();
			array_pop( $this->__block_stack );

			$this->__current_block_level = 1;
			$this->__current_block = $this->__root_block;
		}


		$block = new Debug_Profiler_Run_Block(
			$label,
			$this->__current_block_level,
			$this->__block_stack[$this->__current_block_level - 1]
		);


		$this->__current_block_level++;

		$this->appendBlock( $block );

		return $block;

	}

	/**
	 * @param string $label
	 */
	public function blockEnd( string $label ): void
	{
		if( $this->__current_block->getLabel() != $label ) {
			trigger_error(
				'Jet Profiler Error: Inconsistent block start and end. Star:' . $this->__current_block->getLabel() . ', end: ' . $label
			);

			die();
		}


		$this->__current_block->setEnd();
		$this->__current_block_level--;
		array_pop( $this->__block_stack );

		if( $this->__current_block_level > 1 ) {
			$this->__current_block = $this->__block_stack[count( $this->__block_stack ) - 1];
		} else {
			$this->__current_block_level = 1;
			$block = new Debug_Profiler_Run_Block_Anonymous(
				$this->__current_block_level,
				$this->__root_block
			);

			$this->appendBlock( $block );
		}
	}

	/**
	 * @param Debug_Profiler_Run_Block $block
	 */
	protected function appendBlock( Debug_Profiler_Run_Block $block ): void
	{
		$this->blocks[$block->getId()] = $block;
		$this->__current_block = $block;
		$this->__block_stack[] = $block;
	}


	/**
	 *
	 */
	public function runEnd(): void
	{
		$timestamp = microtime( true );

		while( $this->__block_stack ) {
			$block = array_pop( $this->__block_stack );
			$block->setEnd( $timestamp );
		}

		$this->__root_block->setEnd( $timestamp );


		if( extension_loaded( 'xhprof' ) ) {
			$this->XHP_data = xhprof_disable();
		}

		if( extension_loaded( 'tideways' ) ) {
			/** @noinspection PhpUndefinedFunctionInspection */
			$this->XHP_data = tideways_disable();
		}

	}


	/**
	 * @param string $query
	 * @param array $query_params
	 */
	public function SQLQueryStart( string $query, array $query_params ): void
	{
		$this->__current_block->SQLQueryStart( $query, $query_params );
	}

	/**
	 * @param int $rows_count
	 */
	public function SqlQueryDone( int $rows_count ): void
	{
		$this->__current_block->SQLQueryDone( $rows_count );
	}


	/**
	 * @return Debug_Profiler_Run_Block|null
	 */
	public function getCurrentBlock(): Debug_Profiler_Run_Block|null
	{
		return $this->__current_block;
	}

	/**
	 * @param string $text
	 */
	public function message( string $text ): void
	{
		$this->__current_block->message( $text );
	}

	/**
	 * Default serialize rules (don't serialize __* properties)
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

	/**
	 *
	 * @param int $shift (optional, default: 0)
	 *
	 * @return array
	 */
	public static function getBacktrace( int $shift = 0 ): array
	{
		$_backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );

		if( $shift ) {
			for( $c = 0; $c < $shift; $c++ ) {
				array_shift( $_backtrace );
			}
		}

		$backtrace = [];

		foreach( $_backtrace as $bt ) {
			if( !isset( $bt['file'] ) ) {
				$backtrace[] = '?';
			} else {
				$file = $bt['file'];

				$file = '~/'.substr($file, strlen(SysConf_Path::getBase()));

				$backtrace[] = $file . ':' . $bt['line'];
			}
		}

		return $backtrace;

	}

}