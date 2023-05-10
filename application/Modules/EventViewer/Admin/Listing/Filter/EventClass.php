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
use Jet\Form_Field;
use Jet\Form_Field_Select;
use Jet\Http_Request;
use Jet\Logger;
use Jet\Tr;

/**
 *
 */
class Listing_Filter_EventClass extends DataListing_Filter {

	public const KEY = 'event_class';

	protected string $event_class = '';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function catchParams(): void
	{
		$this->event_class = Http_Request::GET()->getString( 'event_class' );
		$this->listing->setParam( 'event_class', $this->event_class );
	}
	
	public function generateFormFields( Form $form ): void
	{
		$field = new Form_Field_Select( 'event_class', 'Event class:' );
		$field->setDefaultValue( $this->event_class );
		
		$field->setErrorMessages( [
			Form_Field::ERROR_CODE_INVALID_VALUE => ' '
		] );
		$options = [
			''                          => Tr::_( '- all -' ),
			Logger::EVENT_CLASS_SUCCESS => Tr::_( 'success' ),
			Logger::EVENT_CLASS_INFO    => Tr::_( 'info' ),
			Logger::EVENT_CLASS_WARNING => Tr::_( 'warning' ),
			Logger::EVENT_CLASS_DANGER  => Tr::_( 'danger' ),
			Logger::EVENT_CLASS_FAULT   => Tr::_( 'fault' ),
		];

		$field->setSelectOptions( $options );
		$field->getSelectOptions()[Logger::EVENT_CLASS_SUCCESS]->setSelectOptionCssClass('text-success');
		$field->getSelectOptions()[Logger::EVENT_CLASS_INFO]->setSelectOptionCssClass('text-info');
		$field->getSelectOptions()[Logger::EVENT_CLASS_WARNING]->setSelectOptionCssClass('text-warning');
		$field->getSelectOptions()[Logger::EVENT_CLASS_DANGER]->setSelectOptionCssClass('text-danger');
		$field->getSelectOptions()[Logger::EVENT_CLASS_FAULT]->setSelectOptionCssClass('text-danger');


		$form->addField( $field );
	}
	
	public function catchForm( Form $form ): void
	{
		$this->event_class = $form->field( 'event_class' )->getValue();
		$this->listing->setParam( 'event_class', $this->event_class );
	}
	
	public function generateWhere(): void
	{
		if( $this->event_class ) {
			$this->listing->addFilterWhere( [
				'event_class' => $this->event_class,
			] );
		}
	}

}