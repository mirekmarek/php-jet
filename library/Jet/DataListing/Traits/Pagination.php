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
trait DataListing_Traits_Pagination
{
	protected int $pagination_page_no = 1;
	
	protected int $pagination_items_per_page = 0;
	
	protected function setPageNo( int $page_no ): void
	{
		$this->pagination_page_no = $page_no;
		$this->setParam( SysConf_Jet_DataListing::getPaginationPageNoGetParam(), $page_no );
	}
	
	protected function setItemsPerPage( int $items_per_page ): void
	{
		
		if( $items_per_page > SysConf_Jet_DataListing::getPaginationMaxItemsPerPage() ) {
			$items_per_page = SysConf_Jet_DataListing::getPaginationMaxItemsPerPage();
		}
		
		$this->pagination_items_per_page = $items_per_page;
		$this->setParam( SysConf_Jet_DataListing::getPaginationItemsPerPageParam(), $items_per_page );
	}
	
	
	protected function catchPaginationParams(): void
	{
		$GET = Http_Request::GET();
		
		$param = SysConf_Jet_DataListing::getPaginationPageNoGetParam();
		if( $GET->exists( $param ) ) {
			$this->setPageNo( $GET->getInt( $param ) );
		}
		
		$param = SysConf_Jet_DataListing::getPaginationItemsPerPageParam();
		if( $GET->exists( $param ) ) {
			$this->setItemsPerPage( $GET->getInt( $param ) );
		}
	}
	
	protected function getPageNo(): int
	{
		return $this->pagination_page_no;
	}
	
	protected function getItemsPerPage(): int
	{
		if( !$this->pagination_items_per_page ) {
			return SysConf_Jet_DataListing::getPaginationDefaultItemsPerPage();
		}
		
		return $this->pagination_items_per_page;
	}
	
	
	
}