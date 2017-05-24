<?php

use Jet\Debug;
use Jet\Debug_Profiler;
use Jet\Debug_Profiler_Run;

if( JET_DEBUG_PROFILER_ENABLED ) {

	/** @noinspection PhpIncludeInspection */
	require JET_PATH_LIBRARY.'Jet/Debug/Profiler.php';

	$profiler_save_dir = JET_PATH_TMP.'_profiler/';

	if( !empty( $_GET['JPR'] ) ) {
		$run_id = $_GET['JPR'];
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
		function( Debug_Profiler_Run $run ) use ( $profiler_save_dir ) {

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
			$URL = '?JPR='.$run->getId();

			if( Debug::getOutputIsXML() ) {
				echo '<!-- profiler: '.$URL.' -->';
			} elseif( Debug::getOutputIsJSON() ) {
				//echo '//profiler: '.$URL;
			} else {
				$root_block = $run->getBlocks()[0];
				$duration = round($root_block->getDuration()*1000, 2);
				$memory = $root_block->getMemoryUsageDiff()/1024;

				echo '<div style="position: fixed; bottom: 0px;left: 0px;background-color: #c9c9c9;padding: 10px;font-family: Helvetica, Arial, sans-serif;border: 1px inset #ffffff;font-size:14px;">';
				echo '<a href="'.$URL.'" target="_blank" style="text-decoration: underline;font-weight: bolder;color: #000000;">PROFILER</a>';
				echo '&nbsp;&nbsp;&nbsp;';
				echo 'Duration: <b>'.$duration.' ms</b>';
				echo '&nbsp;&nbsp;&nbsp;';
				echo 'Memory: <b>'.$memory.' Kib</b>';
				echo '&nbsp;&nbsp;&nbsp;';
				echo 'SQL queries count: <b>'.count($run->getSqlQueries()).'</b>';
				echo '&nbsp;&nbsp;&nbsp;';
				echo 'Environment: <a href="#" onclick="var el=document.getElementById(\'__profiler__cache_state\');el.style.display=(el.style.display==\'none\'?\'block\':\'none\');return false;" style="text-decoration: underline;font-weight: bolder;color: #000000;">'.JET_CONFIG_ENVIRONMENT.'</a>';


				$show_cache_state = function( $title, $cache_name ) {
					$load = constant('JET_CACHE_'.$cache_name.'_LOAD');
					$save = constant('JET_CACHE_'.$cache_name.'_SAVE');

					echo '<tr>';
					echo '<td colspan="2" style="font-weight: bolder">'.$title.'</td>';
					echo '</tr>';
					echo '<tr style="background-color: '.($load?'#009900':'#990000').'">';
						echo '<td>Load:</td>';
						echo '<td>'.($load?'yes':'no').'</td>';
					echo '</tr>';
					echo '<tr style="background-color: '.($save?'#009900':'#990000').'">';
						echo '<td>Save:</td>';
						echo '<td>'.($save?'yes':'no').'</td>';
					echo '</tr>';

				};

				echo '<div id="__profiler__cache_state" style="display: none"><b>Cache settings</b>';
				echo '<table style="margin: 10px;">';
					$show_cache_state('Autoloader', 'AUTOLOADER');
					$show_cache_state('Reflection', 'REFLECTION');
					$show_cache_state('DataModel Definition', 'DATAMODEL_DEFINITION');
					$show_cache_state('Config Definition', 'CONFIG_DEFINITION');
					$show_cache_state('MVC - Sites', 'MVC_SITE');
					$show_cache_state('MVC - Pages', 'MVC_PAGE');
				echo '</table></div>';

				echo '</div>';


			}
		}
	);

}

