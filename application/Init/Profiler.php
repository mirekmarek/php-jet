<?php

use Jet\Debug;
use Jet\Debug_Profiler;
use Jet\Debug_Profiler_Run;
use Jet\SysConf_Path;
use Jet\SysConf_Jet;

if( SysConf_Jet::isDebugProfilerEnabled() ) {

	require SysConf_Path::getLibrary().'Jet/Debug/Profiler.php';

	$profiler_save_dir = SysConf_Path::getTmp().'_profiler/';

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
				require SysConf_Path::getBase()."_profiler/result_callgraph.php";
			} else {
				require SysConf_Path::getBase()."_profiler/result.phtml";
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
				chmod( $dir, SysConf_Jet::getIOModDir() );
			}

			file_put_contents( $file_path, serialize( $run ) );
			chmod( $file_path, SysConf_Jet::getIOModDir() );

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

				$show_cache_state = function( string $title, bool $state ) {
					?>
					<table>
					<tr>
						<td colspan="2"><?=$title?></td>
						<td style="background-color: <?=($state?'#009900':'#990000')?>"><?=($state?'yes':'no')?></td>
					</tr>
					</table>
					<?php
				};

				?>
				<div id="__profiler__" style="position: fixed; bottom: 0px;left: 0px;background-color: #c9c9c9;padding: 5px;font-family: Helvetica, Arial, sans-serif;border: 1px inset #ffffff;font-size:14px;">
					<table>
						<tr>
							<td style="padding-right: 20px;">
								<span onclick="document.getElementById('__profiler__').style.display='none';">X</span>
							</td>
							<td style="padding-right: 20px;">
								<a href="<?=$URL?>" target="_blank" style="text-decoration: underline;font-weight: bolder;color: #000000;">PROFILER</a>
							</td>
							<td style="padding-right: 20px;">
								Duration: <b><?=$duration?> ms</b>
							</td>
							<td style="padding-right: 20px;">
								Memory: <b><?=$memory?> Kib</b>
							</td>
							<td style="padding-right: 20px;">
								SQL queries count: <b><?=count($run->getSqlQueries())?></b>
							</td>
							<td><b>Cache state:</b></td>
							<td><?php $show_cache_state('Autoloader', SysConf_Jet::isCacheAutoloaderEnabled() ); ?></td>
							<td><?php $show_cache_state('MVC', SysConf_Jet::isCacheMvcEnabled() ); ?></td>
							<td style="padding-left: 20px"><b>Packager state:</b></td>
							<td><?php $show_cache_state('JS', SysConf_Jet::isJSPackagerEnabled() ); ?></td>
							<td><?php $show_cache_state('CSS', SysConf_Jet::isCSSPackagerEnabled() ); ?></td>
						</tr>
					</table>


				</div>
				<?php

			}
		}
	);

}

