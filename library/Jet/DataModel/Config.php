<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Config
 */
namespace Jet;

/**
 * Class DataModel_Config
 *
 * @JetConfig:data_path = 'data_model'
 */
class DataModel_Config extends Application_Config {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'MySQL'
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Config', 'getBackendTypesList']
     * @JetConfig:form_field_label = 'Default backend type: '
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please select backend type', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select backend type']
	 *
	 * @var string
	 */
	protected $backend_type;

	/**
	 * @return string
	 */
	public function getBackendType() {
		return $this->backend_type;
	}


	/**
	 * @return array
	 */
	public static function getBackendTypesList() {
		return static::getAvailableHandlersList( JET_LIBRARY_PATH.'Jet/DataModel/Backend/' );
	}


}