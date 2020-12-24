<?php

use Jet\Debug;
use Jet\Debug_Profiler;
use Jet\Debug_Profiler_Run;
use Jet\SysConf_PATH;
use Jet\SysConf_Jet;

if( SysConf_Jet::DEBUG_PROFILER_ENABLED() ) {

	require SysConf_PATH::LIBRARY().'Jet/Debug/Profiler.php';

	$profiler_save_dir = SysConf_PATH::TMP().'_profiler/';

	if( !empty( $_GET['JPR'] ) ) {
		$run_id = $_GET['JPR'];
		$run = null;

		if( !str_contains( $run_id, '.' ) ) {
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
				require SysConf_PATH::BASE()."_profiler/result_callgraph.php";
			} else {
				require SysConf_PATH::BASE()."_profiler/result.phtml";
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
				chmod( $dir, SysConf_Jet::IO_CHMOD_MASK_DIR() );
			}

			file_put_contents( $file_path, serialize( $run ) );
			chmod( $file_path, SysConf_Jet::IO_CHMOD_MASK_DIR() );

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

				$show_cache_state = function( $title, $cache_name ) {
					$getter_name = 'CACHE_'.$cache_name.'_LOAD';
					$load = SysConf_Jet::$getter_name();

					$getter_name = 'CACHE_'.$cache_name.'_SAVE';
					$save = SysConf_Jet::$getter_name();
					?>
					<tr>
						<td colspan="2" style="font-weight: bolder"><?=$title?></td>
					</tr>
					<tr style="background-color: <?=($load?'#009900':'#990000')?>">
						<td>Load:</td>
						<td><?=($load?'yes':'no')?></td>
					</tr>
					<tr style="background-color: <?=($save?'#009900':'#990000')?>">
						<td>Save:</td>
						<td><?=($save?'yes':'no')?></td>
					</tr>
					<?php
				};

				?>
				<div id="__profiler__" style="position: fixed; bottom: 0px;left: 0px;background-color: #c9c9c9;padding: 10px;font-family: Helvetica, Arial, sans-serif;border: 1px inset #ffffff;font-size:14px;">
				<span onclick="document.getElementById('__profiler__').style.display='none';">X</span>&nbsp;&nbsp;&nbsp;
					<a href="<?=$URL?>" target="_blank" style="text-decoration: underline;font-weight: bolder;color: #000000;">PROFILER</a>
					&nbsp;&nbsp;&nbsp;
					Duration: <b><?=$duration?> ms</b>
					&nbsp;&nbsp;&nbsp;
					Memory: <b><?=$memory?> Kib</b>
					&nbsp;&nbsp;&nbsp;
					SQL queries count: <b><?=count($run->getSqlQueries())?></b>
					&nbsp;&nbsp;&nbsp;
					<div id="__profiler__cache_state" style="display: none"><b>Cache settings</b>
						<table style="margin: 10px;">
							<?php
							$show_cache_state('Autoloader', 'AUTOLOADER');
							$show_cache_state('Reflection', 'REFLECTION');
							$show_cache_state('DataModel Definition', 'DATAMODEL_DEFINITION');
							$show_cache_state('Config Definition', 'CONFIG_DEFINITION');
							$show_cache_state('MVC - Sites', 'MVC_SITE');
							$show_cache_state('MVC - Pages', 'MVC_PAGE');
							?>
						</table>
					</div>

				</div>
				<?php

			}
		}
	);

}

