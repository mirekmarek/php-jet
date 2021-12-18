<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Admin;

use Jet\Data_DateTime;
use Jet\Form;
use Jet\Form_Field_DateTime;
use Jet\Http_Request;

/**
 *
 */
trait Listing_filter_dateTime {

	protected ?Data_DateTime $date_time_from = null;
	protected ?Data_DateTime $date_time_till = null;


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

	/**
	 *
	 */
	protected function filter_dateTime_catchGetParams(): void
	{
		$this->date_time_from = $this->getDateTime(Http_Request::GET()->getString( 'date_time_from' ));
		$this->setGetParam( 'date_time_from', ($this->date_time_from?->toString())?:'' );

		$this->date_time_till = $this->getDateTime(Http_Request::GET()->getString( 'date_time_till' ));
		$this->setGetParam( 'date_time_till', ($this->date_time_till?->toString())?:'' );
		
	}

	/**
	 * @param Form $form
	 */
	public function filter_dateTime_catchForm( Form $form ): void
	{
		$this->date_time_from = $this->getDateTime($form->field( 'date_time_from' )->getValue());
		$this->date_time_till = $this->getDateTime($form->field( 'date_time_till' )->getValue());


		$this->setGetParam( 'date_time_from', ($this->date_time_from?->toString())?:'' );
		$this->setGetParam( 'date_time_till', ($this->date_time_till?->toString())?:'' );
	}

	/**
	 * @param Form $form
	 */
	protected function filter_dateTime_getForm( Form $form ): void
	{
		$date_time_form = new Form_Field_DateTime( 'date_time_from', 'From:', $this->date_time_from );
		$date_time_form->setErrorMessages([
			Form_Field_DateTime::ERROR_CODE_INVALID_FORMAT => ' '
		]);

		$form->addField( $date_time_form );

		$date_time_till = new Form_Field_DateTime( 'date_time_till', 'Till:', $this->date_time_till );
		$date_time_till->setErrorMessages([
			Form_Field_DateTime::ERROR_CODE_INVALID_FORMAT => ' '
		]);

		$form->addField( $date_time_till );

	}

	/**
	 *
	 */
	protected function filter_dateTime_getWhere(): void
	{
		if( $this->date_time_from ) {
			$this->filter_addWhere( [
				'date_time >=' => $this->date_time_from,
			] );
		}

		if( $this->date_time_till ) {
			$this->filter_addWhere( [
				'date_time <=' => $this->date_time_till,
			] );
		}
	}

}