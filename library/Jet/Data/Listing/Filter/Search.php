<?php /** @noinspection PhpUnusedAliasInspection */

/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Jet\Form;
use Jet\Form_Field_Search;
use Jet\Http_Request;

/**
 *
 */
abstract class Data_Listing_Filter_Search extends Data_Listing_Filter
{

	/**
	 * @var string
	 */
	protected string $search = '';

	/**
	 *
	 */
	public function catchGetParams(): void
	{
		$this->search = Http_Request::GET()->getString( 'search' );
		$this->listing->setGetParam( 'search', $this->search );
	}

	/**
	 * @param Form $form
	 */
	public function catchForm( Form $form ): void
	{
		$this->search = $form->field( 'search' )->getValue();

		$this->listing->setGetParam( 'search', $this->search );
	}

	/**
	 * @param Form $form
	 */
	public function generateFormFields( Form $form ): void
	{
		$search = new Form_Field_Search( 'search', '' );
		$search->setDefaultValue( $this->search );
		$form->addField( $search );
	}

	/**
	 *
	 */
	abstract public function generateWhere() : void;

}
