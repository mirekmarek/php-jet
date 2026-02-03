<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class InputCatcher_File extends InputCatcher
{
	protected string $_type = self::TYPE_FILE;
	
	
	public function catchInput( array|Data_Array $data ): void
	{
		$this->value = [];
		$this->value_exists_in_the_input = false;
		
		if( array_key_exists( $this->name, $_FILES ) ) {
			$file_data = $_FILES[$this->name];
			
			if(
				array_key_exists( 'tmp_name', $file_data ) &&
				array_key_exists( 'name', $file_data )
			) {
				if( !is_array( $file_data['name'] ) ) {
					
					if(
						!empty( $file_data['name'] )
					) {
						$this->value_exists_in_the_input = true;
					}
				} else {
					if(
						count( $file_data['name'] ) > 0 &&
						!empty( $file_data['name'][0] )
					) {
						$this->value_exists_in_the_input = true;
					}
					
				}
			}
		}
		
		
		if( $this->value_exists_in_the_input ) {
			if(
				is_array( $_FILES[$this->name]['tmp_name'] )
			) {
				$this->catchInput_multiple();
			} else {
				$this->catchInput_single();
			}
		}
		
	}
	
	protected function catchInput_multiple(): void
	{
		
		$_files = $_FILES[$this->name];
		
		$names = $_files['name'];
		$tmp_names = $_files['tmp_name'];
		$errors = $_files['error'];
		
		$this->value_raw = [];
		
		foreach( $names as $i => $name ) {
			$file = new IO_UploadedFile(
				file_name: $name,
				tmp_file_path: $tmp_names[$i],
			);
			
			$this->value_raw[$name] = $file;
			
			if($errors[$i]) {
				$file->setError( '', $errors[$i]);
			} else {
				$this->value[$name] = $file;
			}
			
		}
	}
	
	protected function catchInput_single(): void
	{
		
		$file_data = $_FILES[$this->name];
		
		$name = $file_data['name'];
		
		$file = new IO_UploadedFile(
			file_name: $name,
			tmp_file_path: $file_data['tmp_name'],
		);
		
		$files = [
			$name => $file
		];
		
		$this->value_raw = $files;
		$this->value = $files;
	}
	
	/**
	 * @return array<IO_UploadedFile>
	 */
	public function getValueRaw() : array
	{
		return $this->value_raw;
	}
	
	/**
	 * @return array<IO_UploadedFile>
	 */
	public function getValue() : array
	{
		return $this->value;
	}
	
	protected function checkValue(): void
	{
	}
	
	/**
	 * @param string $name
	 * @return array<IO_UploadedFile>
	 */
	public static function catchUploadedFiles( string $name ) : array
	{
		$catcher = new static( $name, [] );
		$catcher->catchInput([]);
		
		return $catcher->getValue();
	}
}