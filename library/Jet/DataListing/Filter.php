<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
abstract class DataListing_Filter extends DataListing_ElementBase
{
	
	abstract public function getKey() : string;
	
	abstract public function catchParams(): void;
	
	abstract public function generateFormFields( Form $form ): void;
	
	abstract public function catchForm( Form $form ): void;
	
	abstract public function generateWhere() : void;
	
	public function renderForm() : string
	{
		$view = $this->listing->getFilterView();
		$view->setVar('filter', $this );
		$view->setVar('listing', $this->listing);
		
		return $view->render( $this->getKey() );
	}

}
