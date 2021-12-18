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
trait Listing_filter_contextObject {


	/**
	 * @var string
	 */
	protected string $context_object_id = '';


	/**
	 *
	 */
	protected function filter_contextObject_catchGetParams(): void
	{
		$this->context_object_id = Http_Request::GET()->getString( 'context_object_id' );
		$this->setGetParam( 'context_object_id', $this->context_object_id );
	}

	/**
	 * @param Form $form
	 */
	public function filter_contextObject_catchForm( Form $form ): void
	{
		$value = $form->field( 'context_object_id' )->getValue();

		$this->context_object_id = $value;
		$this->setGetParam( 'context_object_id', $value );
	}

	/**
	 * @param Form $form
	 */
	protected function filter_contextObject_getForm( Form $form ): void
	{
		$field = new Form_Field_Input( 'context_object_id', 'Context object ID:', $this->context_object_id );

		$form->addField( $field );
	}

	/**
	 *
	 */
	protected function filter_contextObject_getWhere(): void
	{
		if( $this->context_object_id ) {
			$this->filter_addWhere( [
				'context_object_id' => $this->context_object_id,
			] );
		}
	}

}