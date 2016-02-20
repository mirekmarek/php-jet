<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\TestModule;
use Jet;
use Jet\DataModel;
use Jet\Data_DateTime;

/**
 * Class DataModelT1
 *
 * @JetDataModel:name = 'DataModelT1'
 * @JetDataModel:database_table_name = 'JetApplicationModule_TestModule_DataModelT1'
 * @JetDataModel:ID_class_name = 'DataModel_ID_UniqueString'
 */
class DataModelT1 extends DataModel {

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_label = 'Checkbox: '
	 *
	 * @var bool
	 */
	protected $checkbox = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE
	 * @JetDataModel:form_field_label = 'Date: '
	 * @JetDataModel:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_INVALID_FORMAT=>'Invalid date format']
	 *
	 * @var Data_DateTime
	 */
	protected $date;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:form_field_label = 'Date and time: '
	 * @JetDataModel:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_INVALID_FORMAT=>'Invalid date format']
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
	protected $multi_select = [
	];

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
	 * @JetDataModel:form_field_label = 'Password: '
	 * @JetDataModel:form_field_type = Form::TYPE_PASSWORD
	 * @JetDataModel:form_field_options = ['disable_check' => false]
     * @JetDataModel:form_field_error_messages = [Form_Field_Password::ERROR_CODE_EMPTY=>'Please type password', Form_Field_Password::ERROR_CODE_CHECK_EMPTY=>'Please type confirm password', Form_Field_Password::ERROR_CODE_CHECK_NOT_MATCH=>'Passwords do not match']
	 *
	 * @var string
	 */
	protected $password = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Password (no check field): '
	 * @JetDataModel:form_field_type = Form::TYPE_PASSWORD
	 * @JetDataModel:form_field_options = ['disable_check' => true]
	 *
	 * @var string
	 */
	protected $password_nc = '';


	/**
	 * @param string $long_text
	 */
	public function setLongText($long_text) {
		$this->long_text = $long_text;
	}

	/**
	 * @return string
	 */
	public function getLongText() {
		return $this->long_text;
	}

	/**
	 * @param string $text
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}



	/**
	 * @param Data_DateTime $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @return Data_DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param Data_DateTime $date_time
	 */
	public function setDateTime($date_time) {
		$this->date_time = $date_time;
	}

	/**
	 * @return Data_DateTime
	 */
	public function getDateTime() {
		return $this->date_time;
	}

	/**$HTM
	 * @param string $HTML
	 */
	public function setHTML($HTML) {
		$this->HTML = $HTML;
	}

	/**
	 * @return string
	 */
	public function getHTML() {
		return $this->HTML;
	}

	public static function getSelectOptions() {
		return [
				'value1' => 'Option 1',
				'value2' => 'Option 2',
				'value3' => 'Option 3',
				'value4' => 'Option 4',
				'value5' => 'Option 5',
		];
	}


}