<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait DataModel_Trait
{
	use DataModel_Trait_Definition;
	use DataModel_Trait_IDController;
	use DataModel_Trait_InternalState;
	use DataModel_Trait_MagicMethods;
	use DataModel_Trait_Backend;
	use DataModel_Trait_Load;
	use DataModel_Trait_Save;
	use DataModel_Trait_Delete;
	use DataModel_Trait_Forms;
	use DataModel_Trait_Exports;
}