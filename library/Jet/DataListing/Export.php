<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


abstract class DataListing_Export extends DataListing_ElementBase
{
	abstract public function getKey() : string;
	
	abstract public function getTitle() : string;
	
	public function export( array $column_keys=[] ): void
	{
		if(!$column_keys) {
			$columns = $this->listing->getVisibleColumns();
		} else {
			$columns = [];
			foreach($column_keys as $column_key) {
				$columns[] = $this->listing->column( $column_key );
			}
		}
		
		$export_columns = [];
		$export_header = [];
		foreach( $columns as $col ) {
			$col_header = $col->getExportHeader();
			if( $col_header === null ) {
				continue;
			}
			
			if( is_array( $col_header ) ) {
				$export_columns[] = [$col];
				
				foreach( $col_header as $_col_header ) {
					$export_header[] = $_col_header;
				}
			} else {
				$export_header[] = $col_header;
				
				$export_columns[] = $col;
			}
		}
		
		$ids = $this->listing->getAllIds();
		
		if($this->listing->getExportLimit()>0) {
			if( count( $ids ) > $this->listing->getExportLimit() ) {
				$ids = array_slice( $ids, 0, $this->listing->getExportLimit() );
			}
		}
		
		$data = [];
		foreach( $ids as $id ) {
			$item = $this->listing->itemGetter( $id );
			
			$data_row = [];
			foreach( $export_columns as $col ) {
				if( is_array( $col ) ) {
					foreach( $col[0]->getExportData( $item ) as $d ) {
						$data_row[] = $d;
					}
				} else {
					$data_row[] = $col->getExportData( $item );
				}
			}
			
			$data[] = $data_row;
		}
		
		$this->formatData( $export_header, $data );
		
		Application::end();
	}
	
	abstract protected function formatData( array $export_header, array $data ): void;
	
}