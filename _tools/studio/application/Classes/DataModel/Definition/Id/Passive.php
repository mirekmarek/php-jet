<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Form_Field;

/**
 *
 */
class DataModel_Definition_Id_Passive extends DataModel_Definition_Id_Abstract {

	/**
	 * @return array
	 */
	public function getOptionsList() : array
	{
		return [];
	}

	/**
	 *
	 * @return Form_Field[]
	 */
	public function getOptionsFormFields() : array
	{
		return [];
	}
}