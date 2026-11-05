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
trait DataModel_Trait_MagicMethods
{

	/**
	 * @param array<string, mixed> $data
	 */
	public function __unserialize( array $data ): void
	{
		foreach( $data as $property => $value ) {
			$this->{$property} = $value;
		}
		$this->setIsSaved();
	}

	/**
	 * @return array<string,mixed>
	 */
	public function __debugInfo(): array
	{
		$r = parent::__debugInfo();

		$r['_data_model_saved'] = $this->getIsSaved();

		if( $this->getLoadFilter() ) {
			$r['_load_filter'] = $this->getLoadFilter();
		}

		return $r;
	}

	/**
	 *
	 */
	public function __clone(): void
	{
		parent::__clone();

		$this->setIsNew();
	}

}