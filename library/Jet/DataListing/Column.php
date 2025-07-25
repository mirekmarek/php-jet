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
abstract class DataListing_Column extends DataListing_ElementBase
{
	protected bool $is_visible = true;
	
	protected int $index = 0;
	
	abstract public function getKey(): string;
	
	abstract public function getTitle(): string;
	
	public function getIcon() : string
	{
		return '';
	}
	
	public function isMandatory() : bool
	{
		return false;
	}
	
	public function getDisallowSort(): bool
	{
		return false;
	}
	
	public function getIsVisible(): bool
	{
		if($this->isMandatory()) {
			return true;
		}
		
		return $this->is_visible;
	}
	
	public function setIsVisible( bool $is_visible ): void
	{
		$this->is_visible = $is_visible;
	}
	
	public function getIndex(): int
	{
		return $this->index;
	}
	
	public function setIndex( int $index ): void
	{
		$this->index = $index;
	}
	
	
	
	
	public function getOrderByAsc(): array|string
	{
		return '+'.$this->getKey();
	}
	
	public function getOrderByDesc(): array|string
	{
		return '-'.$this->getKey();
	}
	
	
	public function render( mixed $item ) : string
	{
		$view = $this->listing->getColumnView();
		$view->setVar('item', $item);
		$view->setVar('listing', $this->listing );
		$view->setVar('column', $this );
		
		return $view->render( $this->getKey() );
	}
	
	public function initializer( UI_dataGrid_column $column ) : void
	{
	}
	
	public function getExportHeader() : null|string|array
	{
		return null;
	}
	
	public function getExportData( mixed $item ) : float|int|bool|string|array|object
	{
		return '';
	}
	
}