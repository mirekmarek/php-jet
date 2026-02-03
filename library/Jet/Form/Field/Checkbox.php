<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Field_Checkbox extends Form_Field
{
	protected string $_type = Form_Field::TYPE_CHECKBOX;
	protected string $_validator_type = Validator::TYPE_NULL;
	protected string $_input_catcher_type = InputCatcher::TYPE_BOOL;
}