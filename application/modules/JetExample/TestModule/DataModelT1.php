<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_TestModule
 * @subpackage JetApplicationModule_TestModule_DataModelT1
 */
namespace JetApplicationModule\JetExample\TestModule;
use Jet;

class DataModelT1 extends Jet\DataModel {

	protected static $__data_model_model_name = 'JetApplicationModule_TestModule_DataModelT1';

	/**
	 * @var array
	 */
	protected static $__data_model_properties_definition = array(
		'ID' => array(
			'type' => self::TYPE_ID,
			'is_ID' => true
		),
		'checkbox' => array(
			'type' => self::TYPE_BOOL,
			'form_field_label' => 'Checkbox: '
		),
		'date' => array(
			'type' => self::TYPE_DATE,
			'form_field_label' => 'Date: '
		),
		'date_time' => array(
			'type' => self::TYPE_DATE_TIME,
			'form_field_label' => 'Date and time: '
		),
		'float' => array(
			'type' => self::TYPE_FLOAT,
			'form_field_label' => 'Float: ',
			'min_value' => 0,
			'max_value' => 999
		),
		'int' => array(
			'type' => self::TYPE_INT,
			'form_field_label' => 'Int: ',
			'min_value' => 0,
			'max_value' => 999
		),
		'text' => array(
			'type' => self::TYPE_STRING,
			'max_len' => 255,
			'form_field_label' => 'Text: ',
		),
		'long_text' => array(
			'type' => self::TYPE_STRING,
			'max_len' => 65536,
			'form_field_label' => 'Long text:'
		),
		'HTML' => array(
			'type' => self::TYPE_STRING,
			'max_len' => 655360,
			'form_field_label' => 'WYSIWYG:',
			'form_field_type' => 'WYSIWYG'
		),
		'select' => array(
			'type' => self::TYPE_STRING,
			'form_field_label' => 'Select: ',
			'form_field_type' => 'Select',
			'form_field_get_select_options_callback' => array('JetApplicationModule\\JetExample\\TestModule\\DataModelT1', 'getSelectOptions')
		),
		'multi_select' => array(
			'type' => self::TYPE_ARRAY,
			'item_type' => self::TYPE_STRING,
			'form_field_label' => 'Multi Select: ',
			'form_field_type' => 'MultiSelect',
			'form_field_get_select_options_callback' => array('JetApplicationModule\\JetExample\\TestModule\\DataModelT1', 'getSelectOptions')
		),
		'radio_button' => array(
			'type' => self::TYPE_ARRAY,
			'item_type' => self::TYPE_STRING,
			'form_field_label' => 'Radio Button: ',
			'form_field_type' => 'RadioButton',
			'form_field_get_select_options_callback' => array('JetApplicationModule\\JetExample\\TestModule\\DataModelT1', 'getSelectOptions')
		),
		'password' => array(
			'type' => self::TYPE_STRING,
			'max_len' => 255,
			'form_field_label' => 'Password: ',
			'form_field_type' => 'Password',
			'form_field_options' => array(
				'disable_check' => false,
				'minimal_password_strength' => 0
			)
		),
		'password_nc' => array(
			'type' => self::TYPE_STRING,
			'max_len' => 255,
			'form_field_label' => 'Password (no check field): ',
			'form_field_type' => 'Password',
			'form_field_options' => array(
				'disable_check' => true
			)
		)

	);

	/**
	 * @var string
	 */
	protected $ID;

	/**
	 * @var string
	 */
	protected $text = '';

	/**
	 * @var string
	 */
	protected $HTML = '';

	/**
	 * @var string
	 */
	protected $long_text = '';

	/**
	 * @var Jet\DateTime
	 */
	protected $date;

	/**
	 * @var Jet\DateTime
	 */
	protected $date_time;

	/**
	 * @var string
	 */
	protected $select = '';

	/**
	 * @var array
	 */
	protected $multi_select = array();

	/**
	 * @var array
	 */
	protected $radio_button = array();

	/**
	 * @var bool
	 */
	protected $checkbox = false;

	/**
	 * @var float
	 */
	protected $float = 0.0;

	/**
	 * @var int
	 */
	protected $int = 0;

	/**
	 * @var string
	 */
	protected $password = '';

	/**
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
	 * @param Jet\DateTime $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @return Jet\DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param Jet\DateTime $date_time
	 */
	public function setDateTime($date_time) {
		$this->date_time = $date_time;
	}

	/**
	 * @return Jet\DateTime
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