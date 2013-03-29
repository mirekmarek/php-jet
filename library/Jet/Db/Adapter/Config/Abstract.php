<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 */
namespace Jet;

abstract class Db_Adapter_Config_Abstract extends Config_Section {
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
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Db_Adapter_Config_Abstract";

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		"adapter" => array(
			"type" => self::TYPE_STRING,
			"is_required" => true,
			"form_field_type" => false
		)
	);

	/**
	 *
	 * @var string
	 */
	protected $adapter = "";


	/**
	 * @return string
	 */
	public function getAdapter() {
		return $this->adapter;
	}
}