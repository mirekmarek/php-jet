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
class Form_Field_Hidden extends Form_Field
{
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_HIDDEN;
	
	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		return [];
	}

	/**
	 * @return string
	 */
	public function render(): string
	{
		return (string)$this->input();
	}
	
	/**
	 * @return bool
	 */
	public function validate(): bool
	{
		if(!$this->validate_validator()) {
			return false;
		}
		
		$this->setIsValid();
		return true;
	}
}