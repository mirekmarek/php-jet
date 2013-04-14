<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Debug
 * @subpackage Debug_Profiler
 */
namespace Jet;

class Debug_Profiler {
	/**
	 * @var bool
	 */
	protected static $enabled = true;

	/**
	 * @var bool
	 */
	protected static $log_SQL_queries = false;

	/**
	 * @var array
	 */
	protected static $sql_queries = array();


	/**
	 * @param bool $log_SQL_queries
	 * @param bool $print_output
	 */
	public static function enable( $log_SQL_queries=true, $print_output=true ) {
		static::$enabled = true;
		static::$log_SQL_queries = $log_SQL_queries;

		if($print_output) {
			//register_shutdown_function( array(get_called_class(), "printSQLQueries") );

			register_shutdown_function( function() {
				ob_start();
				Debug_Profiler::printSQLQueries();
				$result = ob_get_clean();

				$dir = JET_TMP_PATH."profiler/";
				@mkdir($dir);
				@chmod($dir, 0777);
				$file = $dir."pr_".microtime(true).".html";
				@file_put_contents( $file, $result );
				@chmod($file, 0666);

				echo $result;
			} );

		}
	}

	/**
	 * @param string $query
	 */
	public static function SQLQueryStart( $query ) {

		if(!static::$log_SQL_queries) {
			return;
		}

		$backtrace = array();

		foreach(debug_backtrace() as $bt) {
			if(!isset($bt["file"])) {
				$backtrace[] = "?";
			} else {
				$backtrace[] = $bt["file"].":".$bt["line"];
			}
		}

		static::$sql_queries[] = array(
			"query" => $query,
			"backtrace" => $backtrace,
			"time_start" => microtime(true),
			"time_end" => microtime(true),
			"memory_start" => memory_get_usage(),
			"memory_end" => memory_get_usage(),
			"rows_count" => 0

		);
	}

	/**
	 * @param int $rows_count
	 */
	public static function SQLQueryEnd( $rows_count=0 ) {
		if(!static::$log_SQL_queries) {
			return;
		}

		$i = count(static::$sql_queries)-1;
		static::$sql_queries[$i]["time_end"] = microtime(true);
		static::$sql_queries[$i]["rows_count"] = $rows_count;
		static::$sql_queries[$i]["memory_end"] = memory_get_usage();
	}

	/**
	 *
	 */
	public static function printSQLQueries() {
		?>
		<table border="1">
		<?php
		$total_duration = 0;
		$total_rows_count = 0;
		$total_memory_usage = 0;
		foreach(static::$sql_queries as $qd):
			$duration = $qd["time_end"]-$qd["time_start"];
			$memory_usage = $qd["memory_end"]-$qd["memory_start"];
			$total_duration = $total_duration + $duration;
			$total_rows_count = $total_rows_count + $qd["rows_count"];
			$total_memory_usage = $total_memory_usage + $memory_usage;
			?>
			<tr>
				<td><?=$qd["query"];?></td>
				<td><?=$duration;?></td>
				<td><?=$qd["rows_count"];?></td>
				<td><?=$memory_usage / 1024;?>&nbsp;KiB</td>
				<td><?=implode("<br/>\n", $qd["backtrace"]);?></td>
			</tr>
		<?php endforeach; ?>
		        <tr>
		            <td><?=count(static::$sql_queries);?></td>
		            <td><?=$total_duration;?></td>
                            <td><?=$total_rows_count;?></td>
                            <td><?=$total_memory_usage / 1024;?>&nbsp;KiB</td>
		            <td></td>
		        </tr>
		</table>
		<?php
	}
}
