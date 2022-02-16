<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\Visitors\Users;

use Jet\Data_Listing_Filter;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Select;
use Jet\Http_Request;
use Jet\Tr;

use JetApplication\Auth_Visitor_Role as Role;


class Listing_Filter_Role extends Data_Listing_Filter {

	/**
	 * @var string
	 */
	protected string $role = '';


	/**
	 *
	 */
	public function catchGetParams(): void
	{
		$this->role = Http_Request::GET()->getString( 'role' );
		$this->listing->setGetParam( 'role', $this->role );
	}

	/**
	 * @param Form $form
	 */
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

	/**
	 * @param Form $form
	 */
	public function catchForm( Form $form ): void
	{
		$this->role = $form->field( 'role' )->getValue();
		$this->listing->setGetParam( 'role', $this->role );
	}

	/**
	 *
	 */
	public function generateWhere(): void
	{
		if( $this->role ) {
			$this->listing->addWhere( [
				'users_roles.role_id' => $this->role,
			] );
		}
	}
}