<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Admin;

use Jet\Data_Listing_Filter;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Http_Request;

/**
 *
 */
class Listing_Filter_Event extends Data_Listing_Filter {


	/**
	 * @var string
	 */
	protected string $event = '';


	/**
	 *
	 */
	public function catchGetParams(): void
	{
		$this->event = Http_Request::GET()->getString( 'event' );
		$this->listing->setGetParam( 'event', $this->event );
	}

	/**
	 * @param Form $form
	 */
	public function catchForm( Form $form ): void
	{
		$this->event = $form->field( 'event' )->getValue();
		$this->listing->setGetParam( 'event', $this->event );
	}

	/**
	 * @param Form $form
	 */
	public function generateFormFields( Form $form ): void
	{
		$field = new Form_Field_Input( 'event', 'Event:' );
		$field->setDefaultValue( $this->event );

		$form->addField( $field );
	}

	/**
	 *
	 */
	public function generateWhere(): void
	{
		if( $this->event ) {
			$this->listing->addWhere( [
				'event' => $this->event,
			] );
		}
	}

}