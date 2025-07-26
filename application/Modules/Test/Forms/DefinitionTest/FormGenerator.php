<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\Forms;

use Jet\BaseObject;

use Jet\Data_DateTime;

use Jet\Form_Definition;
use Jet\Form_Definition_Interface;
use Jet\Form_Definition_Trait;

use Jet\Form_Field;
use Jet\Locale;


class DefinitionTest_FormGenerator extends BaseObject implements Form_Definition_Interface
{
	
	use Form_Definition_Trait;

	/**
	 * @var bool
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_CHECKBOX,
		label: 'Checkbox'
	)]
	protected bool $checkbox = false;

	/**
	 * @var ?Data_DateTime
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_DATE,
		label: 'Date: ',
		error_messages: [
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid date format'
		]
	)]
	protected ?Data_DateTime $date = null;

	/**
	 * @var ?Data_DateTime
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_DATE_TIME,
		label: 'Date and time: ',
		error_messages: [
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid date format'
		]
	)]
	protected ?Data_DateTime $date_time = null;

	/**
	 * @var float
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_FLOAT,
		label: 'Float:',
		is_required: false,
		min_value: 0,
		max_value: 999,
		step: 0.0001,
		places: 4,
		error_messages: [
			Form_Field::ERROR_CODE_OUT_OF_RANGE => 'Number is out of range (0-999)'
		]
	)]
	protected float $float = 0;

	/**
	 * @var int
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_INT,
		label: 'Int: ',
		help_text: 'Any number ...',
		min_value: 0,
		max_value: 999,
		error_messages: [
			Form_Field::ERROR_CODE_OUT_OF_RANGE => 'Number is out of range (0-999)'
		]
	)]
	protected int $int = 0;

	/**
	 * @var string
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Text: '
	)]
	protected string $text = '';

	/**
	 * @var string
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_TEXTAREA,
		label: 'Long text:'
	)]
	protected string $long_text = '';

	/**
	 * @var string
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_WYSIWYG,
		label: 'WYSIWYG:',
	)]
	protected string $HTML = '';

	/**
	 * @var string
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_SELECT,
		label: 'Select: ',
		select_options_creator: [
			self::class,
			'getSelectOptions'
		],
		error_messages: [
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid value'
		]
	)]
	protected string $select = '';

	/**
	 * @var array
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_MULTI_SELECT,
		label: 'Multi Select: ',
		select_options_creator: [
			self::class,
			'getSelectOptions'
		],
		error_messages: [
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid value'
		]
	)]
	protected array $multi_select = [];

	/**
	 * @var string
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_RADIO_BUTTON,
		label: 'Radio Button: ',
		select_options_creator: [
			self::class,
			'getSelectOptions'
		],
		error_messages: [
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid value'
		]
	)]
	protected string $radio_button = '';

	/**
	 * @var string
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_PASSWORD,
		label: 'Password: ',
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter password',
		]
	)]
	protected string $password = '';
	
	
	/**
	 * @var string
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_FILE,
		label: 'File: ',
		error_messages: [
			Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE    => 'File is too large',
			Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE => 'Unsupported file type',
		]
	)]
	protected string $file = '';
	
	/**
	 * @var string
	 */
	#[Form_Definition(
		type: Form_Field::TYPE_FILE_IMAGE,
		label: 'Image: ',
		error_messages: [
			Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE    => 'File is too large',
			Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE => 'Unsupported file type',
		]
	)]
	protected string $file_image = '';
	
	
	/**
	 * @var DefinitionTest_FormGenerator_Sub1[]
	 */
	#[Form_Definition(
		is_sub_forms: true
	)]
	protected array $sub_entities = [];
	
	/**
	 * @var DefinitionTest_FormGenerator_Sub2
	 */
	#[Form_Definition(
		is_sub_form: true
	)]
	protected DefinitionTest_FormGenerator_Sub2 $sub_entity;
	
	/**
	 * @var string
	 */
	protected string $no_field = '';

	/**
	 * @return array
	 */
	public static function getSelectOptions(): array
	{
		return [
			'value1' => 'Option 1',
			'value2' => 'Option 2',
			'value3' => 'Option 3',
			'value4' => 'Option 4',
			'value5' => 'Option 5',
		];
	}
	
	/**
	 * @return Locale[]
	 */
	public static function getLocales() : array
	{
		$res = [];
		
		$res[] = new Locale('cs_CZ');
		$res[] = new Locale('en_EU');
		$res[] = new Locale('de_DE');
		$res[] = new Locale('sk_SK');
		
		return $res;
	}
	
	/**
	 *
	 */
	public function __construct()
	{
		foreach(static::getLocales() as $locale) {
			$this->sub_entities[$locale->toString()] = new DefinitionTest_FormGenerator_Sub1();
		}
		
		$this->sub_entity = new DefinitionTest_FormGenerator_Sub2();
	}
	
	/**
	 * @return string
	 */
	public function getLongText(): string
	{
		return $this->long_text;
	}

	/**
	 * @param string $long_text
	 */
	public function setLongText( string $long_text ): void
	{
		$this->long_text = $long_text;
	}

	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 */
	public function setText( string $text ): void
	{
		$this->text = $text;
	}

	/**
	 * @return Data_DateTime|null
	 */
	public function getDate(): Data_DateTime|null
	{
		return $this->date;
	}

	/**
	 * @param ?Data_DateTime $date
	 */
	public function setDate( ?Data_DateTime $date ) : void
	{
		$this->date = $date;
	}

	/**
	 * @return Data_DateTime|null
	 */
	public function getDateTime(): Data_DateTime|null
	{
		return $this->date_time;
	}

	/**
	 * @param ?Data_DateTime $date_time
	 */
	public function setDateTime( ?Data_DateTime $date_time ): void
	{
		$this->date_time = $date_time;
	}

	/**
	 * @return string
	 */
	public function getHTML(): string
	{
		return $this->HTML;
	}

	/**$HTM
	 * @param string $HTML
	 */
	public function setHTML( string $HTML ): void
	{
		$this->HTML = $HTML;
	}

	/**
	 * @return bool
	 */
	public function getCheckbox(): bool
	{
		return $this->checkbox;
	}

	/**
	 * @param bool $checkbox
	 */
	public function setCheckbox( bool $checkbox ): void
	{
		$this->checkbox = $checkbox;
	}

	/**
	 * @return float
	 */
	public function getFloat(): float
	{
		return $this->float;
	}

	/**
	 * @param float $float
	 */
	public function setFloat( float $float ): void
	{
		$this->float = $float;
	}

	/**
	 * @return int
	 */
	public function getInt(): int
	{
		return $this->int;
	}

	/**
	 * @param int $int
	 */
	public function setInt( int $int ): void
	{
		$this->int = $int;
	}

	/**
	 * @return string
	 */
	public function getSelect(): string
	{
		return $this->select;
	}

	/**
	 * @param string $select
	 */
	public function setSelect( string $select ): void
	{
		$this->select = $select;
	}

	/**
	 * @return array
	 */
	public function getMultiSelect(): array
	{
		return $this->multi_select;
	}

	/**
	 * @param array $multi_select
	 */
	public function setMultiSelect( array $multi_select ): void
	{
		$this->multi_select = $multi_select;
	}

	/**
	 * @return string
	 */
	public function getRadioButton(): string
	{
		return $this->radio_button;
	}

	/**
	 * @param ?string $value
	 */
	public function setRadioButton( ?string $value ): void
	{
		$this->radio_button = $value ? : '';
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword( string $password ): void
	{
		$this->password = password_hash( $password, PASSWORD_DEFAULT );
	}
	
	/**
	 * @return DefinitionTest_FormGenerator_Sub1[]
	 */
	public function getSubEntities(): array
	{
		return $this->sub_entities;
	}
	
	/**
	 * @return DefinitionTest_FormGenerator_Sub2
	 */
	public function getSubEntity(): DefinitionTest_FormGenerator_Sub2
	{
		return $this->sub_entity;
	}
	
	public function getFile(): string
	{
		return $this->file;
	}
	
	public function setFile( string $file ): void
	{
		$this->file = $file;
	}
	
	public function getFileImage(): string
	{
		return $this->file_image;
	}
	
	public function setFileImage( string $file_image ): void
	{
		$this->file_image = $file_image;
	}

	
	
}