<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Logger;

use Jet\DataListing_Filter_OptionSelect;
use Jet\Form_Field_Select;
use Jet\MVC;

/**
 *
 */
class Listing_Filter_Base extends DataListing_Filter_OptionSelect {
	
	public const KEY = 'base';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getParamName() : string
	{
		return 'base';
	}
	
	public function getFormFieldLabel() : string
	{
		return 'Base:';
	}
	
	
	protected function setFieldSelectOptions( Form_Field_Select $field ) : void
	{
		$bases = [];
		foreach( MVC::getBases() as $base ) {
			$bases[$base->getId()] = $base->getName();
		}
		
		$field->setSelectOptions( $bases );
	}
	
	public function generateWhere(): void
	{
		if( $this->selected_value ) {
			$this->listing->addFilterWhere( [
				'base_id' => $this->selected_value,
			] );
		}
	}
	
}