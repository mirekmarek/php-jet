<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Trait_Exports
 * @package Jet
 */
trait DataModel_Trait_Exports
{


	/**
	 * @return string
	 */
	public function toXML()
	{
		return $this->XMLSerialize();
	}

	/**
	 * @param string $prefix
	 *
	 * @return string
	 */
	public function XMLSerialize( $prefix = '' )
	{
		/**
		 * @var DataModel_Definition_Model_Abstract $definition
		 * @var DataModel                           $this
		 */
		$definition = static::getDataModelDefinition();
		$properties = $definition->getProperties();

		$model_name = $definition->getModelName();

		$result = $prefix.'<'.$model_name.'>'.JET_EOL;

		foreach( $properties as $property_name => $property ) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			if( $property->doNotExport() ) {
				continue;
			}
			$result .= $prefix.JET_TAB.'<!-- '.$property->getTechnicalDescription().' -->'.JET_EOL;

			$val = $property->getXmlExportValue( $this, $this->{$property_name} );


			if( ( $val instanceof DataModel_Related_Interface ) ) {
				$result .= $prefix.JET_TAB.'<'.$property_name.'>'.JET_EOL;
				if( $val ) {
					/**
					 * @var DataModel $val
					 */
					$result .= $val->XMLSerialize( $prefix.JET_TAB );
				}
				$result .= $prefix.JET_TAB.'</'.$property_name.'>'.JET_EOL;

			} else {
				if( is_array( $val ) ) {
					$result .= $prefix.JET_TAB.'<'.$property_name.'>'.JET_EOL;
					foreach( $val as $k => $v ) {
						if( is_numeric( $k ) ) {
							$k = 'item';
						}
						$result .= $prefix.JET_TAB.JET_TAB.'<'.$k.'>'.Data_Text::htmlSpecialChars(
								$v
							).'</'.$k.'>'.JET_EOL;

					}
					$result .= $prefix.JET_TAB.'</'.$property_name.'>'.JET_EOL;
				} else {
					$result .= $prefix.JET_TAB.'<'.$property_name.'>'.Data_Text::htmlSpecialChars(
							$val
						).'</'.$property_name.'>'.JET_EOL;
				}

			}
		}
		$result .= $prefix.'</'.$model_name.'>'.JET_EOL;

		return $result;
	}

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
		 * @var DataModel                           $this
		 * @var DataModel_Definition_Model_Abstract $definition
		 */
		$definition = static::getDataModelDefinition();
		$properties = $definition->getProperties();

		$result = [];
		foreach( $properties as $property_name => $property ) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			if( $property->doNotExport() ) {
				continue;
			}

			$result[$property_name] = $property->getValueForJsonSerialize( $this, $this->{$property_name} );

		}

		return $result;
	}

}