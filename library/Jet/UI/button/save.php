<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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