<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Config
 */
namespace Jet;

class DataModel_Config extends Config_Application {
	/**
	 * @var string
	 */
	protected static $__config_data_path = 'data_model';
	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		'backend_type' => array(
			'type' => self::TYPE_STRING,
			'is_required' => true,
			'default_value' => 'MySQL',
			'form_field_label' => 'Default backend type: ',
			'form_field_type' => 'Select',
			'form_field_get_select_options_callback' => array('Jet\\DataModel_Config', 'getBackendTypesList')
		),
		'history_enabled' => array(
			'type' => self::TYPE_BOOL,
			'default_value' => true,
			'form_field_label' => 'Enable data history:',
		),
		'history_backend_type' => array(
			'type' => self::TYPE_STRING,
			'is_required' => true,
			'default_value' => 'MySQL',
			'form_field_label' => 'History backend type: ',
			'form_field_type' => 'Select',
			'form_field_get_select_options_callback' => array('Jet\\DataModel_Config', 'getHistoryBackendTypesList')
		),
		'cache_enabled' => array(
			'type' => self::TYPE_BOOL,
			'default_value' => true,
			'form_field_label' => 'Enable data cache:',
		),
		'cache_backend_type' => array(
			'type' => self::TYPE_STRING,
			'is_required' => true,
			'default_value' => 'MySQL',
			'form_field_label' => 'Cache backend type: ',
			'form_field_type' => 'Select',
			'form_field_get_select_options_callback' => array('Jet\\DataModel_Config', 'getCacheBackendTypesList')

		)
	);

	/**
	 * @var string
	 */
	protected $backend_type;

	/**
	 * @var bool
	 */
	protected $history_enabled;
	/**
	 * @var string
	 */
	protected $history_backend_type;

	/**
	 * @var bool
	 */
	protected $cache_enabled;
	/**
	 * @var string
	 */
	protected $cache_backend_type;

	/**
	 * @return string
	 */
	public function getBackendType() {
		return $this->backend_type;
	}

	/**
	 * @return string
	 */
	public function getCacheBackendType() {
		return $this->cache_backend_type;
	}

	/**
	 * @return boolean
	 */
	public function getCacheEnabled() {
		return $this->cache_enabled;
	}

	/**
	 * @return string
	 */
	public function getHistoryBackendType() {
		return $this->history_backend_type;
	}

	/**
	 * @return boolean
	 */
	public function getHistoryEnabled() {
		return $this->history_enabled;
	}

	/**
	 * @return array
	 */
	public static function getBackendTypesList() {
		return static::getAvailableHandlersList( JET_LIBRARY_PATH.'Jet/DataModel/Backend/' );
	}

	/**
	 * @return array
	 */
	public static function getHistoryBackendTypesList() {
		return static::getAvailableHandlersList( JET_LIBRARY_PATH.'Jet/DataModel/History/Backend/' );
	}


	/**
	 * @return array
	 */
	public static function getCacheBackendTypesList() {
		return static::getAvailableHandlersList( JET_LIBRARY_PATH.'Jet/DataModel/Cache/Backend/' );
	}
}