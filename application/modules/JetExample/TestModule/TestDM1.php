<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\TestModule;

use Jet\DataModel;
use Jet\Data_DateTime;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Float;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_RegistrationPassword;

/**
 *
 * @JetDataModel:name = 'DataModelT1'
 * @JetDataModel:database_table_name = 'JetApplicationModule_TestModule_DataModelT1'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 */
class TestDM1 extends DataModel
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
	 * @JetDataModel:type = DataModel::TYPE_ARRAY
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
	 * @JetDataModel:type = DataModel::TYPE_ARRAY
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
			'value1' => 'Option 1', 'value2' => 'Option 2', 'value3' => 'Option 3', 'value4' => 'Option 4',
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


}