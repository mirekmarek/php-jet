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
class Form_Field_FileImage extends Form_Field implements Form_Field_Part_File_Interface
{
	use Form_Field_Part_File_Trait;
	
	protected string $_type = Form_Field::TYPE_FILE_IMAGE;
	protected string $_validator_type = Validator::TYPE_FILE_IMAGE;
	protected string $_input_catcher_type = InputCatcher::TYPE_FILE;
	
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY                => 'Please select file',
		Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE    => 'File is too large',
		Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE => 'Unsupported file type',
	];

	/**
	 * @var ?int
	 */
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_INT,
		label: 'Maximal image width',
		getter: 'getMaximalWidth',
		setter: 'setMaximalWidth',
	)]
	protected int|null $maximal_width = null;

	/**
	 * @var ?int
	 */
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_INT,
		label: 'Maximal image height',
		getter: 'getMaximalHeight',
		setter: 'setMaximalHeight',
	)]
	protected int|null $maximal_height = null;
	
	
	#[Form_Definition_FieldOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_BOOL,
		label: 'Automatic resize of image mode enabled',
		getter: 'getAutomaticResizeMode',
		setter: 'setAutomaticResizeMode',
	)]
	protected bool $automatic_resize_mode = true;
	
	
	/**
	 * @return array<string>
	 */
	public function getAllowedMimeTypes(): array
	{
		if(!$this->allowed_mime_types) {
			$this->allowed_mime_types = Data_Image::getSupportedMimeTypes();
		}
		
		return $this->allowed_mime_types;
	}
	
	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->_name = $name;
	}
	
	/**
	 * @param int|null $maximal_width
	 */
	public function setMaximalWidth( ?int $maximal_width ): void
	{
		$this->maximal_width = $maximal_width;
	}
	
	/**
	 * @param int|null $maximal_height
	 */
	public function setMaximalHeight( ?int $maximal_height ): void
	{
		$this->maximal_height = $maximal_height;
	}
	
	

	/**
	 * @param int $maximal_width
	 * @param int $maximal_height
	 */
	public function setMaximalSize( int $maximal_width, int $maximal_height ) : void
	{
		$this->maximal_width = $maximal_width;
		$this->maximal_height = $maximal_height;
	}

	/**
	 * @return int|null
	 */
	public function getMaximalHeight(): int|null
	{
		return $this->maximal_height;
	}

	/**
	 * @return int|null
	 */
	public function getMaximalWidth(): int|null
	{
		return $this->maximal_width;
	}
	
	public function getAutomaticResizeMode(): bool
	{
		return $this->automatic_resize_mode;
	}
	
	public function setAutomaticResizeMode( bool $automatic_resize_mode ): void
	{
		$this->automatic_resize_mode = $automatic_resize_mode;
	}
	
	

	
	public function getValidator() : Validator|Validator_FileImage
	{
		/**
		 * @var Validator_FileImage $validator;
		 */
		$validator = parent::getValidator();
		if($this->getAllowedMimeTypes()) {
			$validator->setAllowedMimeTypes( $this->getAllowedMimeTypes() );
		}
		
		if($this->getMaximalFileSize()) {
			$validator->setMaximalFileSize( $this->getMaximalFileSize() );
		}
		if($this->getMaximalHeight()) {
			$validator->setMaximalHeight( $this->getMaximalHeight() );
		}
		if( $this->getMaximalWidth() ) {
			$validator->setMaximalWidth( $this->getMaximalWidth() );
		}
		$validator->setAutomaticResizeMode( $this->getAutomaticResizeMode() );
		
		return $validator;
	}
	
	
}