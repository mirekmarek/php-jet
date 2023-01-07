<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\DataModel;
use Jet\BaseObject_Exception;
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

		if( $related_class ) {
			$related_model = $related_class->getDefinition();
			?>
			<div class="card">
				<div class=" card-body">
					<?=Tr::_( 'Related DataModel:' )?>&nbsp;&nbsp;<a href="?class=<?=$related_class->getFullClassName()?>"><?=$related_model->getClassName()?> (<?=$related_model->getModelName()?>)</a>
				</div>
			</div>
			<br>
			<?php
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

		$related_dm = DataModels::getClass( $this->getDataModelClass() );
		if(!$related_dm) {
			throw new BaseObject_Exception( 'Class ' . $this->getDataModelClass() . ' does not exist' );
		}
		$related_dm = $related_dm->getDefinition();
		$attributes = [];

		$property_type = '';
		$default_value = null;


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


		switch($type) {
			case DataModel::MODEL_TYPE_RELATED_1TO1:
				$property_type = $use->getClass();
			break;
			case DataModel::MODEL_TYPE_RELATED_1TON:
				$property_type = 'array';
				$default_value = [];
			break;
		}


		$property = $this->createClassProperty_main( $class, $property_type, 'DataModel::TYPE_DATA_MODEL', $attributes );
		$property->setDefaultValue( $default_value );

		return $property;
	}

	/**
	 * @return array|null
	 */
	public function getDefaultValue() : ?array
	{
		$related_dm = DataModels::getClass( $this->getDataModelClass() )?->getDefinition();

		if( $related_dm ) {
			switch( $related_dm->getInternalType() ) {
				case DataModel::MODEL_TYPE_RELATED_1TO1:
					return null;

				case DataModel::MODEL_TYPE_RELATED_1TON:
					return [];
			}
		}

		return null;
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