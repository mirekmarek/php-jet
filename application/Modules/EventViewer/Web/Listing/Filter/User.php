<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Web;

use Jet\DataListing_Filter;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Http_Request;

/**
 *
 */
class Listing_Filter_User extends DataListing_Filter {

	public const KEY = 'user';

	protected string $user_id = '';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function catchParams(): void
	{
		$this->user_id = Http_Request::GET()->getString( 'user_id' );
		$this->listing->setParam( 'user_id', $this->user_id );
	}

	public function catchForm( Form $form ): void
	{
		$value = $form->field( 'user_id' )->getValue();

		$this->user_id = $value;
		$this->listing->setParam( 'user_id', $value );
	}

	public function generateFormFields( Form $form ): void
	{
		$field = new Form_Field_Input( 'user_id', 'User ID:' );
		$field->setDefaultValue( $this->user_id );

		$form->addField( $field );
	}

	public function generateWhere(): void
	{
		if( $this->user_id ) {
			$this->listing->addFilterWhere( [
				'user_id' => $this->user_id,
			] );
		}
	}

}