<?php
namespace Jet;

if( !JET_DEBUG_PROFILER_ENABLED ) {
	return;
}

/** @noinspection PhpIncludeInspection */
require JET_PATH_LIBRARY.'Jet/Debug/Profiler.php';

if( isset( $_GET['JPR'] )&&!empty( $_GET['run'] ) ) {
	$run = Debug_Profiler::loadRun( $_GET['run'] );

	if( $run ) {
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

Debug_Profiler::enable();
