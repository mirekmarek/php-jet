<?php

/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet\DataListing\Filter;


use Jet\DataListing_Filter;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Select;
use Jet\Http_Request;

/**
 *
 */
abstract class DataListing_Filter_OptionSelect extends DataListing_Filter {
	
	
	protected string $selected_value = '';
	
	abstract public function getParamName() : string;
	
	abstract protected function setFieldSelectOptions( Form_Field_Select $field ) : void;
	
	public function catchParams(): void
	{
		$this->selected_value = Http_Request::GET()->getString( $this->getParamName() );
		$this->listing->setParam( $this->getParamName(), $this->selected_value );
	}
	
	public function generateFormFields( Form $form ): void
	{
		$field = new Form_Field_Select( $this->getParamName(), 'Event class:' );
		$field->setDefaultValue( $this->selected_value );
		
		$field->setErrorMessages( [
			Form_Field::ERROR_CODE_INVALID_VALUE => ' '
		] );
		
		$this->setFieldSelectOptions( $field );
		
		
		$form->addField( $field );
	}
	
	public function catchForm( Form $form ): void
	{
		$this->selected_value = $form->field( $this->getParamName() )->getValue();
		$this->listing->setParam( $this->getParamName(), $this->selected_value );
	}
	
}