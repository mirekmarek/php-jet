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
class Form_Field_Select_Option extends BaseObject implements Form_Field_Select_Option_Interface
{
	use Form_Field_Select_Option_Trait;

	/**
	 * @var string
	 */
	protected string $option = '';

	/**
	 *
	 * @param string $option
	 */
	public function __construct( string $option )
	{
		$this->option = $option;
	}
	
	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->option;
	}
}