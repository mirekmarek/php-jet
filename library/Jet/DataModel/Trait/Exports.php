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
trait DataModel_Trait_Exports
{


	/**
	 * @return string
	 */
	public function toJSON(): string
	{
		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		/**
		 * @var DataModel $this
		 * @var DataModel_Definition_Model $definition
		 */
		$definition = static::getDataModelDefinition();
		$properties = $definition->getProperties();

		/**
		 * @var DataModel_PropertyFilter $load_filter
		 */
		$load_filter = $this->getLoadFilter();

		$result = [];
		foreach( $properties as $property_name => $property ) {
			if( $property->doNotExport() ) {
				continue;
			}

			if(
				$load_filter &&
				!$load_filter->getPropertyDefinitionAllowed( $property )
			) {
				continue;
			}


			$result[$property_name] = $property->getJsonSerializeValue( $this->{$property_name} );

		}

		return $result;
	}

}