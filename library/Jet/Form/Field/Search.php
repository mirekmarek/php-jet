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
class Form_Field_Search extends Form_Field_Input implements Form_Field_Part_RegExp_Interface
{
	use Form_Field_Part_RegExp_Trait;
	
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_SEARCH;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => '',
		Form_Field::ERROR_CODE_INVALID_FORMAT => '',
	];
	
}