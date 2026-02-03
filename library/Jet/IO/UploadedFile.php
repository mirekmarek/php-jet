<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class IO_UploadedFile extends BaseObject
{
	protected string $file_name = '';
	
	protected string $tmp_file_path = '';
	
	/**
	 * @var array<string,string>
	 */
	protected array $errors = [];
	
	public function __construct( string $file_name, string $tmp_file_path )
	{
		$this->file_name = $file_name;
		$this->tmp_file_path = $tmp_file_path;
	}
	
	public function getFileName(): string
	{
		return $this->file_name;
	}
	
	public function getTmpFilePath(): string
	{
		return $this->tmp_file_path;
	}
	

	public function setError( string $error_code, string $error_message ): void
	{
		$this->errors[$error_code] = $error_message;
	}

	public function hasError(): bool
	{
		return count($this->errors)>0;
	}
	
	/**
	 * @return array<string,string>
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}
	
	public function getSize() : int
	{
		return IO_File::getSize( $this->tmp_file_path );
	}
	
	public function getMimeType() : string
	{
		return IO_File::getMimeType( $this->tmp_file_path );
	}
}
