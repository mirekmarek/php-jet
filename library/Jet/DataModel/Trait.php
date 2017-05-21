<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait DataModel_Trait
{
	use DataModel_Trait_Definition;
	use DataModel_Trait_IdObject;
	use DataModel_Trait_InternalState;
	use DataModel_Trait_MagicMethods;
	use DataModel_Trait_Backend;
	use DataModel_Trait_Load;
	use DataModel_Trait_Save;
	use DataModel_Trait_Delete;
	use DataModel_Trait_Forms;
	use DataModel_Trait_Exports;
	use DataModel_Trait_Fetch;

	/**
	 *
	 */
	public function __construct()
	{
		$this->initNewObject();
	}


}