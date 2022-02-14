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
class Listing_filter_User extends Data_Listing_Filter {


	/**
	 * @var string
	 */
	protected string $user_id = '';


	/**
	 *
	 */
	public function catchGetParams(): void
	{
		$this->user_id = Http_Request::GET()->getString( 'user_id' );
		$this->listing->setGetParam( 'user_id', $this->user_id );
	}

	/**
	 * @param Form $form
	 */
	public function catchForm( Form $form ): void
	{
		$value = $form->field( 'user_id' )->getValue();

		$this->user_id = $value;
		$this->listing->setGetParam( 'user_id', $value );
	}

	/**
	 * @param Form $form
	 */
	public function generateFormFields( Form $form ): void
	{
		$field = new Form_Field_Input( 'user_id', 'User ID:' );
		$field->setDefaultValue( $this->user_id );

		$form->addField( $field );
	}

	/**
	 *
	 */
	public function generateWhere(): void
	{
		if( $this->user_id ) {
			$this->listing->addWhere( [
				'user_id' => $this->user_id,
			] );
		}
	}

}