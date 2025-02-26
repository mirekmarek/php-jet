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
trait DataModel_Trait_InternalState
{

	/**
	 * @var bool
	 */
	private bool $_data_model_saved = false;

	/**
	 *
	 */
	public function setIsNew( bool $set_for_whole_object = false ): void
	{
		$this->_data_model_saved = false;
		
		if($set_for_whole_object) {
			foreach($this::getDataModelDefinition()->getProperties() as $property_name=>$property  ) {
				if($property instanceof DataModel_Definition_Property_DataModel) {
					$v = $this->$property_name;
					if(
						is_object($v) &&
						$v instanceof DataModel
					) {
						$v->setIsNew( $set_for_whole_object );
					}
					
					if( is_array($v) ) {
						foreach($v as $s) {
							if(
								is_object($s) &&
								$s instanceof DataModel
							) {
								$s->setIsNew( $set_for_whole_object );
							}
						}
					}
				}
			}
		}
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsNew(): bool
	{
		return !$this->_data_model_saved;
	}

	/**
	 * @return bool
	 */
	public function getIsSaved(): bool
	{
		return $this->_data_model_saved;
	}

	/**
	 *
	 */
	public function setIsSaved(): void
	{
		$this->_data_model_saved = true;
	}

}