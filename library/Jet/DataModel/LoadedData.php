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
	protected array $main_data;
	protected array $related_data;
	
	public function __construct( array $main_data, array $related_data )
	{
		$this->main_data = $main_data;
		$this->related_data = $related_data;
	}
	
	public function setMainData( array $main_data ): void
	{
		$this->main_data = $main_data;
	}
	
	public function setRelatedData( array $related_data ): void
	{
		$this->related_data = $related_data;
	}
	
	
	
	public function getMainData(): array
	{
		return $this->main_data;
	}
	
	public function getRelatedData(): array
	{
		return $this->related_data;
	}
	
}