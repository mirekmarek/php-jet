<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

use Jet\Debug;
use Jet\Debug_Profiler;
use Jet\Debug_Profiler_Run;
use Jet\SysConf_Path;
use Jet\SysConf_Jet;

require SysConf_Path::getLibrary() . 'Jet/Debug/Profiler.php';

if( SysConf_Jet::isDebugProfilerEnabled() ) {

	$profiler_save_dir = SysConf_Path::getTmp() . '_profiler/';

	if( !empty( $_GET['JPR'] ) ) {
		$run_id = $_GET['JPR'];
		$run = null;

		if( !str_contains( $run_id, '.' ) ) {
			$file_path = $profiler_save_dir . $run_id . '.jpd';

			if( file_exists( $file_path ) ) {

				$run = unserialize( file_get_contents( $file_path ) );

				if(
					!is_object( $run ) ||
					!($run instanceof Debug_Profiler_Run)
				) {
					$run = null;
				}
			}
		}

		if( $run ) {
			if( isset( $_GET['callgraph'] ) ) {
				require SysConf_Path::getBase() . "_profiler/result_callgraph.php";
			} else {
				require SysConf_Path::getBase() . "_profiler/result.phtml";
			}
			die();

		}

	}

	Debug_Profiler::enable(
		function( Debug_Profiler_Run $run ) use ( $profiler_save_dir ) {

			$run_id = $run->getId();

			$dir = $profiler_save_dir;
			$file_path = $dir . $run_id . '.jpd';

			if( !file_exists( $dir ) ) {
				mkdir( $dir );
				chmod( $dir, SysConf_Jet::getIOModDir() );
			}

			file_put_contents( $file_path, serialize( $run ) );
			chmod( $file_path, SysConf_Jet::getIOModDir() );

		},
		function( Debug_Profiler_Run $run ) {
			$URL = '?JPR=' . $run->getId();

			if( Debug::getOutputIsXML() ) {
				echo '<!-- profiler: ' . $URL . ' -->';
			} else {
				if( !Debug::getOutputIsJSON() ) {
					require SysConf_Path::getBase() . "_profiler/status_bar.phtml";
				}
			}

		}
	);

}

