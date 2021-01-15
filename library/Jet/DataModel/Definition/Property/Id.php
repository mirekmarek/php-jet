<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
class DataModel_Definition_Property_Id extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_ID;

	/**
	 * @var string
	 */
	protected $default_value = '';

	/**
	 * @var bool
	 */
	protected bool $is_id = false;

	/**
	 * @var string|bool
	 */
	protected string|bool $form_field_type = Form::TYPE_HIDDEN;

	/**
	 *
	 * @param mixed $value
	 */
	public function checkValueType( mixed &$value ): void
	{
	}
}