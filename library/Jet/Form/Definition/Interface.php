<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

interface Form_Definition_Interface
{
	
	/**
	 * @return Form_Definition_Field[]
	 */
	public function getFormFieldsDefinition() : array;
	
	/**
	 *
	 * @param string $form_name
	 *
	 * @return Form
	 * @throws Form_Definition_Exception
	 *
	 */
	public function createForm( string $form_name ): Form;

}