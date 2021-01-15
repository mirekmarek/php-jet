<?php /** @noinspection PhpUnusedAliasInspection */

/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

use Jet\Form;
use Jet\Form_Field_Search;
use Jet\Http_Request;

/**
 *
 */
trait Data_Listing_Filter_search
{

	/**
	 * @var string
	 */
	protected string $search = '';

	/**
	 *
	 */
	protected function filter_search_catchGetParams(): void
	{
		$this->search = Http_Request::GET()->getString( 'search' );
		$this->setGetParam( 'search', $this->search );
	}

	/**
	 * @param Form $form
	 */
	public function filter_search_catchForm( Form $form ): void
	{
		$value = $form->field( 'search' )->getValue();

		$this->search = $value;
		$this->setGetParam( 'search', $value );
	}

	/**
	 * @param Form $form
	 */
	protected function filter_search_getForm( Form $form ): void
	{
		$search = new Form_Field_Search( 'search', '', $this->search );
		$form->addField( $search );
	}

}
