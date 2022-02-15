<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Iterator;

/**

 */
trait Form_Field_Part_Select_Trait
{
	
	/**
	 * @var ?callable
	 */
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_CALLABLE,
		label: 'Select options creator',
		getter: 'getSelectOptionsCreator',
		setter: 'setSelectOptionsCreator',
	)]
	protected $select_options_creator = null;
	
	/**
	 * @var ?array
	 */
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_ASSOC_ARRAY,
		label: 'Select options',
		getter: 'getSelectOptions',
		setter: 'setSelectOptions',
	)]
	protected ?array $select_options = null;
	
	/**
	 * @return Form_Field_Select_Option_Interface[]
	 */
	public function getSelectOptions(): array
	{
		if($this->select_options===null) {
			$creator = $this->getSelectOptionsCreator();
			if($creator) {
				$this->setSelectOptions( $creator() );
			} else {
				$this->select_options = [];
			}
		}
		
		return $this->select_options;
	}
	
	/**
	 * @param array|Iterator $options
	 */
	public function setSelectOptions( array|Iterator $options ): void
	{
		$_o = $options;
		$options = [];
		
		foreach( $_o as $k => $v ) {
			if(!is_object($v)) {
				$v = new Form_Field_Select_Option($v);
			} else {
				if(!($v instanceof Form_Field_Select_Option_Interface)) {
					$v = new Form_Field_Select_Option((string)$v);
				}
			}
			
			$options[$k] = $v;
		}
		
		$this->select_options = $options;
	}
	
	/**
	 * @return callable|null
	 */
	public function getSelectOptionsCreator(): ?callable
	{
		return $this->select_options_creator;
	}
	
	/**
	 * @param callable|null $select_options_creator
	 */
	public function setSelectOptionsCreator( ?callable $select_options_creator ): void
	{
		$this->select_options_creator = $select_options_creator;
	}
	
	
	
	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		$codes = [];
		
		$codes[] = Form_Field::ERROR_CODE_INVALID_VALUE;
		
		if( $this->is_required ) {
			$codes[] = Form_Field::ERROR_CODE_EMPTY;
		}
		
		
		return $codes;
	}
}