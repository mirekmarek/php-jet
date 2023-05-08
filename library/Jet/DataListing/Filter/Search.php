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
abstract class DataListing_Filter_Search extends DataListing_Filter
{

	protected string $search = '';
	
	public function catchParams(): void
	{
		$this->search = Http_Request::GET()->getString( 'search' );
		$this->listing->setParam( 'search', $this->search );
	}

	public function catchForm( Form $form ): void
	{
		$this->search = $form->field( 'search' )->getValue();

		$this->listing->setParam( 'search', $this->search );
	}

	public function generateFormFields( Form $form ): void
	{
		$search = new Form_Field_Search( 'search', '' );
		$search->setDefaultValue( $this->search );
		$form->addField( $search );
	}

	abstract public function generateWhere() : void;

}
