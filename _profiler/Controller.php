<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

use Jet\Debug;
use Jet\Debug_Profiler;
use Jet\Debug_Profiler_Run;
use Jet\IO_Dir;
use Jet\IO_Dir_Exception;
use Jet\IO_File;
use Jet\IO_File_Exception;
use Jet\SysConf_Path;

$controller = new class {

	protected string $run_file_suffix = '.jpd';
	protected string $save_dir = '_profiler';
	protected string $GET_param_run_id = 'JPR';

	protected string $dir;

	protected string $save_error = '';

	public function __construct()
	{
		$this->dir = SysConf_Path::getTmp() . $this->save_dir.'/';

		$this->catchShowRun();

		Debug_Profiler::enable(
			log_SQL_queries: true,
			saver:
				function(Debug_Profiler_Run $run) {
					try {
						$this->saveRun($run);
					} catch( IO_Dir_Exception|IO_File_Exception $exception ) {
						$this->save_error = $exception->getMessage();
					}
				},
			displayer:
				function(Debug_Profiler_Run $run) {
					$this->statusBarDisplayer($run);
				}
		);
	}


	protected function getRunDirPath() : string {
		$dir = $this->dir;

		require_once SysConf_Path::getLibrary().'Jet/IO/File.php';
		require_once SysConf_Path::getLibrary().'Jet/IO/Dir.php';

		if(!IO_Dir::exists($dir)) {
			IO_Dir::create($dir);
		}

		return $dir;
	}

	protected function getRunFilePath( string $run_id ) : string
	{
		if( str_contains( $run_id, '.' ) ) {
			die();
		}

		$dir = $this->getRunDirPath();
		return $dir . $run_id . $this->run_file_suffix;
	}

	public function saveRun( Debug_Profiler_Run $run ) : void
	{
		$file_path = $this->getRunFilePath( $run->getId() );

		IO_File::write($file_path, serialize($run));
	}


	public function readRun( string $run_id ) : Debug_Profiler_Run|null
	{
		$file_path = $this->getRunFilePath( $run_id );

		$run = null;
		if( file_exists( $file_path ) ) {

			$run = unserialize( IO_File::read( $file_path ) );

			if(
				!is_object( $run ) ||
				!($run instanceof Debug_Profiler_Run)
			) {
				$run = null;
			}
		}

		return $run;
	}

	public function statusBarDisplayer( Debug_Profiler_Run $run ) : void
	{
		if($this->save_error) {
			if( !Debug::getOutputIsJSON() ) {
				require __DIR__ . '/views/status-bar/error.phtml';
			}

			return;
		}

		$URL = '?'.$this->GET_param_run_id.'=' . $run->getId();

		if( Debug::getOutputIsXML() ) {
			echo '<!-- profiler: ' . $URL . ' -->';
		} else {
			if( !Debug::getOutputIsJSON() ) {
				require __DIR__ . '/views/status-bar.phtml';
			}
		}
	}

	protected function catchShowRun() : void
	{
		if( empty( $_GET[$this->GET_param_run_id] ) ) {
			return;
		}


		$run = $this->readRun( $_GET[$this->GET_param_run_id] );

		if( !$run ) {
			return;
		}
		
		
		if(
			($bt_type=$_GET['show_bt']??'') &&
			($bt_id=$_GET['id']??null)!==null
		) {
			$bt = null;
			switch($bt_type) {
				case 'run_block_bt_start':
					$bt = $run->getBlock($bt_id)?->getBacktraceStart();
					break;
				case 'run_block_bt_end':
					$bt = $run->getBlock($bt_id)?->getBacktraceEnd();
					break;
				case 'run_block_message_bt':
					[$block_id, $message_i] = explode(':', $bt_id);
					
					$bt = $run->getBlock( $block_id )?->getMessages()[(int)$message_i]?->getBacktrace();
					
					break;
				case 'run_block_sql_query_bt':
					[$block_id, $q_i] = explode(':', $bt_id);
					
					$bt = $run->getBlock( $block_id )?->getSQLQueries()[(int)$q_i]?->getBacktrace();
					break;
				case 'run_sql_query_bt':
					if(isset($run->getSqlQueries()[(int)$bt_id])) {
						$bt = $run->getSqlQueries()[(int)$bt_id]->getBacktrace();
					}
					break;
			}
			
			if($bt) {
				require __DIR__ . '/views/result/bt.phtml';
			}
			
			die();
		}
		

		if( isset( $_GET['callgraph'] ) ) {
			$this->showCallGraph( $run );
		} else {
			require __DIR__ . '/views/result.phtml';
		}
		die();

	}

	protected function showCallGraph( Debug_Profiler_Run $run ) : void
	{
		/** @noinspection PhpIncludeInspection */
		require_once 'xhprof_lib/utils/xhprof_lib.php';
		/** @noinspection PhpIncludeInspection */
		require_once 'xhprof_lib/utils/callgraph_utils.php';

		ini_set('max_execution_time', 100);
		error_reporting(E_ERROR);

		$threshold = 0.01;
		$source = 'Jet App';
		$description = '';
		$func = '';
		$critical_path = true;
		$type = 'png';


		/** @noinspection PhpUndefinedFunctionInspection */
		/** @noinspection PhpConditionAlreadyCheckedInspection */
		/** @phpstan-ignore function.notFound */
		$dot_script = xhprof_generate_dot_script(
			$run->getXHPData(),
			$threshold,
			$source,
			$description,
			$func,
			$critical_path
		);


		/** @noinspection PhpUndefinedFunctionInspection */
		/** @phpstan-ignore function.notFound */
		$content = xhprof_generate_image_by_dot($dot_script, $type);

		/** @noinspection PhpUndefinedFunctionInspection */
		/** @phpstan-ignore function.notFound */
		xhprof_generate_mime_header($type, strlen($content));
		echo $content;

	}
};

