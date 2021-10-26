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
class Form_Field_Search extends Form_Field_Input
{
	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_SEARCH;

	/**
	 * @var array
	 */
	protected array $error_messages = [];


}