<?php
namespace Jet;

if(!JET_DEBUG_PROFILER_ENABLED) {
	return;
}

/** @noinspection PhpIncludeInspection */
require JET_LIBRARY_PATH . 'Jet/Debug/Profiler.php';

if(
	isset($_GET['JPR']) &&
	!empty($_GET['run'])
) {
	$run = Debug_Profiler::loadRun($_GET['run']);

	if( $run ) {
		if(isset($_GET['callgraph'])) {
			require JET_APPLICATION_PATH."_profiler/result_callgraph.php";
		} else {
			require JET_APPLICATION_PATH."_profiler/result.phtml";
		}
		die();
	}

}

Debug_Profiler::enable();
