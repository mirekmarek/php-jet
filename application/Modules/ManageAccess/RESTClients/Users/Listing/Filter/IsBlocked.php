<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\RESTClients\Users;

use Jet\DataListing_Filter;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Select;
use Jet\Http_Request;
use Jet\Tr;


class Listing_Filter_IsBlocked extends DataListing_Filter {

	public const KEY = 'is_blocked';
	
	protected string $is_blocked = '';
	
	public function getKey(): string
	{
		return static::KEY;
	}

	public function catchParams(): void
	{
		$this->is_blocked = Http_Request::GET()->getString( 'is_blocked' );
		$this->listing->setParam( 'is_blocked', $this->is_blocked );
	}

	public function generateFormFields( Form $form ): void
	{
		$field = new Form_Field_Select( 'is_blocked', 'Is blocked:' );
		$field->setDefaultValue( $this->is_blocked );
		$field->setErrorMessages( [
			Form_Field::ERROR_CODE_INVALID_VALUE => ' '
		] );
		$options = [
			'' => Tr::_( '- all -' ),
			'yes' => Tr::_( 'Yes' ),
			'no' => Tr::_( 'No' ),
		];

		$field->setSelectOptions( $options );


		$form->addField( $field );
	}

	public function catchForm( Form $form ): void
	{
		$this->is_blocked = $form->field( 'is_blocked' )->getValue();
		$this->listing->setParam( 'is_blocked', $this->is_blocked );
	}

	public function generateWhere(): void
	{
		if( $this->is_blocked ) {
			$this->listing->addFilterWhere( [
				'user_is_blocked' => $this->is_blocked=='yes',
			] );
		}
	}
	
}