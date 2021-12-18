<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Admin;

use Jet\Form;
use Jet\Form_Field_Select;
use Jet\Http_Request;
use Jet\Logger;
use Jet\Tr;

/**
 *
 */
trait Listing_filter_eventClass {


	/**
	 * @var string
	 */
	protected string $event_class = '';


	/**
	 *
	 */
	protected function filter_eventClass_catchGetParams(): void
	{
		$this->event_class = Http_Request::GET()->getString( 'event_class' );
		$this->setGetParam( 'event_class', $this->event_class );
	}

	/**
	 * @param Form $form
	 */
	public function filter_eventClass_catchForm( Form $form ): void
	{
		$value = $form->field( 'event_class' )->getValue();

		$this->event_class = $value;
		$this->setGetParam( 'event_class', $value );
	}

	/**
	 * @param Form $form
	 */
	protected function filter_eventClass_getForm( Form $form ): void
	{
		$field = new Form_Field_Select( 'event_class', 'Event class:', $this->event_class );
		$field->setErrorMessages( [
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => ' '
		] );
		$options = [
			'' => Tr::_( '- all -' ),
			Logger::EVENT_CLASS_SUCCESS => Tr::_('success'),
			Logger::EVENT_CLASS_INFO => Tr::_('info'),
			Logger::EVENT_CLASS_WARNING => Tr::_('warning'),
			Logger::EVENT_CLASS_DANGER => Tr::_('danger'),
			Logger::EVENT_CLASS_FAULT => Tr::_('fault'),
		];

		$field->setSelectOptions( $options );


		$form->addField( $field );
	}

	/**
	 *
	 */
	protected function filter_eventClass_getWhere(): void
	{
		if( $this->event_class ) {
			$this->filter_addWhere( [
				'event_class' => $this->event_class,
			] );
		}
	}

}