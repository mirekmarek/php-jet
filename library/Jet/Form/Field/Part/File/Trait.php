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
	 * @var array<string>
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
	
	protected array $_all_files;
	protected array $_problematic_files;
	protected array $_valid_files;
	
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
	 * @return array<string>
	 */
	public function getAllowedMimeTypes(): array
	{
		return $this->allowed_mime_types;
	}
	
	/**
	 * @param array<string> $allowed_mime_types
	 */
	public function setAllowedMimeTypes( array $allowed_mime_types ): void
	{
		$this->allowed_mime_types = $allowed_mime_types;
	}
	
	/**
	 * @return IO_UploadedFile[]
	 */
	public function getAllFiles(): array
	{
		return $this->_all_files;
	}
	
	
	/**
	 * @return IO_UploadedFile[]
	 */
	public function getValidFiles(): array
	{
		return $this->_valid_files;
	}
	
	/**
	 * @return IO_UploadedFile[]
	 */
	public function getProblematicFiles() : array
	{
		return $this->_problematic_files;
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
	
	
	
	public function validate(): bool
	{
		if(
			!$this->getInputCatcher()->valueExistsInTheInput() &&
			$this->is_required
		) {
			$this->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}
		
		$this->_all_files = [];
		$this->_problematic_files = [];
		$this->_valid_files = [];
		
		
		$validator = $this->getValidator();
		
		if($this->getInputCatcher()->valueExistsInTheInput()) {
			
			foreach( $this->getInputCatcher()->getValue() as $name=>$file ) {
				/**
				 * @var IO_UploadedFile $file
				 */
				
				$this->_all_files[$name] = $file;
				
				if(!$validator->validate( $file->getTmpFilePath() )) {
					$error_data = $validator->getLastErrorData();
					$error_data['file_name'] = $file->getFileName();
					$error_data['file_size'] = Locale::size( $file->getSize() );
					$error_data['max_file_size'] = Locale::size( $this->getMaximalFileSize());
					
					$file->setError(
						$validator->getLastErrorCode(),
						$this->getErrorMessage($validator->getLastErrorCode(), $error_data)
					);
					
					$this->_problematic_files[$name] = $file;
				} else {
					$this->_valid_files[$name] = $file;
				}
				
			}
		}
		
		$this->setIsValid();
		return true;
		
	}
	
	public function getValidator() : Validator|Validator_File
	{
		if(!$this->validator) {
			$this->validator = $this->validatorFactory();
		}
		
		/**
		 * @var Validator_File $validator;
		 */
		$validator = $this->validator;

		if($this->getAllowedMimeTypes()) {
			$validator->setAllowedMimeTypes( $this->getAllowedMimeTypes() );
		}
		
		if($this->getMaximalFileSize()) {
			$validator->setMaximalFileSize( $this->getMaximalFileSize() );
		}
		
		return $validator;
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