<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Web\Auth\Admin\Users;

use Jet\DataListing_Filter_OptionSelect;
use Jet\Form_Field_Select;
use Jet\Tr;


class Listing_Filter_IsBlocked extends DataListing_Filter_OptionSelect {
	
	public const KEY = 'is_blocked';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getParamName() : string
	{
		return 'is_blocked';
	}
	
	public function getFormFieldLabel() : string
	{
		return 'Is blocked:';
	}
	
	protected function setFieldSelectOptions( Form_Field_Select $field ): void
	{
		$options = [
			'' => Tr::_( '- all -' ),
			'yes' => Tr::_( 'Yes' ),
			'no' => Tr::_( 'No' ),
		];
		
		$field->setSelectOptions( $options );
	}
	
	public function generateWhere(): void
	{
		if( $this->selected_value ) {
			$this->listing->addFilterWhere( [
				'user_is_blocked' => $this->selected_value=='yes',
			] );
		}
	}
	
}