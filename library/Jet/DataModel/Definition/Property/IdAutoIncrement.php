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
class DataModel_Definition_Property_IdAutoIncrement extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_ID_AUTOINCREMENT;

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
	
}