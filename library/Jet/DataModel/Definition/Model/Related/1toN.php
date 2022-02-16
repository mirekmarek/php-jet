<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class DataModel_Definition_Model_Related_1toN extends DataModel_Definition_Model_Related
{


	/**
	 *
	 */
	public function init(): void
	{
		parent::init();

		$this->default_order_by = $this->getClassArgument( 'default_order_by', [] );
	}


	/**
	 * @var array
	 */
	protected array $default_order_by = [];


	/**
	 * @return array
	 */
	public function getDefaultOrderBy(): array
	{
		return $this->default_order_by;
	}

	/**
	 * @param array $default_order_by
	 */
	public function setDefaultOrderBy( array $default_order_by ): void
	{
		$this->default_order_by = $default_order_by;
	}

}