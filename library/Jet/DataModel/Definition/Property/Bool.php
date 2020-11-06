<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class DataModel_Definition_Property_Bool extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected $type = DataModel::TYPE_BOOL;

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