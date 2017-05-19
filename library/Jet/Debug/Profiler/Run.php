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
class Debug_Profiler_Run
{

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var string
	 */
	protected $request_URL = '';

	/**
	 * @var string
	 */
	protected $date_and_time = '';

	/**
	 * @var Debug_Profiler_Run_Block[]
	 */
	protected $blocks = [];

	/**
	 * @var
	 */
	protected $XHP_data;

	/**
	 * @var Debug_Profiler_Run_Block
	 */
	protected $__root_block;

	/**
	 * @var Debug_Profiler_Run_Block
	 */
	protected $__current_block;

	/**
	 * @var Debug_Profiler_Run_Block[]
	 */
	protected $__block_stack = [];

	/**
	 * @var int
	 */
	protected $__current_block_level = 0;

	/**
	 *
	 */
	public function __construct()
	{

		if( php_sapi_name()=='cli' ) {
			$this->request_URL = isset( $_SERVER['SCRIPT_FILENAME'] ) ? $_SERVER['SCRIPT_FILENAME'] : 'CLI';
		} else {
			if( !isset( $_SERVER['HTTP_HOST'] )||!isset( $_SERVER['HTTP_HOST'] ) ) {
				$this->request_URL = 'unknown';
			}
			$this->request_URL = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}

		$this->date_and_time = date( 'Y-m-d H:i:s' );

		srand();
		$this->id = md5( $this->request_URL.microtime( true ).rand().rand().rand() );
		$root_block = new Debug_Profiler_Run_Block( 'root', 0 );
		$this->blocks[] = $root_block;
		$this->__root_block = $root_block;
		$this->__block_stack[] = $root_block;





		$this->__current_block_level = 1;

		$block = new Debug_Profiler_Run_Block_Anonymous(
			$this->__current_block_level,
			$this->__root_block
		);

		$this->appendBlock($block);


		if( extension_loaded( 'xhprof' ) ) {
			/** @noinspection PhpUndefinedConstantInspection */
			/** @noinspection PhpUndefinedFunctionInspection */
			xhprof_enable( XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY );
		}

		if( extension_loaded( 'tideways' ) ) {
			/** @noinspection PhpUndefinedConstantInspection */
			/** @noinspection PhpUndefinedFunctionInspection */
			tideways_enable( TIDEWAYS_FLAGS_CPU+TIDEWAYS_FLAGS_MEMORY );
		}
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return
	 */
	public function getXHPData()
	{
		return $this->XHP_data;
	}

	/**
	 * @return string
	 */
	public function getDateAndTime()
	{
		return $this->date_and_time;
	}

	/**
	 * @return Debug_Profiler_Run_Block[]
	 */
	public function getBlocks()
	{
		return $this->blocks;
	}

	/**
	 * @return string
	 */
	public function getRequestURL()
	{
		return $this->request_URL;
	}

	/**
	 * @return Debug_Profiler_Run_SQLQueryData[]
	 */
	public function getSqlQueries()
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
	public function blockStart( $label )
	{
		if($this->__current_block->getIsAnonymous()) {
			$this->__current_block->setEnd();
			array_pop( $this->__block_stack );

			$this->__current_block_level = 1;
			$this->__current_block = $this->__root_block;
		}


		$block = new Debug_Profiler_Run_Block(
			$label,
			$this->__current_block_level,
			$this->__block_stack[$this->__current_block_level-1]
		);


		$this->__current_block_level++;

		$this->appendBlock($block);

		return $block;

	}

	/**
	 * @param string $label (Does nothing. Only for best practises and orientation to the application code)
	 */
	public function blockEnd( $label )
	{
		if($this->__current_block->getLabel()!=$label) {
			trigger_error(
				'Jet Profiler Error: Inconsistent block start and end. Star:'.$this->__current_block->getLabel().', end: '.$label
			);

			die();
		}


		$this->__current_block->setEnd();
		$this->__current_block_level--;
		array_pop( $this->__block_stack );

		if($this->__current_block_level>1) {
			$this->__current_block = $this->__block_stack[count( $this->__block_stack )-1];
		} else {
			$this->__current_block_level=1;
			$block = new Debug_Profiler_Run_Block_Anonymous(
				$this->__current_block_level,
				$this->__root_block
			);

			$this->appendBlock($block);
		}
	}

	/**
	 * @param Debug_Profiler_Run_Block $block
	 */
	protected function appendBlock( Debug_Profiler_Run_Block $block )
	{
		$this->blocks[$block->getId()] = $block;
		$this->__current_block = $block;
		$this->__block_stack[] = $block;
	}


	/**
	 *
	 */
	public function runEnd()
	{
		$timestamp = microtime( true );

		while( $this->__block_stack ) {
			/**
			 * @var Debug_Profiler_Run_Block $block
			 */
			$block = array_pop( $this->__block_stack );
			$_labels[] = $block->getLabel();
			$block->setEnd( $timestamp );
		};

		$this->__root_block->setEnd( $timestamp );


		if( extension_loaded( 'xhprof' ) ) {
			/** @noinspection PhpUndefinedFunctionInspection */
			$this->XHP_data = xhprof_disable();
		}

		if( extension_loaded( 'tideways' ) ) {
			/** @noinspection PhpUndefinedFunctionInspection */
			$this->XHP_data = tideways_disable();
		}

	}


	/**
	 * @param string $query
	 * @param array  $query_data
	 */
	public function SQLQueryStart( $query, $query_data )
	{
		$this->__current_block->SQLQueryStart( $query, $query_data );
	}

	/**
	 * @param int $rows_count
	 */
	public function SqlQueryDone( $rows_count )
	{
		$this->__current_block->SQLQueryDone( $rows_count );
	}


	/**
	 * @return Debug_Profiler_Run_Block|null
	 */
	public function getCurrentBlock()
	{
		return $this->__current_block;
	}

	/**
	 * @param string $text
	 */
	public function message( $text )
	{
		$this->__current_block->message( $text );
	}

	/**
	 * Default serialize rules (don't serialize __* properties)
	 *
	 * @return array
	 */
	public function __sleep()
	{
		$vars = get_object_vars( $this );
		foreach( $vars as $k => $v ) {
			if( substr( $k, 0, 2 )==='__' ) {
				unset( $vars[$k] );
			}
		}

		return array_keys( $vars );
	}

}