<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected string $type = DataModel::TYPE_BOOL;

	/**
	 * @var bool
	 */
	protected $default_value = false;

	/**
	 * @var string|bool
	 */
	protected string|bool $form_field_type = Form::TYPE_CHECKBOX;

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( mixed &$value ): void
	{
		$value = (bool)$value;
	}

}