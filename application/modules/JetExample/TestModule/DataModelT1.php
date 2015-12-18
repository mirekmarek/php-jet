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
use Jet\DateTime;

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
	 *
	 * @var DateTime
	 */
	protected $date;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:form_field_label = 'Date and time: '
	 *
	 * @var DateTime
	 */
	protected $date_time;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_FLOAT
	 * @JetDataModel:form_field_label = 'Float: '
	 * @JetDataModel:min_value = 0
	 * @JetDataModel:max_value = 999
	 *
	 * @var float
	 */
	protected $float = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_label = 'Int: '
	 * @JetDataModel:min_value = 0
	 * @JetDataModel:max_value = 999
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
	 * @JetDataModel:form_field_type = 'WYSIWYG'
	 *
	 * @var string
	 */
	protected $HTML = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:form_field_label = 'Select: '
	 * @JetDataModel:form_field_type = 'Select'
	 * @JetDataModel:form_field_get_select_options_callback = ['this', 'getSelectOptions']
	 *
	 * @var string
	 */
	protected $select = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ARRAY
	 * @JetDataModel:item_type = 'String'
	 * @JetDataModel:form_field_label = 'Multi Select: '
	 * @JetDataModel:form_field_type = 'MultiSelect'
	 * @JetDataModel:form_field_get_select_options_callback = ['this', 'getSelectOptions']
	 *
	 * @var array
	 */
	protected $multi_select = array (
	);

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ARRAY
	 * @JetDataModel:item_type = 'String'
	 * @JetDataModel:form_field_label = 'Radio Button: '
	 * @JetDataModel:form_field_type = 'RadioButton'
	 * @JetDataModel:form_field_get_select_options_callback = ['this', 'getSelectOptions']
	 *
	 * @var array
	 */
	protected $radio_button = array ();

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Password: '
	 * @JetDataModel:form_field_type = 'Password'
	 * @JetDataModel:form_field_options = array (  'disable_check' => false,  'minimal_password_strength' => 0,)
	 *
	 * @var string
	 */
	protected $password = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Password (no check field): '
	 * @JetDataModel:form_field_type = 'Password'
	 * @JetDataModel:form_field_options = array (  'disable_check' => true,)
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
	 * @param DateTime $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @return DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param DateTime $date_time
	 */
	public function setDateTime($date_time) {
		$this->date_time = $date_time;
	}

	/**
	 * @return DateTime
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
		return array(
				'value1' => 'Option 1',
				'value2' => 'Option 2',
				'value3' => 'Option 3',
				'value4' => 'Option 4',
				'value5' => 'Option 5',
		);
	}


}