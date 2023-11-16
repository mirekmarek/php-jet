<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\EventViewer\REST;

use Jet\DataListing_Filter;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Http_Request;

/**
 *
 */
class Listing_Filter_ContextObject extends DataListing_Filter {

	public const KEY = 'context_object';

	protected string $context_object_id = '';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function catchParams(): void
	{
		$this->context_object_id = Http_Request::GET()->getString( 'context_object_id' );
		$this->listing->setParam( 'context_object_id', $this->context_object_id );
	}

	public function catchForm( Form $form ): void
	{
		$this->context_object_id = $form->field( 'context_object_id' )->getValue();
		$this->listing->setParam( 'context_object_id', $this->context_object_id );
	}

	public function generateFormFields( Form $form ): void
	{
		$field = new Form_Field_Input( 'context_object_id', 'Context object ID:' );
		$field->setDefaultValue( $this->context_object_id );

		$form->addField( $field );
	}

	public function generateWhere(): void
	{
		if( $this->context_object_id ) {
			$this->listing->addFilterWhere( [
				'context_object_id' => $this->context_object_id,
			] );
		}
	}
}