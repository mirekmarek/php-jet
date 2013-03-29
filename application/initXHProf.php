<?php
if( ($xhprof_available = extension_loaded("xhprof")) ){
	xhprof_enable();

	register_shutdown_function(function() {
		$xhprofData = xhprof_disable();

		require_once JET_PUBLIC_PATH."xhprof_lib/utils/xhprof_lib.php";
		require_once JET_PUBLIC_PATH."xhprof_lib/utils/xhprof_runs.php";

		$xhprof_source = "app_test";
		$xhprofRunId = (new \XHProfRuns_Default())->save_run($xhprofData, $xhprof_source);

		echo "<div><a href=\"/public/xhprof_html/index.php?run={$xhprofRunId}&source={$xhprof_source}\" target=\"_blank\">XHP</a></div>";
	});
}
