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
class DataModel_Definition_Model_Related_1toN extends DataModel_Definition_Model_Related
{

	/**
	 * @var string
	 */
	protected string $iterator_class = DataModel_Related_1toN_Iterator::class;

	/**
	 * @return string
	 */
	public function getIteratorClassName(): string
	{
		return $this->iterator_class;
	}

	/**
	 * @param string $iterator_class
	 */
	public function setIteratorClass( string $iterator_class ): void
	{
		$this->iterator_class = $iterator_class;
	}


	/**
	 * @throws DataModel_Exception
	 */
	protected function _initParents(): void
	{
		parent::_initParents();

		$iterator_class = $this->getClassArgument( 'iterator_class', null );
		if( $iterator_class ) {
			$this->iterator_class = $iterator_class;
		}
	}
}