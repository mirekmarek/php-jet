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
class DataModel_LoadedData extends BaseObject
{
	/**
	 * @var array<string,mixed>
	 */
	protected array $main_data;
	/**
	 * @var array<string,array<string,mixed>>
	 */
	protected array $related_data;
	
	/**
	 * @param array<string,mixed> $main_data
	 * @param array<string,array<string,mixed>> $related_data
	 */
	public function __construct( array $main_data, array $related_data )
	{
		$this->main_data = $main_data;
		$this->related_data = $related_data;
	}
	
	/**
	 * @param array<string,mixed> $main_data
	 * @return void
	 */
	public function setMainData( array $main_data ): void
	{
		$this->main_data = $main_data;
	}
	
	/**
	 * @param array<string,array<string,mixed>> $related_data
	 * @return void
	 */
	public function setRelatedData( array $related_data ): void
	{
		$this->related_data = $related_data;
	}
	
	/**
	 * @return array<string,mixed>
	 */
	public function getMainData(): array
	{
		return $this->main_data;
	}
	
	/**
	 * @return array<string,array<string,mixed>>
	 */
	public function getRelatedData(): array
	{
		return $this->related_data;
	}
	
}