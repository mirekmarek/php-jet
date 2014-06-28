<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Map_Cache_Backend_PHPFile_Config extends Mvc_Router_Map_Cache_Backend_Config_Abstract {

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = '%JET_DATA_PATH%/router_map.php'
	 * @JetConfig:form_field_label = 'File path: '
	 *
	 * @var string
	 */
	protected $path = '%JET_DATA_PATH%/router_map.php';


	/**
	 * @return string
	 */
	public function getPath() {
		return Data_Text::replaceSystemConstants( $this->path );
	}


}