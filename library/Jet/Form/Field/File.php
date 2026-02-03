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
	
	protected string $_type = Form_Field::TYPE_FILE;
	protected string $_validator_type = Validator::TYPE_FILE;
	protected string $_input_catcher_type = InputCatcher::TYPE_FILE;
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY                => 'Please select file',
		Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE    => 'File is too large',
		Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE => 'Unsupported file type',
	];
	
}