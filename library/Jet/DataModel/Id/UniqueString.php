<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Id_UniqueString extends DataModel_Id
{
	/**
	 * @var string
	 */
	protected $id_property_name = 'id';


	/**
	 *
	 *
	 * @throws DataModel_Exception
	 */
	public function generate()
	{

		if( !array_key_exists( $this->id_property_name, $this->values ) ) {
			throw new DataModel_Exception(
				'Class \''.$this->data_model_class_name.'\': Property \''.$this->id_property_name.'\' does not exist. Please configure ID class by @JetDataModel:id_options, or define that property, or create your own ID class.',
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
	public function afterSave( $backend_save_result )
	{
	}

}