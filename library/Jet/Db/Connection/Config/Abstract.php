<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 */
namespace Jet;

abstract class Db_Connection_Config_Abstract extends Config_Section {
	/**
	 * @var null|string
	 */
	protected static $__factory_class_name = null;
	/**
	 * @var null|string
	 */
	protected static $__factory_class_method = null;
	/**
	 * @var null|string
	 */
	protected static $__factory_must_be_instance_of_class_name = 'Jet\\Db_Connection_Config_Abstract';

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		'name' => array(
			'form_field_label' => 'Connection name',
			'type' => self::TYPE_STRING,
			'default_value' => 'default',
			'is_required' => true
		),
		'driver' => array(
			'form_field_label' => 'Driver',
			'type' => self::TYPE_STRING,
			'description' => 'PDO driver',
			'default_value' => 'mysql',
			'is_required' => true,
			'form_field_type' => 'Select',
			'form_field_get_select_options_callback' => array('Jet\\Db_Connection_Config_PDO', 'getPDODrivers'),
		),
		'DSN' => array(
			'form_field_label' => 'DSN',
			'type' => self::TYPE_STRING,
			'default_value' => '',
			'is_required' => true
		),

		'username' => array(
			'form_field_label' => 'Username',
			'type' => self::TYPE_STRING,
			'default_value' => null,
			'is_required' => false
		),

		'password' => array(
			'form_field_label' => 'Password',
			'type' => self::TYPE_STRING,
			'default_value' => null,
			'is_required' => false
		)
	);

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $driver;

	/**
	 * @var string
	 */
	protected $DSN;

	/**
	 *
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @return array
	 */
	public static function getPDODrivers() {
		$drivers = \PDO::getAvailableDrivers();

		return array_combine( $drivers, $drivers );
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDriver() {
		return $this->driver;
	}

	/**
	 * Get authorization user name
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Get authorization password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @return string
	 */
	public function getDsn() {
		return $this->driver.':'.$this->DSN;
	}
}