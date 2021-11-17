<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

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
		<br/>
		<div style="background-color: #c9ffc9;padding:5px;border: 1px solid black; font-family: 'Arial CE', Arial, sans-serif;">
			<h2 style="padding:0;margin:0;"><?= static::encode( $e->getTxt() ) ?></h2>
			<hr/>
			<?= static::encode( $e->getMessage() ) ?>
			<hr/>
			<table style="border-collapse:collapse;collapse;background-color: #c9c9c9;">
				<tr>
					<td style="padding: 2px;">script:</td>
					<td style="padding: 2px;"><?= $e->getFile() ?></td>
				</tr>
				<tr>
					<td style="padding: 2px;">line:</td>
					<td style="padding: 2px;"><?= $e->getLine() ?></td>
				</tr>
				<tr>
					<td style="padding: 2px;">time:</td>
					<td style="padding: 2px;"><?= $e->getDate() ?> <?= $e->getTime() ?></td>
				</tr>
				<tr>
					<td style="padding: 2px;">URL:</td>
					<td style="padding: 2px;"><?= static::encode( $e->getRequestURL() ) ?></td>
				</tr>
			</table>
			<br/>

			<?php if( $e->getBacktrace() ): ?>

				<br/><strong>Debug backtrace:</strong><br/>

				<table style="border-collapse:collapse;background-color: #999999;">
					<tr>
						<th style="text-align: left;padding: 5px;">File</th>
						<th style="text-align: left;padding: 5px;">Line</th>
						<th style="text-align: left;padding: 5px;">Call</th>
					</tr>

					<?php
					$i = 0;
					foreach( $e->getBacktrace() as $d ):
						$row_style = 'background-color:' . (($i % 2 ? '#f0f0f0' : '#c9c9c9'));
						$i++;
						?>
						<tr style="<?= $row_style ?>">
							<td style="text-align: left;padding: 5px;vertical-align: top"><?= $d->getFile() ?></td>
							<td style="text-align: left;padding: 5px;vertical-align: top"><?= $d->getLine() ?></td>
							<td style="text-align: left;padding: 5px;vertical-align: top"><?= self::encode( $d->getCall() ) ?></td>
						</tr>
					<?php endforeach; ?>
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