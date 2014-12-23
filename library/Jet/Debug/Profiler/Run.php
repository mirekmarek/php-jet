<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Debug
 * @subpackage Debug_Profiler
 */
namespace Jet;

class Debug_Profiler_Run {

	/**
	 * @var string
	 */
	protected $ID = '';

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
	protected $blocks = array();

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
	protected $__block_stack = array();

	/**
	 * @var int
	 */
	protected $__current_block_level = 0;

	/**
	 *
	 */
	public function __construct() {

		if( php_sapi_name() == 'cli' ) {
			$this->request_URL =  isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : 'CLI';
		} else {
			if(!isset($_SERVER['HTTP_HOST']) || !isset($_SERVER['HTTP_HOST'])) {
				$this->request_URL = 'unknown';
			}
			$this->request_URL = $_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'];
		}

		$this->date_and_time = date('Y-m-d H:i:s');

		srand();
		$this->ID = md5( $this->request_URL.microtime(true).rand().rand().rand() );

		$root_block = new Debug_Profiler_Run_Block( false, 'root', 0 );

		$this->blocks[] = $root_block;

		$this->__root_block = $root_block;


		$this->MainBlockStart("", true);

		if(extension_loaded('xhprof')) {
			/** @noinspection PhpUndefinedConstantInspection */
			/** @noinspection PhpUndefinedFunctionInspection */
			xhprof_enable( XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY );
		}
	}

	/**
	 * @return string
	 */
	public function getID() {
		return $this->ID;
	}


	/**
	 * @return
	 */
	public function getXHPData() {
		return $this->XHP_data;
	}

	/**
	 * @return string
	 */
	public function getDateAndTime() {
		return $this->date_and_time;
	}

	/**
	 * @return Debug_Profiler_Run_Block[]
	 */
	public function getBlocks() {
		return $this->blocks;
	}

	/**
	 * @return string
	 */
	public function getRequestURL() {
		return $this->request_URL;
	}

	/**
	 * @return Debug_Profiler_Run_SQLQueryData[]
	 */
	public function getSqlQueries() {
		$r = array();

		foreach($this->blocks as $block) {
			$r = array_merge($r, $block->getSQLQueries());
		}

		return $r;
	}


	/**
	 * Starts new block in level #1 end ends all previous block
	 *
	 * @param string $label
	 * @param bool $is_anonymous (optional, default: false)
	 *
	 * @return Debug_Profiler_Run_Block
	 */
	public function MainBlockStart( $label, $is_anonymous=false ) {
		if($is_anonymous) {
			$label = "anonymous";
		}

		if( $this->__block_stack ) {
			$timestamp = microtime(true);

			$_labels = array();

			do {
				/**
				 * @var Debug_Profiler_Run_Block $block
				 */
				$block = array_pop( $this->__block_stack );
				if(!$block->getIsAnonymous()) {
					$_labels[] = $block->getLabel();
				}
				$block->setEnd($timestamp);

			} while($this->__block_stack);

			if($_labels) {
				trigger_error('Jet Profiler Warning: blockStart(\''.$label.'\') called, but unclosed block(s) detected: \''.implode('\', \'', $_labels).'\' ! ');
			}
		}

		$this->__current_block_level = 1;

		$block = new Debug_Profiler_Run_Block( $is_anonymous, $label, $this->__current_block_level, $this->__root_block );

		$this->blocks[$block->getID()] = $block;
		$this->__current_block = $block;
		$this->__block_stack[] = $block;


		return $block;
	}

	/**
	 * @param string $label (Does nothing. Only for best practises and orientation to the application code)
	 */
	public function MainBlockEnd( $label ) {
		if( !$this->__current_block_level ) {
			trigger_error('Jet Profiler Warning: blockEnd(\''.$label.'\') called, but no block has been started! Skipping blockEnd! ');
			return;
		}

		$timestamp = microtime(true);

		if( $this->__current_block_level>1 ) {

			$_labels = array();

			do {
				/**
				 * @var Debug_Profiler_Run_Block $block
				 */
				$block = array_pop( $this->__block_stack );
				$block->setEnd($timestamp);

			} while($this->__block_stack && $block->getLevel()>1);

			trigger_error('Jet Profiler Warning: blockEnd(\''.$label.'\') called, but unclosed subblock(s) detected: \''.implode('\', \'', $_labels).'\' ! ');
		}

		/**
		 * @var Debug_Profiler_Run_Block $block
		 */
		$block = array_pop( $this->__block_stack );
		$block->setEnd($timestamp);

		$this->__block_stack = array();
		$this->__current_block_level = 0;

		$this->MainBlockStart("", true);
	}

	/**
	 *
	 * @param string $label
	 *
	 * @return Debug_Profiler_Run_Block
	 */
	public function blockStart( $label ) {
		if(!$this->__current_block_level) {
			trigger_error('Jet Profiler Warning: subBlockStart(\''.$label.'\') called, but no block has been started! Calling blockStart! ');
			return $this->MainBlockStart($label);
		}

		$block = new Debug_Profiler_Run_Block( false, $label, $this->__current_block_level, $this->__block_stack[$this->__current_block_level-1] );


		$this->__current_block_level++;
		$this->blocks[$block->getID()] = $block;
		$this->__block_stack[] = $block;

		$this->__current_block = $block;

		return $block;

	}

	/**
	 * @param string $label (Does nothing. Only for best practises and orientation to the application code)
	 */
	public function blockEnd( $label ) {

		if(	$this->__current_block_level<=1 ) {
			trigger_error('Jet Profiler Warning: subBlockEnd(\''.$label.'\') called, but no subblock has been started! Calling blockEnd! ');
			$this->MainBlockEnd( $label );
			return;
		}

		$this->__current_block->setEnd();
		$this->__current_block_level--;
		array_pop($this->__block_stack);

		$this->__current_block  = $this->__block_stack[count($this->__block_stack)-1];
	}



	/**
	 *
	 */
	public function runEnd() {
		$timestamp = microtime(true);

		while($this->__block_stack) {
			/**
			 * @var Debug_Profiler_Run_Block $block
			 */
			$block = array_pop( $this->__block_stack );
			$_labels[] = $block->getLabel();
			$block->setEnd( $timestamp );
		};

		$this->__root_block->setEnd( $timestamp );


		if(extension_loaded('xhprof')) {
			/** @noinspection PhpUndefinedFunctionInspection */
			$this->XHP_data = xhprof_disable();
		}

	}


	/**
	 * @param $query
	 * @param $query_data
	 *
	 * @return Debug_Profiler_Run_SQLQueryData
	 */
	public function SQLQueryStart(  $query, $query_data  ) {
		$this->__current_block->SQLQueryStart($query, $query_data);
	}

	/**
	 * @param $rows_count
	 */
	public function SQLQueryDone( $rows_count ) {
		$this->__current_block->SQLQueryDone( $rows_count );
	}


	/**
	 * @return Debug_Profiler_Run_Block|null
	 */
	public function getCurrentBlock() {
		return $this->__current_block;
	}

	/**
	 * @param $text
	 */
	public function message( $text ) {
		$this->__current_block->message($text);
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