<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Field_Search extends Form_Field_Input implements Form_Field_Part_RegExp_Interface
{
	use Form_Field_Part_RegExp_Trait;
	
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_SEARCH;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => '',
		Form_Field::ERROR_CODE_INVALID_FORMAT => '',
	];
	
	protected ?UI_button $reset_button = null;
	
	protected ?UI_button $search_button = null;
	
	public function resetButton() : UI_button
	{
		if(!$this->reset_button) {
			$this->reset_button = UI::button(' ');
			$this->reset_button->setIcon( 'times' );
			$this->reset_button->setType( UI_button::TYPE_BUTTON );
			$this->reset_button->setOnClick('this.form.'.$this->getTagNameValue().'.value=\'\';this.form.submit();');
		}
		
		return $this->reset_button;
	}
	
	public function searchButton() : UI_button
	{
		if(!$this->search_button) {
			$this->search_button = UI::button(' ');
			$this->search_button->setIcon( 'search' );
			$this->search_button->setType( UI_button::TYPE_SUBMIT );
		}
		
		return $this->search_button;
	}
	
}