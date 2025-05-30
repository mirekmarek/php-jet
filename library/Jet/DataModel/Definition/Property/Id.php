<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	 * @var bool
	 */
	protected bool $is_id = false;

	/**
	 *
	 * @param mixed $value
	 */
	public function checkValueType( mixed &$value ): void
	{
	}
	
	/**
	 *
	 * @param mixed &$value
	 * @return string|int|float|bool|null
	 */
	public function getCheckSumData( mixed &$value ): string|int|float|null|bool
	{
		return $value;
	}
	
}