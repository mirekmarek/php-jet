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
trait Form_Field_Part_File_Trait
{
	
	/**
	 * @var array
	 */
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_ARRAY,
		label: 'Allowed mime types',
		getter: 'getAllowedMimeTypes',
		setter: 'setAllowedMimeTypes',
	)]
	protected array $allowed_mime_types = [];
	
	/**
	 * @var null|int
	 */
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_INT,
		label: 'Maximal file size',
		getter: 'getMaximalFileSize',
		setter: 'setMaximalFileSize',
	)]
	protected null|int $maximal_file_size = null;
	
	/**
	 * @var bool
	 */
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_BOOL,
		label: 'Allow multiple upload',
		getter: 'getAllowMultipleUpload',
		setter: 'setAllowMultipleUpload',
	)]
	protected bool $allow_multiple_upload = false;

	
	/**
	 * @var string|array|null
	 */
	protected string|array|null $tmp_file_path = null;
	
	/**
	 * @var string|array|null
	 */
	protected string|array|null $file_name = null;
	
	/**
	 * @var array
	 */
	protected array $multiple_upload_errors = [];
	
	/**
	 * set form instance
	 *
	 * @param Form $form
	 */
	public function setForm( Form $form ): void
	{
		/** @noinspection PhpMultipleClassDeclarationsInspection */
		parent::setForm( $form );
		
		if( !$form->getEnctype() ) {
			$form->setEnctype( Form::ENCTYPE_FORM_DATA );
		}
		$form->setMethod( Form::METHOD_POST );
	}
	
	/**
	 * @return int|null
	 */
	public function getMaximalFileSize(): int|null
	{
		return $this->maximal_file_size;
	}
	
	/**
	 * @param int|null $maximal_file_size
	 */
	public function setMaximalFileSize( int|null $maximal_file_size ): void
	{
		$this->maximal_file_size = $maximal_file_size;
	}
	
	/**
	 * @return array
	 */
	public function getAllowedMimeTypes(): array
	{
		return $this->allowed_mime_types;
	}
	
	/**
	 * @param array $allowed_mime_types
	 */
	public function setAllowedMimeTypes( array $allowed_mime_types ): void
	{
		$this->allowed_mime_types = $allowed_mime_types;
	}
	
	/**
	 * @return string|array
	 */
	public function getFileName(): string|array
	{
		return $this->file_name;
	}
	
	/**
	 * @return bool
	 */
	public function getAllowMultipleUpload(): bool
	{
		return $this->allow_multiple_upload;
	}
	
	/**
	 * @param bool $allow_multiple_upload
	 */
	public function setAllowMultipleUpload( bool $allow_multiple_upload ): void
	{
		$this->allow_multiple_upload = $allow_multiple_upload;
	}
	
	
	/**
	 * @return array
	 */
	public function getMultipleUploadErrors(): array
	{
		return $this->multiple_upload_errors;
	}
	
	/**
	 * @param string $file_name
	 * @param string $error_code
	 */
	public function addMultipleUploadError( string $file_name, string $error_code ): void
	{
		if( !isset( $this->multiple_upload_errors[$file_name] ) ) {
			$this->multiple_upload_errors[$file_name] = [];
		}
		
		$this->multiple_upload_errors[$file_name][$error_code] = $this->getErrorMessage( $error_code );
		
	}
	
	/**
	 * @return string|array
	 */
	public function getTmpFilePath(): string|array
	{
		return $this->tmp_file_path;
	}
	
	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		
		$this->_value = null;
		$this->_has_value = false;
		
		if( array_key_exists( $this->_name, $_FILES ) ) {
			$file_data = $_FILES[$this->_name];
			
			if(
				array_key_exists( 'tmp_name', $file_data ) &&
				array_key_exists( 'name', $file_data )
			) {
				if( !is_array( $file_data['name'] ) ) {
					
					if(
						!empty( $file_data['name'] )
					) {
						$this->_has_value = true;
					}
				} else {
					if(
						count( $file_data['name'] ) > 0 &&
						!empty( $file_data['name'][0] )
					) {
						$this->_has_value = true;
					}
					
				}
			}
		}
		
		
		if( $this->_has_value ) {
			if(
				$this->getAllowMultipleUpload() &&
				is_array( $_FILES[$this->_name]['tmp_name'] )
			) {
				$this->catchInput_multiple();
			} else {
				$this->catchInput_single();
			}
		}
		
	}
	
	/**
	 *
	 */
	protected function catchInput_multiple(): void
	{
		
		if( $this->_has_value ) {
			
			$_files = $_FILES[$this->_name];
			
			$names = $_files['name'];
			$types = $_files['type'];
			$tmp_names = $_files['tmp_name'];
			$errors = $_files['error'];
			$sizes = $_files['size'];
			
			
			$files = [];
			
			foreach( $names as $i => $name ) {
				$files[$i] = [
					'name'     => $name,
					'type'     => $types[$i],
					'tmp_name' => $tmp_names[$i],
					'error'    => $errors[$i],
					'size'     => $sizes[$i]
				];
			}
			
			$this->_value_raw = $files;
			$this->_value = $tmp_names;
			$this->tmp_file_path = $tmp_names;
			$this->file_name = $names;
			
		} else {
			$this->_value_raw = null;
		}
	}
	
	/**
	 *
	 */
	protected function catchInput_single(): void
	{
		
		if( $this->_has_value ) {
			$file_data = $_FILES[$this->_name];
			
			$this->_value_raw = $file_data;
			$this->_value = $file_data['tmp_name'];
			$this->tmp_file_path = $file_data['tmp_name'];
			$this->file_name = $file_data['name'];
			
			if( is_array( $this->tmp_file_path ) ) {
				$this->tmp_file_path = $this->tmp_file_path[0];
				$this->_value = $this->tmp_file_path;
				$this->file_name = $this->file_name[0];
			}
		} else {
			$this->_value_raw = null;
		}
	}
	
	
	protected function validate_checkFileSize( string $path, string $file_name ) : bool
	{
		$res = true;
		
		if( !$path ) {
			$res = false;
		} else {
			if( $this->maximal_file_size ) {
				$file_size = IO_File::getSize( $path );
				if( $file_size > $this->maximal_file_size ) {
					$res = false;
				}
			}
		}
		
		if(
			!$res &&
			$this->allow_multiple_upload
		) {
			$this->addMultipleUploadError( $file_name, static::ERROR_CODE_FILE_IS_TOO_LARGE );
		}
		
		return $res;
	}
	
	protected function validate_checkMimeType ( string $path, string $file_name ) : bool
	{
		if( $this->allowed_mime_types ) {
			if( !in_array(
				IO_File::getMimeType( $path ),
				$this->allowed_mime_types
			) ) {
				if( $this->allow_multiple_upload ) {
					$this->addMultipleUploadError( $file_name, static::ERROR_CODE_DISALLOWED_FILE_TYPE );
				}
				
				return false;
			}
		}
		
		return true;
	}
	
	
	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validate(): bool
	{
		if(
			!$this->_has_value &&
			$this->is_required
		) {
			$this->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}
		
		
		if($this->_has_value) {
			if( is_array( $this->_value ) ) {
				foreach( $this->_value as $i => $path ) {
					if(
						!$this->validate_checkFileSize( $path, $this->file_name[$i] ) ||
						!$this->validate_checkMimeType( $path, $this->file_name[$i] )
					) {
						$this->unsetFile( $i );
					}
				}
			} else {
				if( !$this->validate_checkFileSize( $this->_value, $this->file_name ) ) {
					$this->setError( Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE );
					return false;
				}
				
				if( !$this->validate_checkMimeType( $this->_value, $this->file_name ) ) {
					$this->setError( Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE );
					return false;
				}
			}
		}
		
		if(!$this->validate_validator()) {
			return  false;
		}
		
		$this->setIsValid();
		return true;
		
	}
	
	/**
	 * @param int $index
	 */
	protected function unsetFile( int $index ): void
	{
		unset( $this->_value_raw[$index] );
		unset( $this->_value[$index] );
		unset( $this->tmp_file_path[$index] );
		unset( $this->file_name[$index] );
		
	}
	
	/**
	 * @return string
	 */
	public function render(): string
	{
		if( $this->getIsReadonly() ) {
			return '';
		}
		
		/** @noinspection PhpMultipleClassDeclarationsInspection */
		return parent::render();
	}
	
	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		$codes = [];
		$codes[] = Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE;
		
		if( $this->is_required ) {
			$codes[] = Form_Field::ERROR_CODE_EMPTY;
		}
		
		if( $this->allowed_mime_types ) {
			$codes[] = Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE;
		}
		
		return $codes;
	}
	
	/**
	 * Converts name to HTML ready name
	 *
	 * Example:
	 *
	 * name: /object/property/sub_property
	 *
	 * to: object[property][sub_property]
	 *
	 * @return string
	 */
	public function getTagNameValue(): string
	{
		/** @noinspection PhpMultipleClassDeclarationsInspection */
		$name = parent::getTagNameValue();
		
		if( !str_ends_with( $name, ']' ) ) {
			$name .= '[]';
		}
		
		return $name;
	}
	
}