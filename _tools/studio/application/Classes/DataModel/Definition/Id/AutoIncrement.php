<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\DataModel;
use Jet\Tr;
use Jet\Form_Field;

/**
 *
 */
class DataModel_Definition_Id_AutoIncrement extends DataModel_Definition_Id_Abstract
{

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_IdDefinition( ClassCreator_Class $class ): void
	{
		parent::createClass_IdDefinition( $class );

		$id_property_name = $this->getSelectedIdPropertyName( DataModel::TYPE_ID_AUTOINCREMENT );

		if( !$id_property_name ) {
			$class->addError( Tr::_( 'There is not property which is DataModel::TYPE_ID_AUTOINCREMENT type and is marked as ID', [] ) );
		} else {

			$id_controller_options = [
				'id_property_name' => $id_property_name
			];

			$class->setAttribute( 'DataModel_Definition', 'id_controller_options', $id_controller_options );

		}
	}


	/**
	 * @return array
	 */
	public function getOptionsList(): array
	{
		return [
			'id_property_name'
		];
	}

	/**
	 *
	 * @return Form_Field[]
	 */
	public function getOptionsFormFields(): array
	{
		$id_property_name = $this->getOptionsFormField_idProperty( DataModel::TYPE_ID_AUTOINCREMENT );

		return [
			$id_property_name
		];
	}


}