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
abstract class Data_Listing_Filter
{

	protected Data_Listing $listing;

	/**
	 * @param Data_Listing $listing
	 */
	public function __construct( Data_Listing $listing )
	{
		$this->listing = $listing;
	}

	/**
	 *
	 */
	abstract public function catchGetParams(): void;

	/**
	 * @param Form $form
	 */
	abstract public function generateFormFields( Form $form ): void;

	/**
	 * @param Form $form
	 */
	abstract public function catchForm( Form $form ): void;

	/**
	 *
	 */
	abstract public function generateWhere() : void;

}
