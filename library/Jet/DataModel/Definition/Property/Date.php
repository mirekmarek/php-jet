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
class DataModel_Definition_Property_Date extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_DATE;

	/**
	 * @param mixed $value
	 */
	public function checkValueType( mixed &$value ): void
	{
		$value = Data_DateTime::catchDate( $value );
	}


	/**
	 *
	 * @param mixed &$property
	 *
	 * @return mixed
	 */
	public function getJsonSerializeValue( mixed &$property ): mixed
	{
		/**
		 * @var Data_DateTime $property_value
		 */
		if( !$property ) {
			return $property;
		}

		return (string)$property;
	}
	
	/**
	 *
	 * @param mixed &$value
	 * @return string|int|float|bool|null
	 */
	public function getCheckSumData( mixed &$value ): string|int|float|null|bool
	{
		return $value?$value->toString():'';
	}
	
	
}