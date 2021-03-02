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
abstract class DataModel_Related_1to1 extends BaseObject implements DataModel_Related_1to1_Interface
{
	use DataModel_Related_1to1_Trait;

	/**
	 *
	 */
	public function afterLoad(): void
	{

	}

	/**
	 *
	 */
	public function beforeSave(): void
	{

	}

	/**
	 *
	 */
	public function afterAdd(): void
	{

	}

	/**
	 *
	 */
	public function afterUpdate(): void
	{

	}

	/**
	 *
	 */
	public function afterDelete(): void
	{

	}


}