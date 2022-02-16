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
class DataModel_IDController_UniqueString extends DataModel_IDController
{
	/**
	 * @var string
	 */
	protected string $id_property_name = 'id';


	/**
	 *
	 *
	 * @throws DataModel_Exception
	 */
	public function beforeSave(): void
	{

		if( !array_key_exists( $this->id_property_name, $this->values ) ) {
			throw new DataModel_Exception(
				'Class \'' . $this->data_model_class_name . '\': Property \'' . $this->id_property_name . '\' does not exist. Please configure ID class by #[DataModel_Definition(id_controller_options:[ \'id_property_name\' => \'some_property_name\' ])], or define that property, or create your own ID class.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if( !$this->values[$this->id_property_name] ) {
			/** @noinspection SpellCheckingInspection */
			$id = uniqid( date( 'Ymdhis' ), false );

			$this->values[$this->id_property_name] = $id;
		}
	}

	/**
	 * @param mixed $backend_save_result
	 *
	 */
	public function afterSave( mixed $backend_save_result ): void
	{
	}

}