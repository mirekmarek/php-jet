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
class DataModel_Definition_Property_DateTime extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_DATE_TIME;


	/**
	 * @param mixed &$value
	 */
	public function checkValueType( mixed &$value ): void
	{
		if( $value === '' ) {
			$value = null;
		}

		if( $value === null ) {
			return;
		}

		if( !is_object( $value ) ) {
			$value = new Data_DateTime( $value );
		} else {
			if( !$value instanceof Data_DateTime ) {
				$value = new Data_DateTime();
			}
		}
	}


	/**
	 *
	 * @param mixed               &$property
	 *
	 * @return mixed
	 */
	public function getJsonSerializeValue( mixed &$property ): mixed
	{
		if( !$property ) {
			return $property;
		}

		return (string)$property;
	}
	
}