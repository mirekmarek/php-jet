<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\EventViewer\Admin;

use Jet\DataListing_Filter;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Http_Request;

/**
 *
 */
class Listing_Filter_Event extends DataListing_Filter {

	public const KEY = 'event';

	protected string $event = '';
	
	public function getKey(): string
	{
		return static::KEY;
	}

	public function catchParams(): void
	{
		$this->event = Http_Request::GET()->getString( 'event' );
		$this->listing->setParam( 'event', $this->event );
	}

	public function catchForm( Form $form ): void
	{
		$this->event = $form->field( 'event' )->getValue();
		$this->listing->setParam( 'event', $this->event );
	}

	public function generateFormFields( Form $form ): void
	{
		$field = new Form_Field_Input( 'event', 'Event:' );
		$field->setDefaultValue( $this->event );

		$form->addField( $field );
	}

	public function generateWhere(): void
	{
		if( $this->event ) {
			$this->listing->addFilterWhere( [
				'event' => $this->event,
			] );
		}
	}

}