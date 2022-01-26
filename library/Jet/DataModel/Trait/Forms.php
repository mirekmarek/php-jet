<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait DataModel_Trait_Forms
{
	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getCommonForm( string $form_name = '' ): Form
	{
		/**
		 * @var DataModel $this
		 * @var DataModel_Definition_Model $definition
		 */

		if( !$form_name ) {
			$definition = static::getDataModelDefinition();
			$form_name = $definition->getModelName();
		}

		return $this->getForm( $form_name );
	}

	/**
	 *
	 * @param string $form_name
	 * @param array|DataModel_PropertyFilter|null $property_filter
	 *
	 * @return Form
	 * @throws DataModel_Exception
	 *
	 */
	public function getForm( string $form_name, array|DataModel_PropertyFilter|null $property_filter = null ): Form
	{
		/**
		 * @var DataModel $this
		 * @var DataModel_Definition_Model $definition
		 */


		$definition = static::getDataModelDefinition();

		if(
			$property_filter &&
			!($property_filter instanceof DataModel_PropertyFilter)
		) {
			$property_filter = new DataModel_PropertyFilter( $definition, $property_filter );
		}


		$form_fields = [];

		foreach( $definition->getProperties() as $property_name => $property_definition ) {
			if(
				$property_filter &&
				!$property_filter->getPropertyAllowed(
					$definition->getModelName(),
					$property_name
				)
			) {
				continue;
			}
			$property = &$this->{$property_name};

			if( ($field_creator_method_name = $property_definition->getFormFieldCreatorMethodName()) ) {
				$created_field = $this->{$field_creator_method_name}( $property_definition, $property_filter );
			} else {
				if($property_definition->getFormFieldType()===false) {
					continue;
				}

				if(
					is_object( $property ) &&
					$property instanceof DataModel_Related
				) {
					foreach( $property->getForm( '', $property_filter )->getFields() as $field ) {
						$field->setName( '/' . $property_name . '/' . $field->getName() );
						$form_fields[] = $field;
					}

					continue;
				}

				if( is_array( $property ) ) {
					foreach($property as $k=>$v) {

						if(
							is_object( $v ) &&
							$v instanceof DataModel_Related
						) {
							foreach( $v->getForm( '', $property_filter )->getFields() as $field ) {
								$field->setName( '/' . $property_name . '/' . $k . '/' . $field->getName() );
								$form_fields[] = $field;
							}
						}

					}

					continue;
				}

				$created_field = $property_definition->createFormField( $property );

			}

			if( !$created_field ) {
				continue;
			}

			if(!$created_field->getFieldValueCatcher()) {
				$created_field->setFieldValueCatcher(
					function( $value ) use ( $property_definition, &$property ) {
						$property_definition->catchFormField( $this, $property, $value );
					}
				);
			}


			$form_fields[] = $created_field;


		}


		return new Form( $form_name, $form_fields );

	}

}