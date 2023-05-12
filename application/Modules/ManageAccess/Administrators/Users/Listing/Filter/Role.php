<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\Administrators\Users;

use Jet\DataListing_Filter;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Select;
use Jet\Http_Request;
use Jet\Tr;

use JetApplication\Auth_Administrator_Role as Role;


class Listing_Filter_Role extends DataListing_Filter {

	public const KEY = 'role';
	
	protected string $role = '';
	
	public function getKey(): string
	{
		return static::KEY;
	}

	public function catchParams(): void
	{
		$this->role = Http_Request::GET()->getString( 'role' );
		$this->listing->setParam( 'role', $this->role );
	}

	public function generateFormFields( Form $form ): void
	{
		$field = new Form_Field_Select( 'role', 'Role:' );
		$field->setDefaultValue( $this->role );
		$field->setErrorMessages( [
			Form_Field::ERROR_CODE_INVALID_VALUE => ' '
		] );
		$options = [0 => Tr::_( '- all -' )];

		foreach( Role::getList() as $role ) {
			$options[$role->getId()] = $role->getName();
		}
		$field->setSelectOptions( $options );


		$form->addField( $field );
	}

	public function catchForm( Form $form ): void
	{
		$this->role = $form->field( 'role' )->getValue();
		$this->listing->setParam( 'role', $this->role );
	}

	public function generateWhere(): void
	{
		if( $this->role ) {
			$this->listing->addFilterWhere( [
				'users_roles.role_id' => $this->role,
			] );
		}
	}
	
}