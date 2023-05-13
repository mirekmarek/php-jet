<?php

/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


/**
 *
 */
abstract class DataListing_Filter_DateTimeInterval extends DataListing_Filter {
	
	protected ?Data_DateTime $date_time_from = null;
	protected ?Data_DateTime $date_time_till = null;
	
	protected function getFromParamName() : string
	{
		return 'date_time_from';
	}
	
	protected function getFromFormFieldLabel() : string
	{
		return 'From:';
	}
	
	protected function getTillParamName() : string
	{
		return 'date_time_till';
	}
	
	protected function getTillFieldLabel() : string
	{
		return 'Till:';
	}
	
	protected function getDateTime( ?string $date_time ) : ?Data_DateTime {
		if(!$date_time) {
			return null;
		}
		
		$date_time = new Data_DateTime($date_time);
		if(!$date_time->getTimestamp()) {
			return null;
		}
		
		return $date_time;
	}
	
	public function catchParams(): void
	{
		$this->date_time_from = $this->getDateTime(Http_Request::GET()->getString( $this->getFromParamName() ));
		$this->listing->setParam( $this->getFromParamName(), ($this->date_time_from?->toString())?:'' );
		
		$this->date_time_till = $this->getDateTime(Http_Request::GET()->getString( $this->getTillParamName() ));
		$this->listing->setParam( $this->getTillParamName(), ($this->date_time_till?->toString())?:'' );
		
	}
	
	public function catchForm( Form $form ): void
	{
		$this->date_time_from = $this->getDateTime($form->field( $this->getFromParamName() )->getValue());
		$this->date_time_till = $this->getDateTime($form->field( $this->getTillParamName() )->getValue());
		
		
		$this->listing->setParam( $this->getFromParamName(), ($this->date_time_from?->toString())?:'' );
		$this->listing->setParam( $this->getTillParamName(), ($this->date_time_till?->toString())?:'' );
	}
	
	public function generateFormFields( Form $form ): void
	{
		$date_time_form = new Form_Field_DateTime( $this->getFromParamName(), $this->getFromFormFieldLabel() );
		$date_time_form->setDefaultValue( $this->date_time_from );
		$date_time_form->setErrorMessages([
			Form_Field::ERROR_CODE_INVALID_FORMAT => ' '
		]);
		
		$form->addField( $date_time_form );
		
		$date_time_till = new Form_Field_DateTime( $this->getTillParamName(), $this->getTillFieldLabel() );
		$date_time_till->setDefaultValue( $this->date_time_till );
		$date_time_till->setErrorMessages([
			Form_Field::ERROR_CODE_INVALID_FORMAT => ' '
		]);
		
		$form->addField( $date_time_till );
		
	}
	
	
}