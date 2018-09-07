<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 * @JetConfig:name = 'db'
 */
abstract class Db_Backend_Config extends Config_Section
{
	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:description = 'PDO driver'
	 * @JetConfig:default_value = 'mysql'
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['this', 'getDrivers']
	 * @JetConfig:form_field_label = 'Driver'
	 * @JetConfig:form_field_error_messages = [Form_Field::ERROR_CODE_EMPTY=>'Please select driver', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select driver']
	 *
	 * @var string
	 */
	protected $driver;


	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = 'default'
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Connection name'
	 * @JetConfig:form_field_error_messages = [Form_Field::ERROR_CODE_EMPTY=>'Please enter connection name']
	 *
	 * @var string
	 */
	protected $name;


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
	}

	/**
	 * @return array
	 */
	public static function getDrivers()
	{
		return [];
	}

	/**
	 * @param string $driver
	 */
	public function setDriver( $driver )
	{
		$this->driver = $driver;
	}


	/**
	 * @return string
	 */
	public function getDriver()
	{
		return $this->driver;
	}


}