<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Property_ID extends DataModel_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_ID;

	/**
	 * @var null
	 */
	protected $default_value = null;

	/**
	 * @var bool
	 */
	protected $is_ID = true;

	/**
	 * @var string
	 */
	protected $form_field_type = "Hidden";

	/**
	 * Do nothing
	 *
	 * @param mixed $value
	 */
	public function checkValueType( &$value ) {
	}
}