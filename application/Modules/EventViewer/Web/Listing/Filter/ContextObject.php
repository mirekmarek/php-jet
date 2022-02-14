<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Web;

use Jet\Data_Listing_Filter;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Http_Request;

/**
 *
 */
class Listing_filter_contextObject extends Data_Listing_Filter {


	/**
	 * @var string
	 */
	protected string $context_object_id = '';

	/**
	 *
	 */
	public function catchGetParams(): void
	{
		$this->context_object_id = Http_Request::GET()->getString( 'context_object_id' );
		$this->listing->setGetParam( 'context_object_id', $this->context_object_id );
	}

	/**
	 * @param Form $form
	 */
	public function catchForm( Form $form ): void
	{
		$this->context_object_id = $form->field( 'context_object_id' )->getValue();
		$this->listing->setGetParam( 'context_object_id', $this->context_object_id );
	}

	/**
	 * @param Form $form
	 */
	public function generateFormFields( Form $form ): void
	{
		$field = new Form_Field_Input( 'context_object_id', 'Context object ID:' );
		$field->setDefaultValue( $this->context_object_id );

		$form->addField( $field );
	}

	/**
	 *
	 */
	public function generateWhere(): void
	{
		if( $this->context_object_id ) {
			$this->listing->addWhere( [
				'context_object_id' => $this->context_object_id,
			] );
		}
	}

}