<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package application bootstrap
 */
if( ($xhprof_available = extension_loaded('xhprof')) ){
	if(isset($_GET['XHP'])) {
		require_once 'xhprof_lib/utils/xhprof_lib.php';
		require_once 'xhprof_lib/utils/callgraph_utils.php';
		require_once 'xhprof_lib/utils/xhprof_runs.php';
		require_once 'xhprof_lib/display/xhprof.php';

		if(isset($_GET['graph'])) {
			ini_set('max_execution_time', 100);

			$params = array(// run id param
				'run' => array(XHPROF_STRING_PARAM, ''),

				// source/namespace/type of run
				'source' => array(XHPROF_STRING_PARAM, 'xhprof'),

				// the focus function, if it is set, only directly
				// parents/children functions of it will be shown.
				'func' => array(XHPROF_STRING_PARAM, ''),

				// image type, can be 'jpg', 'gif', 'ps', 'png'
				'type' => array(XHPROF_STRING_PARAM, 'png'),

				// only functions whose exclusive time over the total time
				// is larger than this threshold will be shown.
				// default is 0.01.
				'threshold' => array(XHPROF_FLOAT_PARAM, 0.01),

				// whether to show critical_path
				'critical' => array(XHPROF_BOOL_PARAM, true),

				// first run in diff mode.
				'run1' => array(XHPROF_STRING_PARAM, ''),

				// second run in diff mode.
				'run2' => array(XHPROF_STRING_PARAM, '')
			);

// pull values of these params, and create named globals for each param
			xhprof_param_init($params);

// if invalid value specified for threshold, then use the default
			if ($threshold < 0 || $threshold > 1) {
				$threshold = $params['threshold'][1];
			}

// if invalid value specified for type, use the default
			if (!array_key_exists($type, $xhprof_legal_image_types)) {
				$type = $params['type'][1]; // default image type.
			}

			$xhprof_runs_impl = new XHProfRuns_Default( JET_TMP_PATH );

			if (!empty($run)) {
				// single run call graph image generation
				xhprof_render_image($xhprof_runs_impl, $run, $type,
					$threshold, $func, $source, $critical);
			} else {
				// diff report call graph image generation
				xhprof_render_diff_image($xhprof_runs_impl, $run1, $run2,
					$type, $threshold, $source);
			}

		}

		$params = array('run'        => array(XHPROF_STRING_PARAM, ''),
		                'wts'        => array(XHPROF_STRING_PARAM, ''),
		                'symbol'     => array(XHPROF_STRING_PARAM, ''),
		                'sort'       => array(XHPROF_STRING_PARAM, 'wt'), // wall time
		                'run1'       => array(XHPROF_STRING_PARAM, ''),
		                'run2'       => array(XHPROF_STRING_PARAM, ''),
		                'source'     => array(XHPROF_STRING_PARAM, 'xhprof'),
		                'all'        => array(XHPROF_UINT_PARAM, 0),
		);

		xhprof_param_init($params);

		foreach ($params as $k => $v) {
			$params[$k] = $$k;

			if ($params[$k] == $v[1]) {
				unset($params[$k]);
			}
		}
		$base_path = '?XHP&';

		?>
		<html>

		<head>
		<title>XHProf: Hierarchical Profiler Report</title>
		<style type='text/css'>
		/*  Copyright (c) 2009 Facebook
		*
		*  Licensed under the Apache License, Version 2.0 (the 'License');
		*  you may not use this file except in compliance with the License.
		*  You may obtain a copy of the License at
		*
		*      http://www.apache.org/licenses/LICENSE-2.0
		*
		*  Unless required by applicable law or agreed to in writing, software
		*  distributed under the License is distributed on an 'AS IS' BASIS,
		*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
		*  See the License for the specific language governing permissions and
		*  limitations under the License.
		*/

		td.sorted {
		color:#0000FF;
		}

		td.vbar, th.vbar {
		text-align: right;
		border-left:
		solid 1px #bdc7d8;
		}

		td.vbbar, th.vbar {
		text-align: right;
		border-left:
		solid 1px #bdc7d8;
		color:blue;
		}

		/* diff reports: display regressions in red */
		td.vrbar {
		text-align: right;
		border-left:solid 1px #bdc7d8;
		color:red;
		}

		/* diff reports: display improvements in green */
		td.vgbar {
		text-align: right;
		border-left: solid 1px #bdc7d8;
		color:green;
		}

		td.vwbar, th.vwbar {
		text-align: right;
		border-left: solid 1px white;
		}

		td.vwlbar, th.vwlbar {
		text-align: left;
		border-left: solid 1px white;
		}

		p.blue  {
		color:blue
		}

		.bubble {
		background-color:#C3D9FF
		}

		ul.xhprof_actions {
		float: right;
		padding-left: 16px;
		list-style-image: none;
		list-style-type: none;
		margin:10px 10px 10px 3em;
		position:relative;
		}

		ul.xhprof_actions li {
		border-bottom:1px solid #D8DFEA;
		}

		ul.xhprof_actions li a:hover {
		background:#3B5998 none repeat scroll 0 0;
		color:#FFFFFF;
		}
		</style>
		</head>

		<body>

		<?php
		$vbar  = ' class="vbar"';
		$vwbar = ' class="vwbar"';
		$vwlbar = ' class="vwlbar"';
		$vbbar = ' class="vbbar"';
		$vrbar = ' class="vrbar"';
		$vgbar = ' class="vgbar"';


		$xhprof_runs_impl = new XHProfRuns_Default( JET_TMP_PATH );

		displayXHProfReport($xhprof_runs_impl, $params, $source, $run, $wts,
			$symbol, $sort, $run1, $run2);
		?>

		</body>
		</html>
		<?php
		die();
	}

	xhprof_enable();

	register_shutdown_function(function() {
		$xhprof_data = xhprof_disable();
		$xhprof_source = 'jet_app';

		require_once 'xhprof_lib/utils/xhprof_lib.php';
		require_once 'xhprof_lib/utils/xhprof_runs.php';

		$xhprofRunId = (new \XHProfRuns_Default( JET_TMP_PATH ))->save_run($xhprof_data, $xhprof_source);

		echo '<div><a href=\'?XHP&run={$xhprofRunId}&source={$xhprof_source}\' target=\'_blank\'>XHP</a></div>';
	});
}
