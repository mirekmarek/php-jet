<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Web;

use Jet\Data_DateTime;
use Jet\DataListing_Filter;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_DateTime;
use Jet\Http_Request;

/**
 *
 */
class Listing_Filter_DateTime extends DataListing_Filter {

	public const KEY = 'date_time';
	
	protected ?Data_DateTime $date_time_from = null;
	protected ?Data_DateTime $date_time_till = null;
	
	public function getKey(): string
	{
		return static::KEY;
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
		$this->date_time_from = $this->getDateTime(Http_Request::GET()->getString( 'date_time_from' ));
		$this->listing->setParam( 'date_time_from', ($this->date_time_from?->toString())?:'' );

		$this->date_time_till = $this->getDateTime(Http_Request::GET()->getString( 'date_time_till' ));
		$this->listing->setParam( 'date_time_till', ($this->date_time_till?->toString())?:'' );
		
	}

	public function catchForm( Form $form ): void
	{
		$this->date_time_from = $this->getDateTime($form->field( 'date_time_from' )->getValue());
		$this->date_time_till = $this->getDateTime($form->field( 'date_time_till' )->getValue());


		$this->listing->setParam( 'date_time_from', ($this->date_time_from?->toString())?:'' );
		$this->listing->setParam( 'date_time_till', ($this->date_time_till?->toString())?:'' );
	}

	public function generateFormFields( Form $form ): void
	{
		$date_time_form = new Form_Field_DateTime( 'date_time_from', 'From:' );
		$date_time_form->setDefaultValue( $this->date_time_from );
		$date_time_form->setErrorMessages([
			Form_Field::ERROR_CODE_INVALID_FORMAT => ' '
		]);

		$form->addField( $date_time_form );

		$date_time_till = new Form_Field_DateTime( 'date_time_till', 'Till:' );
		$date_time_till->setDefaultValue( $this->date_time_till );
		$date_time_till->setErrorMessages([
			Form_Field::ERROR_CODE_INVALID_FORMAT => ' '
		]);

		$form->addField( $date_time_till );

	}

	public function generateWhere(): void
	{
		if( $this->date_time_from ) {
			$this->listing->addFilterWhere( [
				'date_time >=' => $this->date_time_from,
			] );
		}

		if( $this->date_time_till ) {
			$this->listing->addFilterWhere( [
				'date_time <=' => $this->date_time_till,
			] );
		}
	}

}