<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait DataModel_Trait_Exports
{


	/**
	 * @return string
	 */
	public function toJSON()
	{
		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		/**
		 * @var DataModel                  $this
		 * @var DataModel_Definition_Model $definition
		 */
		$definition = static::getDataModelDefinition();
		$properties = $definition->getProperties();

		$result = [];
		foreach( $properties as $property_name => $property ) {
			/**
			 * @var DataModel_Definition_Property $property
			 */
			if( $property->doNotExport() ) {
				continue;
			}

			$result[$property_name] = $property->getJsonSerializeValue( $this, $this->{$property_name} );

		}

		return $result;
	}

}