<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

class Config_Definition_Property_Bool extends Config_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_BOOL;

	/**
	 * @var bool
	 */
	protected $default_value = false;

	/**
	 * @var string
	 */
	protected $form_field_type = "Checkbox";

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( &$value ) {
		$value = (bool)$value;
	}

	/**
	 * Property required test
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 * @return bool
	 */
	public function _validateData_test_required( &$value ) {
		return true;
	}


}