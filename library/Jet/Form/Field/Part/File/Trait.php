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
	 * @return Form_Field_File_UploadedFile[]
	 */
	public function getAllFiles(): array
	{
		return $this->_value_raw;
	}
	
	
	/**
	 * @return Form_Field_File_UploadedFile[]
	 */
	public function getValidFiles(): array
	{
		return $this->_value;
	}
	
	/**
	 * @return Form_Field_File_UploadedFile[]
	 */
	public function getProblematicFiles() : array
	{
		$res = [];
		
		foreach($this->_value_raw as $file) {
			/**
			 * @var Form_Field_File_UploadedFile $file
			 */
			if($file->hasError()) {
				$res[$file->getFileName()] = $file;
			}
		}
		
		return $res;
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
	 *
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		$this->_value = [];
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
		
		$_files = $_FILES[$this->_name];
		
		$names = $_files['name'];
		$tmp_names = $_files['tmp_name'];
		$errors = $_files['error'];
		
		$this->_value_raw = [];
		
		foreach( $names as $i => $name ) {
			$file = new Form_Field_File_UploadedFile(
				file_name: $name,
				tmp_file_path: $tmp_names[$i],
			);
			
			$this->_value_raw[$name] = $file;
			
			if($errors[$i]) {
				$file->setError( '', $errors[$i]);
			} else {
				$this->_value[$name] = $file;
			}
			
		}
	}
	
	/**
	 *
	 */
	protected function catchInput_single(): void
	{

		$file_data = $_FILES[$this->_name];
		
		$name = $file_data['name'];
		
		$file = new Form_Field_File_UploadedFile(
			file_name: $name,
			tmp_file_path: $file_data['tmp_name'],
		);
		
		$files = [
			$name => $file
		];
		
		$this->_value_raw = $files;
		$this->_value = $files;
	}
	
	
	/**
	 * @param Form_Field_File_UploadedFile $file
	 * @return bool
	 * @throws IO_File_Exception
	 */
	protected function validate_checkFileSize( Form_Field_File_UploadedFile $file ) : bool
	{
		
		if( !$file->getTmpFilePath() ) {
			return false;
		} else {
			if( $this->maximal_file_size ) {
				if( $file->getSize() > $this->maximal_file_size ) {
					$file->setError( static::ERROR_CODE_FILE_IS_TOO_LARGE, $this->getErrorMessage(
						static::ERROR_CODE_FILE_IS_TOO_LARGE,
						[
							'file_name' => $file->getFileName(),
							'file_size' => Locale::size( $file->getSize() ),
							'max_file_size' => Locale::size( $this->maximal_file_size)
						]
					) );
					
					return false;
				}
			}
		}

		return true;
	}
	
	/**
	 * @param Form_Field_File_UploadedFile $file
	 * @return bool
	 */
	protected function validate_checkMimeType ( Form_Field_File_UploadedFile $file ) : bool
	{
		$allowed_mime_types = $this->getAllowedMimeTypes();
		
		if( $allowed_mime_types ) {
			$file_mime_type = $file->getMimeType();
			
			if( !in_array(
				$file_mime_type,
				$allowed_mime_types
			) ) {
				$file->setError( static::ERROR_CODE_DISALLOWED_FILE_TYPE, $this->getErrorMessage(
					static::ERROR_CODE_DISALLOWED_FILE_TYPE,
					[
						'file_name' => $file->getFileName(),
						'file_mime_type' => $file_mime_type
					]
				) );
				
				return false;
			}
		}
		
		return true;
	}
	
	
	/**
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

			foreach( $this->_value as $name=>$file ) {
				if(
					!$this->validate_checkFileSize( $file ) ||
					!$this->validate_checkMimeType( $file )
				) {
					unset($this->_value[$name]);
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