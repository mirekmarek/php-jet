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
class DataModel_Definition_Property_Bool extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_BOOL;

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( mixed &$value ): void
	{
		$value = (bool)$value;
	}
	
	/**
	 *
	 * @param mixed &$value
	 * @return string|int|float|bool|null
	 */
	public function getCheckSumData( mixed &$value ): string|int|float|null|bool
	{
		return $value?1:0;
	}
	

}