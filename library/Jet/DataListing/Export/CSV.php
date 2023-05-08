<?php
namespace Jet;

abstract class DataListing_Export_CSV extends DataListing_Export
{
	abstract protected function generateFileName(  ) : string;
	
	protected function formatData( array $export_header, array $data ): void
	{
		$file_name = $this->generateFileName();
		
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename="' . $file_name . '"' );
		header( 'Cache-Control: max-age=0' );
		
		$fp = fopen('php://output', 'w');
		
		fputcsv( $fp, $export_header );
		
		foreach( $data as $row ) {
			fputcsv( $fp, $row );
		}
		
		fclose( $fp );
		
	}
}