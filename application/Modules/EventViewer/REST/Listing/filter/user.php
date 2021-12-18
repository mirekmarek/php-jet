<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\REST;

use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Http_Request;

/**
 *
 */
trait Listing_filter_user {


	/**
	 * @var string
	 */
	protected string $user_id = '';


	/**
	 *
	 */
	protected function filter_user_catchGetParams(): void
	{
		$this->user_id = Http_Request::GET()->getString( 'user_id' );
		$this->setGetParam( 'user_id', $this->user_id );
	}

	/**
	 * @param Form $form
	 */
	public function filter_user_catchForm( Form $form ): void
	{
		$value = $form->field( 'user_id' )->getValue();

		$this->user_id = $value;
		$this->setGetParam( 'user_id', $value );
	}

	/**
	 * @param Form $form
	 */
	protected function filter_user_getForm( Form $form ): void
	{
		$field = new Form_Field_Input( 'user_id', 'User ID:', $this->user_id );

		$form->addField( $field );
	}

	/**
	 *
	 */
	protected function filter_user_getWhere(): void
	{
		if( $this->user_id ) {
			$this->filter_addWhere( [
				'user_id' => $this->user_id,
			] );
		}
	}

}