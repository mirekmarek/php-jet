<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Web;

use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Http_Request;

/**
 *
 */
trait Listing_filter_event {


	/**
	 * @var string
	 */
	protected string $event = '';


	/**
	 *
	 */
	protected function filter_event_catchGetParams(): void
	{
		$this->event = Http_Request::GET()->getString( 'event' );
		$this->setGetParam( 'event', $this->event );
	}

	/**
	 * @param Form $form
	 */
	public function filter_event_catchForm( Form $form ): void
	{
		$value = $form->field( 'event' )->getValue();

		$this->event = $value;
		$this->setGetParam( 'event', $value );
	}

	/**
	 * @param Form $form
	 */
	protected function filter_event_getForm( Form $form ): void
	{
		$field = new Form_Field_Input( 'event', 'Event:', $this->event );

		$form->addField( $field );
	}

	/**
	 *
	 */
	protected function filter_event_getWhere(): void
	{
		if( $this->event ) {
			$this->filter_addWhere( [
				'event' => $this->event,
			] );
		}
	}

}