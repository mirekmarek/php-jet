<?php

/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet\DataListing\Filter;


use Jet\Data_DateTime;
use Jet\DataListing_Filter;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Date;
use Jet\Http_Request;

/**
 *
 */
abstract class DataListing_Filter_DateInterval extends DataListing_Filter {
	
	protected ?Data_DateTime $date_from = null;
	protected ?Data_DateTime $date_till = null;
	
	protected function getFromParamName() : string
	{
		return 'date_from';
	}
	
	protected function getFromFormFieldLabel() : string
	{
		return 'From:';
	}
	
	protected function getTillParamName() : string
	{
		return 'date_till';
	}
	
	protected function getTillFieldLabel() : string
	{
		return 'Till:';
	}
	
	protected function getDateTime( ?string $date ) : ?Data_DateTime {
		if(!$date) {
			return null;
		}
		
		$date = new Data_DateTime($date);
		if(!$date->getTimestamp()) {
			return null;
		}
		
		$date->setOnlyDate(true);
		
		return $date;
	}
	
	public function catchParams(): void
	{
		$this->date_from = $this->getDateTime(Http_Request::GET()->getString( $this->getFromParamName() ));
		$this->listing->setParam( $this->getFromParamName(), ($this->date_from?->toString())?:'' );
		
		$this->date_till = $this->getDateTime(Http_Request::GET()->getString( $this->getTillParamName() ));
		$this->listing->setParam( $this->getTillParamName(), ($this->date_till?->toString())?:'' );
		
	}
	
	public function catchForm( Form $form ): void
	{
		$this->date_from = $this->getDateTime($form->field( $this->getFromParamName() )->getValue());
		$this->date_till = $this->getDateTime($form->field( $this->getTillParamName() )->getValue());
		
		
		$this->listing->setParam( $this->getFromParamName(), ($this->date_from?->toString())?:'' );
		$this->listing->setParam( $this->getTillParamName(), ($this->date_till?->toString())?:'' );
	}
	
	public function generateFormFields( Form $form ): void
	{
		$date_form = new Form_Field_Date( $this->getFromParamName(), $this->getFromFormFieldLabel() );
		$date_form->setDefaultValue( $this->date_from );
		$date_form->setErrorMessages([
			Form_Field::ERROR_CODE_INVALID_FORMAT => ' '
		]);
		
		$form->addField( $date_form );
		
		$date_till = new Form_Field_Date( $this->getTillParamName(), $this->getTillFieldLabel() );
		$date_till->setDefaultValue( $this->date_till );
		$date_till->setErrorMessages([
			Form_Field::ERROR_CODE_INVALID_FORMAT => ' '
		]);
		
		$form->addField( $date_till );
		
	}
	
	
}