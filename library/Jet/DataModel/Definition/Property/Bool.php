<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Definition_Property_Bool
 * @package Jet
 */
class DataModel_Definition_Property_Bool extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_BOOL;

	/**
	 * @var bool
	 */
	protected $default_value = false;

	/**
	 * @var string
	 */
	protected $form_field_type = Form::TYPE_CHECKBOX;

	/**
	 * @param mixed $value
	 */
	public function checkValueType( &$value )
	{
		$value = (bool)$value;
	}

}