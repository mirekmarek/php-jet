<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
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
class DataModel_Definition_Property_DataModel extends Jet_DataModel_Definition_Property_DataModel implements DataModel_Definition_Property_Interface {
	use DataModel_Definition_Property_Trait;

	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( &$fields )
	{
		$remove = [
			'type',
			'database_column_name',
			'is_id',
			'is_key',
			'is_unique'
		];

		foreach( $remove as $r ) {
			unset($fields[$r]);
		}
	}

	/**
	 * @return string
	 */
	public function getDataModelClass()
	{
		return $this->data_model_class;
	}

	/**
	 *
	 */
	public function showEditFormFields()
	{

		$related_class = DataModels::getClass( $this->getDataModelClass() );
		$related_model = $related_class->getDefinition();

		if( $related_model ) {
			echo '<label>'.Tr::_('Related DataModel:').'&nbsp;&nbsp;</label>';
			echo
				'<a href="?class='.$related_class->getFullClassName().'">'
				.$related_model->getModelName().' ('.$related_model->getClassName().')'
				.'</a>';
		}
	}


	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class )
	{
		$related_dm = DataModels::getClass( $this->getDataModelClass() )->getDefinition();
		$annotations = [];

		$property_type = '';

		if(!$related_dm) {
			$class->addError('Unable to get related DataModel definition (related model ID: '.$this->getDataModelClass().')');
		} else {
			$annotations[] = new ClassCreator_Annotation('JetDataModel', 'data_model_class',  var_export($related_dm->getClassName(), true) );

			$type = $related_dm->getInternalType();

			$property_type = $related_dm->getClassName();

			switch( $type ) {
				case DataModels::MODEL_TYPE_RELATED_1TO1:
					$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_1to1') );
					$property_type .= '|DataModel_Related_1to1|null';
					break;
				case DataModels::MODEL_TYPE_RELATED_1TON:

					$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_1toN') );

					$iterator_class_name = $related_dm->getIteratorClassName();

					if( substr($iterator_class_name, 0, 4)=='Jet\\' ) {
						$iterator_class_name = substr( $iterator_class_name, 4 );

						$class->addUse( new ClassCreator_UseClass('Jet', $iterator_class_name) );
					}

					$property_type .= '[]|DataModel_Related_1toN|'.$iterator_class_name;
					break;
				case DataModels::MODEL_TYPE_RELATED_MTON:
					$N_model = $related_dm->getNModel();

					if($N_model) {
						$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_MtoN') );

						/*
						if($N_model->getNamespaceId()!=Project::getCurrentNamespaceId()) {

							$ns = Project::getCurrentNamespace();

							$class->addUse(
								new ClassCreator_UseClass($ns->getNamespace(), $N_model->getClassName())
							);

						}
						*/

						$property_type .= '|DataModel_Related_MtoN|'.$N_model->getClassName().'[]';
					} else {
						$class->addError('Unable to get N DataModel definition');
					}

					break;
			}
		}




		$property = $this->createClassProperty_main( $class, $property_type,  'DataModel::TYPE_DATA_MODEL', $annotations);

		return $property;
	}

	/**
	 * @param ClassCreator_Class $class
	 *
	 */
	public function createClassMethods( ClassCreator_Class $class )
	{

		$s_g_method_name = $this->getSetterGetterMethodName();

		$setter = $class->createMethod('set'.$s_g_method_name);
		$setter->line( 1, '//TODO: implement ...' );

		$getter = $class->createMethod('get'.$s_g_method_name);
		$getter->line( 1, '//TODO: implement ...');
	}

}