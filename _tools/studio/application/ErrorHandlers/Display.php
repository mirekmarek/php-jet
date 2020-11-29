<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	public function getName() {
		return 'Display';
	}

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error )
	{
		if( Debug::getOutputIsHTML() ) {
			echo $this->display($error);
		} else {
			echo $error->toString();
		}
	}

	/**
	 * @return bool
	 */
	public function errorDisplayed()
	{
		return true;
	}


	/**
	 *
	 * @param  Debug_ErrorHandler_Error $e
	 *
	 */
	public function display( Debug_ErrorHandler_Error $e )
	{

		?>
		<br />
		<div style="background-color: #c9ffc9;padding:5px;border: 1px solid black; font-family: 'Arial CE', Arial, sans-serif;">
			<h2 style="padding:0;margin:0;"><?=static::encode( $e->getTxt() )?></h2>
			<hr/>
			<?=static::encode( $e->getMessage() )?>
			<hr/>
			<table cellSpacing="0" cellPadding="2" border="1" style="border-collapse:collapse;collapse;background-color: #c9c9c9;">
				<tr><td>script:</td><td><?=$e->getFile()?></td></tr>
				<tr><td>line:</td><td><?=$e->getLine()?></td></tr>
				<tr><td>time:</td><td><?=$e->getDate()?> <?=$e->getTime()?></td></tr>
				<tr><td>URL:</td><td><?=static::encode( $e->getRequestURL() )?></td></tr>
			</table><br />


			<?php if( $e->getContext() ): ?>
				<br /><strong>Error context:</strong><br/>
				<table border="1" cellSpacing="0" cellpadding="2" style="border-collapse:collapse;background-color: #999999;">
					<tr><th align="left">Variable</th><th align="left">Value</th></tr>
					<?php
					$i = 0;
					foreach( $e->getContext() as $var_name => $var_value ):
						$row_style = 'background-color:'.( ( $i%2 ? '#f0f0f0' : '#c9c9c9' ) );
						$i++;
						$var_value = Debug_ErrorHandler_Error::formatVariable($var_value);
						?>
						<tr style="<?=$row_style?>"><td valign="top"> $<?=$var_name?></td><td><?=static::encode( $var_value )?></td></tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
			<?php if( $e->getBacktrace() ): ?>

				<br /><strong>Debug backtrace:</strong><br />

				<table border="1" cellSpacing="0" cellpadding="2" style="border-collapse:collapse;background-color: #999999;">
					<tr>
						<th align="left">File</th>
						<th align="left">Line</th>
						<th align="left">Call</th>
					</tr>

					<?php
					$i = 0;
					foreach( $e->getBacktrace() as $d ):
						$row_style = 'background-color:'.( ( $i%2 ? '#f0f0f0' : '#c9c9c9' ) );
						$i++;
						?>
						<tr style="<?=$row_style?>">
							<td valign="top"><?=$d->getFile()?></td>
							<td valign="top"><?=$d->getLine()?></td>
							<td valign="top"><?=self::encode( $d->getCall() )?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>

		</div><br />

		<?php
	}


	/**
	 *
	 * @param string $html
	 *
	 * @return string
	 */
	protected static function encode( $html )
	{
		return nl2br( htmlspecialchars( $html, ENT_QUOTES ) );
	}

}