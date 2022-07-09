<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


class Form_Field_File_UploadedFile extends BaseObject
{
	protected string $file_name = '';
	
	protected string $tmp_file_path = '';
	
	protected array $errors = [];
	
	/**
	 * @param string $file_name
	 * @param string $tmp_file_path
	 */
	public function __construct( string $file_name, string $tmp_file_path )
	{
		$this->file_name = $file_name;
		$this->tmp_file_path = $tmp_file_path;
	}
	
	/**
	 * @return string
	 */
	public function getFileName(): string
	{
		return $this->file_name;
	}
	
	/**
	 * @return string
	 */
	public function getTmpFilePath(): string
	{
		return $this->tmp_file_path;
	}
	
	
	/**
	 * @param string $error_code
	 * @param string $error_message
	 */
	public function setError( string $error_code, string $error_message ): void
	{
		$this->errors[$error_code] = $error_message;
	}
	
	/**
	 * @return bool
	 */
	public function hasError(): bool
	{
		return count($this->errors)>0;
	}
	
	/**
	 * @return array
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}
	
	
	/**
	 * @return int
	 *
	 * @throws IO_File_Exception
	 */
	public function getSize() : int
	{
		return IO_File::getSize( $this->tmp_file_path );
	}
	
	/**
	 * @return string
	 */
	public function getMimeType() : string
	{
		return IO_File::getMimeType( $this->tmp_file_path );
	}
}
