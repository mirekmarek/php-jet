<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Validator_File extends Validator implements Validator_Part_File_Interface
{
	use Validator_Part_File_Trait;
	
	protected static string $type = self::TYPE_FILE;
	
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_FILE_IS_TOO_LARGE => 'File is too large. Max file size is: %max_file_size%',
		self::ERROR_CODE_DISALLOWED_FILE_TYPE => 'Disallowed file type %file_mime_type%',
	];
	
	public function getErrorCodeScope(): array
	{
		$codes = [];
		$codes[] = static::ERROR_CODE_FILE_IS_TOO_LARGE;
		
		if( $this->is_required ) {
			$codes[] = static::ERROR_CODE_EMPTY;
		}
		
		if( $this->allowed_mime_types ) {
			$codes[] = static::ERROR_CODE_DISALLOWED_FILE_TYPE;
		}
		
		return $codes;
		
	}
}