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
class DataModel_Definition_Property_Int extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_INT;

	/**
	 * @param array $definition_data
	 *
	 */
	public function setUp( array $definition_data ): void
	{

		if( !$definition_data ) {
			return;
		}

		parent::setUp( $definition_data );
	}

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( mixed &$value ): void
	{
		$value = (int)$value;
	}
	
}