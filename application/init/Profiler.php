<?php

use Jet\Debug;
use Jet\Debug_Profiler;
use Jet\Debug_Profiler_Run;

if( JET_DEBUG_PROFILER_ENABLED ) {

	/** @noinspection PhpIncludeInspection */
	require JET_PATH_LIBRARY.'Jet/Debug/Profiler.php';

	$profiler_save_dir = JET_PATH_TMP.'_profiler/';

	if(
		isset( $_GET['JPR'] ) &&
		!empty( $_GET['run'] )
	) {
		$run_id = $_GET['run'];
		$run = null;

		if( strpos( $run_id, '.' )===false ) {
			$file_path = $profiler_save_dir.$run_id.'.jpd';

			if( file_exists( $file_path ) ) {

				$run = unserialize( file_get_contents( $file_path ) );

				if(
					!is_object( $run ) ||
					!( $run instanceof Debug_Profiler_Run )
				) {
					$run = null;
				}
			}
		}


		if($run) {
			if( isset( $_GET['callgraph'] ) ) {
				/** @noinspection PhpIncludeInspection */
				require JET_PATH_BASE."_profiler/result_callgraph.php";
			} else {
				/** @noinspection PhpIncludeInspection */
				require JET_PATH_BASE."_profiler/result.phtml";
			}
			die();

		}



	}

	Debug_Profiler::enable(
		true,
		function( Debug_Profiler_Run $run ) use ($profiler_save_dir) {

			$run_id = $run->getId();

			$dir = $profiler_save_dir;
			$file_path = $dir.$run_id.'.jpd';

			if( !file_exists( $dir ) ) {
				mkdir( $dir );
				chmod( $dir, JET_IO_CHMOD_MASK_DIR );
			}

			file_put_contents( $file_path, serialize( $run ) );
			chmod( $file_path, JET_IO_CHMOD_MASK_FILE );

		},
		function( Debug_Profiler_Run $run ) {
			$URL = '?JPR&run='.$run->getId();

			if( Debug::getOutputIsXML() ) {
				echo '<!-- profiler: '.$URL.' -->';
			} elseif( Debug::getOutputIsJSON() ) {
				//echo '//profiler: '.$URL;
			} else {
				echo '<div style="position: fixed; bottom: 0px;left: 0px;"><a href="'.$URL.'" target="_blank">profiler</a></div>';
			}

		}
	);

}

