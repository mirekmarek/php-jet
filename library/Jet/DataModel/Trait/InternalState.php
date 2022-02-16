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
trait DataModel_Trait_InternalState
{

	/**
	 * @var bool
	 */
	private bool $_data_model_saved = false;

	/**
	 *
	 */
	public function setIsNew(): void
	{
		$this->_data_model_saved = false;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsNew(): bool
	{
		return !$this->_data_model_saved;
	}

	/**
	 * @return bool
	 */
	public function getIsSaved(): bool
	{
		return $this->_data_model_saved;
	}

	/**
	 *
	 */
	public function setIsSaved(): void
	{
		$this->_data_model_saved = true;
	}

}