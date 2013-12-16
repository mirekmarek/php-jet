<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Module
 */
namespace Jet;

abstract class Config_Module extends Config {
	/**
	 * @var string
	 */
	protected $__config_module_name = "";

	/**
	 * @param $module_name
	 */
	public function __construct( $module_name ) {
		$this->__config_module_name = $module_name;
		parent::__construct( JET_APPLICATION_MODULES_PATH.$module_name."/config/config.php");
	}
}