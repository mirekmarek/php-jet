<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Debug;
use Jet\Debug_ErrorHandler_Handler;
use Jet\Debug_ErrorHandler_Error;

/**
 *
 */
class ErrorHandler_Display extends Debug_ErrorHandler_Handler
{
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return 'Display';
	}

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error ): void
	{
		if($error->isSilenced()) {
			return;
		}

		if( Debug::getOutputIsHTML() ) {
			$this->display( $error );
		} else {
			echo $error->toString();
		}
	}

	/**
	 * @return bool
	 */
	public function errorDisplayed(): bool
	{
		return true;
	}



	/**
	 *
	 * @param Debug_ErrorHandler_Error $e
	 *
	 */
	public function display( Debug_ErrorHandler_Error $e ): void
	{
		?>
		<style>
			.dbg-error {
				background-color: #c9ffc9;
				padding:5px;
				border: 1px solid black;
				font-family: 'Arial CE', Arial, sans-serif;
			}

			.dbg-error h2 {
				padding:0;
				margin:5px;
			}

			.dbg-error div.error {
				padding: 10px;
				margin-top: 10px;
				margin-bottom: 10px;
				border-top: 1px solid black;
				border-bottom: 1px solid black;
			}

			.dbg-error table.error-info {
				border-collapse:collapse;
				background-color: #c9c9c9;
			}

			.dbg-error table.error-info td {
				padding: 5px;
			}

			.dbg-error table.backtrace  {
				border-collapse:collapse;
				background-color: #999999;
			}

			.dbg-error table.backtrace th {
				text-align: left;
				padding: 5px;
			}

			.dbg-error table.backtrace .row1 td,
			.dbg-error table.backtrace .row2 td {
				padding: 5px;
			}

			.dbg-error table.backtrace .row1 {
				background-color:#f0f0f0;
			}

			.dbg-error table.backtrace .row2 {
				background-color:#c9c9c9;
			}
		</style>
		<br/>
		<div class="dbg-error">
			<h2><?= static::encode( $e->getTxt() ) ?></h2>
			<div class="error">
				<?= static::encode( $e->getMessage() ) ?>
			</div>
			<table class="error-info">
				<tr>
					<td>script:</td>
					<td><?= $e->getFile() ?></td>
				</tr>
				<tr>
					<td>line:</td>
					<td><?= $e->getLine() ?></td>
				</tr>
				<tr>
					<td>time:</td>
					<td><?= $e->getDate() ?> <?= $e->getTime() ?></td>
				</tr>
				<tr>
					<td>URL:</td>
					<td><?= static::encode( $e->getRequestURL() ) ?></td>
				</tr>
			</table>
			<br/>

			<?php if( $e->getBacktrace() ): ?>

				<br/><strong>Debug backtrace:</strong><br/>

				<table class="backtrace">
					<thead>
					<tr>
						<th>File</th>
						<th>Line</th>
						<th>Call</th>
					</tr>

					</thead>

					<tbody>
					<?php
					$i = 0;
					foreach( $e->getBacktrace() as $d ):
						$i++;
						?>
						<tr class="row<?=($i % 2)?1:2?>">
							<td><?= $d->getFile() ?></td>
							<td><?= $d->getLine() ?></td>
							<td><?= self::encode( $d->getCall() ) ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>

		</div><br/>

		<?php
	}


	/**
	 *
	 * @param string $html
	 *
	 * @return string
	 */
	protected static function encode( string $html ): string
	{
		return nl2br( htmlspecialchars( $html, ENT_QUOTES ) );
	}

}