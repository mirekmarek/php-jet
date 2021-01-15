<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;


/**
 * Available attributes:
 *
 * #[DataModel_Definition(default_order_by: ['property_name','-next_property_name', '+some_property_name'])]
 */

/**
 *
 */
#[DataModel_Definition(
	id_controller_class: DataModel_IDController_Passive::class
)]
abstract class DataModel_Related_MtoN extends BaseObject implements DataModel_Related_MtoN_Interface
{
	use DataModel_Related_MtoN_Trait;

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