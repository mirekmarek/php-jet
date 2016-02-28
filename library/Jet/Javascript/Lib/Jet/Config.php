<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Lib
 */
namespace Jet;

//TODO: asi zcela vyradit

/**
 * Class Javascript_Lib_Jet_Config
 *
 * @JetConfig:data_path = '/js_libs/Jet'
 * @JetConfig:section_is_obligatory = false
 */
class Javascript_Lib_Jet_Config extends Application_Config {
	/**
	 * @JetConfig:type = Config::TYPE_BOOL
	 * @JetConfig:default_value = true
	 * @JetConfig:form_field_label = 'Create package'
	 * @JetConfig:is_required = false
	 *
	 */
	protected $package_enabled = true;

	/**
	 * @return bool
	 */
	public function getPackageEnabled() {
		return $this->package_enabled;
	}

}