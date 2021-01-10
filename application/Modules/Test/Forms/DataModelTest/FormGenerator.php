<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Test\Forms;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\Data_DateTime;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Float;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_RegistrationPassword;
use Jet\DataModel_IDController_UniqueString;

/**
 *
 */
#[DataModel_Definition(
	name: 'data_model_test_form_generator',
	database_table_name: 'data_model_test_form_generator',
	id_controller_class: DataModel_IDController_UniqueString::class
)]
class DataModelTest_FormGenerator extends DataModel
{

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true
	)]
	protected string $id = '';

	/**
	 * @var bool
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_BOOL,
		form_field_label: 'Checkbox'
	)]
	protected bool $checkbox = false;

	/**
	 * @var ?Data_DateTime
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE,
		form_field_label: 'Date: ',
		form_field_error_messages: [
			Form_Field::ERROR_CODE_INVALID_FORMAT=>'Invalid date format'
		]
	)]
	protected ?Data_DateTime $date = null;

	/**
	 * @var ?Data_DateTime
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME,
		form_field_label: 'Date and time: ',
		form_field_error_messages: [
			Form_Field::ERROR_CODE_INVALID_FORMAT=>'Invalid date format'
		]
	)]
	protected ?Data_DateTime $date_time = null;

	/**
	 * @var float
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_FLOAT,
		form_field_label: 'Float: ',
		form_field_min_value: 0,
		form_field_max_value: 999,
		form_field_error_messages: [
			Form_Field_Float::ERROR_CODE_OUT_OF_RANGE=>'Number is out of range (0-999)'
		]
	)]
	protected float $float = 0;

	/**
	 * @var int
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_INT,
		form_field_label: 'Int: ',
		form_field_min_value: 0,
		form_field_max_value: 999,
		form_field_error_messages: [
			Form_Field_Float::ERROR_CODE_OUT_OF_RANGE=>'Number is out of range (0-999)'
		]
	)]
	protected int $int = 0;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		form_field_label: 'Text: '
	)]
	protected string $text = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 65536,
		form_field_label: 'Long text:'
	)]
	protected string $long_text = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 655360,
		form_field_label: 'WYSIWYG:',
		form_field_type: Form::TYPE_WYSIWYG
	)]
	protected string $HTML = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		form_field_label: 'Select: ',
		form_field_type: Form::TYPE_SELECT,
		form_field_get_select_options_callback: [self::class, 'getSelectOptions'],
		form_field_error_messages: [
			Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Invalid value'
		]
	)]
	protected string $select = '';

	/**
	 * @var array
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_CUSTOM_DATA,
		form_field_label: 'Multi Select: ',
		form_field_type: Form::TYPE_MULTI_SELECT,
		form_field_get_select_options_callback: [self::class, 'getSelectOptions'],
		form_field_error_messages: [
			Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Invalid value'
		]
	)]
	protected array $multi_select = [];

	/**
	 * @var array
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_CUSTOM_DATA,
		form_field_label: 'Radio Button: ',
		form_field_type: Form::TYPE_RADIO_BUTTON,
		form_field_get_select_options_callback: [self::class, 'getSelectOptions'],
		form_field_error_messages: [
			Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Invalid value'
		]
	)]
	protected array $radio_button = [];

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		form_field_label: 'Password (user registration): ',
		form_field_type: Form::TYPE_REGISTRATION_PASSWORD,
		form_field_options: [],
		form_field_error_messages: [
			Form_Field_RegistrationPassword::ERROR_CODE_EMPTY=>'Please enter password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY=>'Please enter confirm password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH=>'Passwords do not match'
		]
	)]
	protected string $password = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		form_field_label: 'Password: ',
		form_field_type: Form::TYPE_PASSWORD
	)]
	protected string $password_nc = '';

	/**
	 * @return array
	 */
	public static function getSelectOptions() : array
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
	 * @return string
	 */
	public function getLongText() : string
	{
		return $this->long_text;
	}

	/**
	 * @param string $long_text
	 */
	public function setLongText( string $long_text ) : void
	{
		$this->long_text = $long_text;
	}

	/**
	 * @return string
	 */
	public function getText() : string
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 */
	public function setText( string $text ) : void
	{
		$this->text = $text;
	}

	/**
	 * @return Data_DateTime|null
	 */
	public function getDate() : Data_DateTime|null
	{
		return $this->date;
	}

	/**
	 * @param ?Data_DateTime $date
	 */
	public function setDate( ?Data_DateTime $date )
	{
		$this->date = $date;
	}

	/**
	 * @return Data_DateTime|null
	 */
	public function getDateTime() : Data_DateTime|null
	{
		return $this->date_time;
	}

	/**
	 * @param ?Data_DateTime $date_time
	 */
	public function setDateTime( ?Data_DateTime $date_time ) : void
	{
		$this->date_time = $date_time;
	}

	/**
	 * @return string
	 */
	public function getHTML() : string
	{
		return $this->HTML;
	}

	/**$HTM
	 * @param string $HTML
	 */
	public function setHTML( string $HTML ) : void
	{
		$this->HTML = $HTML;
	}

	/**
	 * @return bool
	 */
	public function getCheckbox() : bool
	{
		return $this->checkbox;
	}

	/**
	 * @param bool $checkbox
	 */
	public function setCheckbox( bool $checkbox ) : void
	{
		$this->checkbox = $checkbox;
	}

	/**
	 * @return float
	 */
	public function getFloat() : float
	{
		return $this->float;
	}

	/**
	 * @param float $float
	 */
	public function setFloat( float $float ) : void
	{
		$this->float = $float;
	}

	/**
	 * @return int
	 */
	public function getInt() : int
	{
		return $this->int;
	}

	/**
	 * @param int $int
	 */
	public function setInt( int $int ) : void
	{
		$this->int = $int;
	}

	/**
	 * @return string
	 */
	public function getSelect() : string
	{
		return $this->select;
	}

	/**
	 * @param string $select
	 */
	public function setSelect( string $select ) : void
	{
		$this->select = $select;
	}

	/**
	 * @return array
	 */
	public function getMultiSelect() : array
	{
		return $this->multi_select;
	}

	/**
	 * @param array $multi_select
	 */
	public function setMultiSelect( array $multi_select ) : void
	{
		$this->multi_select = $multi_select;
	}

	/**
	 * @return array
	 */
	public function getRadioButton() : array
	{
		return $this->radio_button;
	}

	/**
	 * @param array $radio_button
	 */
	public function setRadioButton( array $radio_button ) : void
	{
		$this->radio_button = $radio_button;
	}

	/**
	 * @return string
	 */
	public function getPassword() : string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword( string $password ) : void
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getPasswordNc() : string
	{
		return $this->password_nc;
	}

	/**
	 * @param string $password_nc
	 */
	public function setPasswordNc( string $password_nc ) : void
	{
		$this->password_nc = $password_nc;
	}


}