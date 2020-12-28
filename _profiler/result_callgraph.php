<?php
require_once 'xhprof_lib/utils/xhprof_lib.php';
require_once 'xhprof_lib/utils/callgraph_utils.php';

ini_set('max_execution_time', 100);


$threshold = 0.01;
$source = 'Jet App';
$description = '';
$func = '';
$critical_path = true;
$type = 'png';

/** @noinspection PhpUsageOfSilenceOperatorInspection */
/** @noinspection PhpUndefinedFunctionInspection */
$dot_script = @xhprof_generate_dot_script(
	$GLOBALS['run']->getXHPData(),
	$threshold,
	$source,
	$description,
	$func,
	$critical_path
);

/** @noinspection PhpUsageOfSilenceOperatorInspection */
/** @noinspection PhpUndefinedFunctionInspection */
$content = @xhprof_generate_image_by_dot($dot_script, $type);

/** @noinspection PhpUndefinedFunctionInspection */
xhprof_generate_mime_header($type, strlen($content));
echo $content;
