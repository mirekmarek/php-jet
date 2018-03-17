<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Test\Forms;

use Jet\DataModel;
use Jet\Data_DateTime;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Float;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_RegistrationPassword;

/**
 *
 * @JetDataModel:name = 'data_model_test_form_generator'
 * @JetDataModel:database_table_name = 'data_model_test_form_generator'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 */
class DataModelTest_FormGenerator extends DataModel
{

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_label = 'Checkbox'
	 *
	 * @var bool
	 */
	protected $checkbox = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE
	 * @JetDataModel:form_field_label = 'Date: '
	 * @JetDataModel:form_field_error_messages = [Form_Field::ERROR_CODE_INVALID_FORMAT=>'Invalid date format']
	 *
	 * @var Data_DateTime
	 */
	protected $date;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:form_field_label = 'Date and time: '
	 * @JetDataModel:form_field_error_messages = [Form_Field::ERROR_CODE_INVALID_FORMAT=>'Invalid date format']
	 *
	 * @var Data_DateTime
	 */
	protected $date_time;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_FLOAT
	 * @JetDataModel:form_field_label = 'Float: '
	 * @JetDataModel:form_field_min_value = 0
	 * @JetDataModel:form_field_max_value = 999
	 * @JetDataModel:form_field_error_messages = [Form_Field_Float::ERROR_CODE_OUT_OF_RANGE=>'Number is out of range (0-999)']
	 *
	 * @var float
	 */
	protected $float = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_label = 'Int: '
	 * @JetDataModel:form_field_min_value = 0
	 * @JetDataModel:form_field_max_value = 999
	 * @JetDataModel:form_field_error_messages = [Form_Field_Float::ERROR_CODE_OUT_OF_RANGE=>'Number is out of range (0-999)']
	 *
	 * @var int
	 */
	protected $int = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Text: '
	 *
	 * @var string
	 */
	protected $text = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 * @JetDataModel:form_field_label = 'Long text:'
	 *
	 * @var string
	 */
	protected $long_text = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 655360
	 * @JetDataModel:form_field_label = 'WYSIWYG:'
	 * @JetDataModel:form_field_type = Form::TYPE_WYSIWYG
	 *
	 * @var string
	 */
	protected $HTML = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:form_field_label = 'Select: '
	 * @JetDataModel:form_field_type = Form::TYPE_SELECT
	 * @JetDataModel:form_field_get_select_options_callback = ['this', 'getSelectOptions']
	 * @JetDataModel:form_field_error_messages = [Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Invalid value']
	 *
	 * @var string
	 */
	protected $select = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_CUSTOM_DATA
	 * @JetDataModel:form_field_label = 'Multi Select: '
	 * @JetDataModel:form_field_type = Form::TYPE_MULTI_SELECT
	 * @JetDataModel:form_field_get_select_options_callback = ['this', 'getSelectOptions']
	 * @JetDataModel:form_field_error_messages = [Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Invalid value']
	 *
	 * @var array
	 */
	protected $multi_select = [];

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_CUSTOM_DATA
	 * @JetDataModel:form_field_label = 'Radio Button: '
	 * @JetDataModel:form_field_type = Form::TYPE_RADIO_BUTTON
	 * @JetDataModel:form_field_get_select_options_callback = ['this', 'getSelectOptions']
	 * @JetDataModel:form_field_error_messages = [Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Invalid value']
	 *
	 * @var array
	 */
	protected $radio_button = [];

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Password (user registration): '
	 * @JetDataModel:form_field_type = Form::TYPE_REGISTRATION_PASSWORD
	 * @JetDataModel:form_field_options = []
	 * @JetDataModel:form_field_error_messages = [Form_Field_RegistrationPassword::ERROR_CODE_EMPTY=>'Please enter password', Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY=>'Please enter confirm password', Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH=>'Passwords do not match']
	 *
	 * @var string
	 */
	protected $password = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Password: '
	 * @JetDataModel:form_field_type = Form::TYPE_PASSWORD
	 *
	 * @var string
	 */
	protected $password_nc = '';

	/**
	 * @return array
	 */
	public static function getSelectOptions()
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
	public function getLongText()
	{
		return $this->long_text;
	}

	/**
	 * @param string $long_text
	 */
	public function setLongText( $long_text )
	{
		$this->long_text = $long_text;
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 */
	public function setText( $text )
	{
		$this->text = $text;
	}

	/**
	 * @return Data_DateTime
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * @param Data_DateTime $date
	 */
	public function setDate( $date )
	{
		$this->date = $date;
	}

	/**
	 * @return Data_DateTime
	 */
	public function getDateTime()
	{
		return $this->date_time;
	}

	/**
	 * @param Data_DateTime $date_time
	 */
	public function setDateTime( $date_time )
	{
		$this->date_time = $date_time;
	}

	/**
	 * @return string
	 */
	public function getHTML()
	{
		return $this->HTML;
	}

	/**$HTM
	 * @param string $HTML
	 */
	public function setHTML( $HTML )
	{
		$this->HTML = $HTML;
	}

	/**
	 * @return bool
	 */
	public function getCheckbox()
	{
		return $this->checkbox;
	}

	/**
	 * @param bool $checkbox
	 */
	public function setCheckbox( $checkbox )
	{
		$this->checkbox = $checkbox;
	}

	/**
	 * @return float
	 */
	public function getFloat()
	{
		return $this->float;
	}

	/**
	 * @param float $float
	 */
	public function setFloat( $float )
	{
		$this->float = $float;
	}

	/**
	 * @return int
	 */
	public function getInt()
	{
		return $this->int;
	}

	/**
	 * @param int $int
	 */
	public function setInt( $int )
	{
		$this->int = $int;
	}

	/**
	 * @return string
	 */
	public function getSelect()
	{
		return $this->select;
	}

	/**
	 * @param string $select
	 */
	public function setSelect( $select )
	{
		$this->select = $select;
	}

	/**
	 * @return array
	 */
	public function getMultiSelect()
	{
		return $this->multi_select;
	}

	/**
	 * @param array $multi_select
	 */
	public function setMultiSelect( $multi_select )
	{
		$this->multi_select = $multi_select;
	}

	/**
	 * @return array
	 */
	public function getRadioButton()
	{
		return $this->radio_button;
	}

	/**
	 * @param array $radio_button
	 */
	public function setRadioButton( $radio_button )
	{
		$this->radio_button = $radio_button;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword( $password )
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getPasswordNc()
	{
		return $this->password_nc;
	}

	/**
	 * @param string $password_nc
	 */
	public function setPasswordNc( $password_nc )
	{
		$this->password_nc = $password_nc;
	}


}