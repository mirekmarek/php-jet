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
class DataModel_PropertyFilter extends BaseObject
{

	/**
	 * @var array
	 */
	protected array $only_properties = [];

	/**
	 *
	 * @param DataModel_Definition_Model $model_definition
	 * @param array $only_properties
	 *
	 * @throws DataModel_Exception
	 */
	public function __construct( DataModel_Definition_Model $model_definition, array $only_properties )
	{

		foreach( $only_properties as $lp ) {
			$property_names = [];

			if( !str_contains( $lp, '.' ) ) {
				$model_name = $model_definition->getModelName();
				$property_name = $lp;
				if( !$model_definition->hasProperty( $property_name ) ) {
					throw new DataModel_Exception( 'Unknown property ' . $lp );
				}

				$property_names[] = $property_name;
			} else {
				[
					$model_name,
					$property_name
				] = explode( '.', $lp );

				if( $model_name != $model_definition->getModelName() ) {
					$relation = $model_definition->getRelation( $model_name );

					if($property_name=='*') {
						$property_names = array_keys($relation->getRelatedDataModelDefinition()->getProperties());
					} else {
						if( !$relation->getRelatedDataModelDefinition()->hasProperty( $property_name ) ) {
							throw new DataModel_Exception( 'Unknown property ' . $lp );
						}

						$property_names[] = $property_name;
					}
				} else {
					if($property_name=='*') {
						$property_names = array_keys($model_definition->getProperties());
					} else {
						if( !$model_definition->hasProperty( $property_name ) ) {
							throw new DataModel_Exception( 'Unknown property ' . $lp );
						}

						$property_names[] = $property_name;
					}
				}
			}



			if( !isset( $this->only_properties[$model_name] ) ) {
				$this->only_properties[$model_name] = [];
			}

			foreach($property_names as $property_name) {
				$this->only_properties[$model_name][] = $property_name;
			}
		}
	}


	/**
	 * @param DataModel_Definition_Property $property
	 *
	 * @return bool
	 */
	public function getPropertyDefinitionAllowed( DataModel_Definition_Property $property ): bool
	{
		if( $property instanceof DataModel_Definition_Property_DataModel ) {
			if( !$this->getModelAllowed( $property->getValueDataModelDefinition()->getModelName() ) ) {
				return false;
			}

		} else {
			if( !$this->getPropertyAllowed( $property->getDataModelDefinition()->getModelName(), $property->getName() ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param string $model_name
	 * @param string $property_name
	 *
	 * @return bool
	 */
	public function getPropertyAllowed( string $model_name, string $property_name ): bool
	{
		if( !array_key_exists( $model_name, $this->only_properties ) ) {
			return false;
		}

		return in_array( $property_name, $this->only_properties[$model_name] );
	}

	/**
	 * @param string $model_name
	 *
	 * @return bool
	 */
	public function getModelAllowed( string $model_name ): bool
	{
		return array_key_exists( $model_name, $this->only_properties );
	}

	/**
	 * @param string $model_name
	 *
	 * @return array
	 */
	public function getPropertyNames( string $model_name ): array
	{
		if( !array_key_exists( $model_name, $this->only_properties ) ) {
			return [];
		}

		return $this->only_properties[$model_name];
	}


	/**
	 * @param DataModel_Definition_Model $model_definition
	 * @param ?DataModel_PropertyFilter $load_filter
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public static function getQuerySelect( DataModel_Definition_Model $model_definition,
	                                       ?DataModel_PropertyFilter $load_filter = null ): array
	{

		if( !$load_filter ) {
			$select = $model_definition->getProperties();
		} else {
			$select = [];

			foreach( $model_definition->getProperties() as $property ) {
				if(
					!$property->getIsId() &&
					!$load_filter->getPropertyAllowed(
						$model_definition->getModelName(),
						$property->getName()
					)
				) {
					continue;
				}

				$select[] = $property;
			}
		}

		return $select;
	}

}