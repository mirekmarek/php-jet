<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetStudio;

use Jet\DataModel_Definition_Property_DataModel as Jet_DataModel_Definition_Property_DataModel;
use Jet\Form_Field;
use Jet\Tr;

/**
 *
 */
class DataModel_Definition_Property_DataModel extends Jet_DataModel_Definition_Property_DataModel implements DataModel_Definition_Property_Interface
{
	use DataModel_Definition_Property_Trait;

	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( array &$fields ): void
	{
		$remove = [
			'type',
			'database_column_name',
			'is_id',
			'is_key',
			'is_unique'
		];

		foreach( $remove as $r ) {
			unset( $fields[$r] );
		}
	}

	/**
	 * @return string
	 */
	public function getDataModelClass(): string
	{
		return $this->data_model_class;
	}

	/**
	 * @param string $class
	 */
	public function setDataModelClass( string $class ): void
	{
		$this->data_model_class = $class;
	}

	/**
	 *
	 */
	public function showEditFormFields(): void
	{

		$related_class = DataModels::getClass( $this->getDataModelClass() );
		$related_model = $related_class->getDefinition();

		if( $related_model ) {
			echo '<label>' . Tr::_( 'Related DataModel:' ) . '&nbsp;&nbsp;</label>';
			echo
				'<a href="?class=' . $related_class->getFullClassName() . '">'
				. $related_model->getModelName() . ' (' . $related_model->getClassName() . ')'
				. '</a>';
		}
	}


	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class ): ClassCreator_Class_Property
	{
		$related_dm = DataModels::getClass( $this->getDataModelClass() )->getDefinition();
		$attributes = [];

		$property_type = '';

		if( !$related_dm ) {
			$class->addError( 'Unable to get related DataModel definition (related model ID: ' . $this->getDataModelClass() . ')' );
		} else {

			$use = ClassCreator_UseClass::createByClassName( $related_dm->getClassName() );

			if( $use->getNamespace() != $class->getNamespace() ) {
				$class->addUse( $use );
			}

			$attributes[] = [
				'DataModel_Definition',
				'data_model_class',
				$use->getClass() . '::class'
			];

			$type = $related_dm->getInternalType();

			$property_type = $use->getClass();

			switch( $type ) {
				case DataModels::MODEL_TYPE_RELATED_1TO1:
					$class->addUse( new ClassCreator_UseClass( 'Jet', 'DataModel_Related_1to1' ) );
					$property_type .= '|DataModel_Related_1to1|null';
					break;
				case DataModels::MODEL_TYPE_RELATED_1TON:

					$class->addUse( new ClassCreator_UseClass( 'Jet', 'DataModel_Related_1toN' ) );

					$iterator_class = $related_dm->getIteratorClassName();

					if( substr( $iterator_class, 0, 4 ) == 'Jet\\' ) {
						$iterator_class = substr( $iterator_class, 4 );

						$class->addUse( new ClassCreator_UseClass( 'Jet', $iterator_class ) );
					}

					$property_type .= '[]|DataModel_Related_1toN|' . $iterator_class;
					break;
				case DataModels::MODEL_TYPE_RELATED_MTON:
					$N_model = $related_dm->getNModel();

					if( $N_model ) {
						$class->addUse( new ClassCreator_UseClass( 'Jet', 'DataModel_Related_MtoN' ) );

						/*
						if($N_model->getNamespaceId()!=Project::getCurrentNamespaceId()) {

							$ns = Project::getCurrentNamespace();

							$class->addUse(
								new ClassCreator_UseClass($ns->getNamespace(), $N_model->getClassName())
							);

						}
						*/

						$property_type .= '|DataModel_Related_MtoN|' . $N_model->getClassName() . '[]';
					} else {
						$class->addError( 'Unable to get N DataModel definition' );
					}

					break;
			}
		}


		$property = $this->createClassProperty_main( $class, $property_type, 'DataModel::TYPE_DATA_MODEL', $attributes );

		return $property;
	}

	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return array
	 */
	public function createClassMethods( ClassCreator_Class $class ): array
	{

		$s_g_method_name = $this->getSetterGetterMethodName();

		$setter = $class->createMethod( 'set' . $s_g_method_name );
		$setter->line( 1, '//TODO: implement ...' );

		$getter = $class->createMethod( 'get' . $s_g_method_name );
		$getter->line( 1, '//TODO: implement ...' );

		return [
			'set' . $s_g_method_name,
			'get' . $s_g_method_name
		];
	}

}