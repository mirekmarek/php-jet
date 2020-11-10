<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\Tr;
use Jet\Form_Field;
use Jet\Form_Field_Select;

class DataModels_Model_Id_UniqueString extends DataModels_Model_Id_Abstract {

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClassIdDefinition( ClassCreator_Class $class ) {
		parent::createClassIdDefinition( $class );

		$id_property_name = $this->getSelectedIdPropertyName( DataModel::TYPE_ID );

		if(!$id_property_name) {
			$class->addError( Tr::_('There is not property which is DataModel::TYPE_ID type and is marked as ID', []) );
		} else {

			$id_controller_options = [
				'id_property_name' => var_export( $id_property_name, true )
			];

			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'id_controller_options', $id_controller_options ))
			);

		}

	}

	/**
	 * @return array
	 */
	public function getOptionsList()
	{
		return [
			'id_property_name'
		];
	}

	/**
	 *
	 * @return Form_Field[]
	 */
	public function getOptionsFormFields()
	{
		$id_property_name = $this->getOptionsFormField_idProperty( DataModel::TYPE_ID );

		return [
			$id_property_name
		];
	}

}