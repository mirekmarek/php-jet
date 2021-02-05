<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	public function getCommonForm( $form_name = '' ): Form
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
				if(
					is_object( $property ) &&
					method_exists( $property, 'getRelatedFormFields' ) &&
					$property_definition->getFormFieldType() !== false
				) {
					foreach( $property->getRelatedFormFields( $property_definition, $property_filter ) as $field ) {
						$form_fields[] = $field;
					}

					continue;
				}

				$created_field = $property_definition->createFormField( $property );

			}

			if( !$created_field ) {
				continue;
			}

			if(!$created_field->getCatcher()) {
				$created_field->setCatcher(
					function( $value ) use ( $property_definition, &$property ) {
						/** @noinspection PhpParamsInspection */
						$property_definition->catchFormField( $this, $property, $value );
					}
				);
			}


			$form_fields[] = $created_field;


		}


		return new Form( $form_name, $form_fields );

	}

	/**
	 * @param Form $form
	 *
	 * @param array|null $data
	 * @param bool $force_catch
	 *
	 * @return bool;
	 */
	public function catchForm( Form $form, ?array $data = null, bool $force_catch = false ): bool
	{

		if(
			!$form->catchInput( $data, $force_catch ) ||
			!$form->validate()
		) {
			return false;
		}

		return $form->catchData();
	}

}