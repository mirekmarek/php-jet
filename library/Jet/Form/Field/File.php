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
class Form_Field_File extends Form_Field implements Form_Field_Part_File_Interface
{
	use Form_Field_Part_File_Trait;
	
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_FILE;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY                => '',
		Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE    => '',
		Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE => '',
	];
	
}