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
trait DataListing_Traits_Filters
{
	
	/**
	 * @var DataListing_Filter[]
	 */
	protected array $filters = [];
	
	protected ?array $filter_where = null;
	
	protected ?Form $filter_form = null;
	
	/**
	 * @return DataListing_Filter[]
	 */
	public function getFilters() : array
	{
		return $this->filters;
	}
	
	public function addFilter( DataListing_Filter $filter ) : void
	{
		$this->filters[$filter->getKey()] = $filter;
		$filter->setListing( $this );
	}
	
	public function filterExists( string $filter_key ) : bool
	{
		return isset( $this->filters[$filter_key] );
	}
	
	
	public function filter( string $key ) : DataListing_Filter
	{
		return $this->filters[$key];
	}
	
	
	
	protected function catchFilterParams(): void
	{
		foreach( $this->filters as $filter ) {
			$filter->catchParams();
		}
	}
	
	public function getFilterForm(): Form
	{
		if( !$this->filter_form ) {
			$this->filter_form = new Form( 'filter_form', [] );
			$this->filter_form->setAction( $this->getURI() );
			
			foreach( $this->filters as $filter ) {
				$filter->generateFormFields( $this->filter_form );
			}
		}
		
		return $this->filter_form;
	}
	
	protected function catchFilterForm(): void
	{
		$form = $this->getFilterForm();
		
		if(
			$form->catchInput() &&
			$form->validate()
		) {
			foreach( $this->filters as $filter ) {
				$filter->catchForm( $form );
			}
			
			$this->setPageNo( 1 );
			
			Http_Headers::movedTemporary( $this->getURI() );
		}
	}
	
	
	public function addFilterWhere( array $where ): void
	{
		if( $this->filter_where ) {
			$this->filter_where[] = 'AND';
		}
		
		$this->filter_where[] = $where;
	}
	
	public function getDefaultFilterWhere() : array
	{
		return [];
	}
	
	public function getFilterWhere(): array
	{
		if( $this->filter_where === null ) {
			
			$this->filter_where = $this->getDefaultFilterWhere();
			
			foreach( $this->filters as $filter ) {
				$filter->generateWhere();
			}
			
		}
		return $this->filter_where;
	}
	
}