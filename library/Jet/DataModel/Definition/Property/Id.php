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
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Property_Id extends DataModel_Definition_Property_Abstract {
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
	protected $is_id = false;

	/**
	 * @var string
	 */
	protected $form_field_type = Form::TYPE_HIDDEN;

	/**
	 * Do nothing
	 *
	 * @param mixed $value
	 */
	public function checkValueType( &$value ) {
	}
}