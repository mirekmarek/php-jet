<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\Administrators\Users;

use Jet\DataListing_Filter_OptionSelect;
use Jet\Form_Field_Select;
use Jet\Tr;

use JetApplication\Auth_Administrator_Role as Role;


class Listing_Filter_Role extends DataListing_Filter_OptionSelect {

	public const KEY = 'role';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getParamName() : string
	{
		return 'role';
	}
	
	public function getFormFieldLabel() : string
	{
		return 'Role:';
	}
	
	protected function setFieldSelectOptions( Form_Field_Select $field ) : void
	{
		$options = [0 => Tr::_( '- all -' )];

		foreach( Role::getList() as $role ) {
			$options[$role->getId()] = $role->getName();
		}
		$field->setSelectOptions( $options );
	}

	public function generateWhere(): void
	{
		if( $this->selected_value ) {
			$this->listing->addFilterWhere( [
				'users_roles.role_id' => $this->selected_value,
			] );
		}
	}
	
}