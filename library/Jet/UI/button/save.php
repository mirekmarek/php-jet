<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
class UI_button_save extends UI_button
{

	/**
	 * @var string
	 */
	protected string $type = 'submit';

	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'button/save';

}