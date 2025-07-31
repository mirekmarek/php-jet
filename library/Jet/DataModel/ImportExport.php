<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;
use Error;

class DataModel_ImportExport extends BaseObject
{
	public static function export(
		string|DataModel $data_model_class_name,
		string $target_dir_path,
		?array $where = null,
		?DataModel_ImportExport_ExportPreprocessor $preprocessor=null
	): string
	{
		if(!$where) {
			$where = [];
		}
		
		$target_dir_path = rtrim($target_dir_path, '/');
		$target_dir_path = rtrim($target_dir_path, '\\');
		
		$target_file_name = str_replace('\\', '_', $data_model_class_name);
		$target_file_name .= '_'.Data_DateTime::now()->format('YmdHis');
		
		$dir = $target_dir_path.'/'.$target_file_name.'/';
		IO_Dir::create( $dir );
		
		$data_model_class_name::getBackendInstance()->getType();
		
		$ids = $data_model_class_name::fetchIDs( $where );
		
		$meta_info = new DataModel_ImportExport_MetaInfo();
		
		$meta_info->setDataModelClassName( $data_model_class_name );
		$meta_info->setSourceBackendType( $data_model_class_name::getBackendInstance()->getType() );
		$meta_info->setSourceWhere( $where );
		$meta_info->setRecordCount( count( $ids ) );
		$meta_info->setExportDateTime( Data_DateTime::now() );
		
		$meta_info->save( $dir );
		
		foreach( $ids as $id ) {
			$file_path = $dir.$id->toString().'.dat';
			
			$data = $data_model_class_name::loadData( $id );
			
			if(
				$preprocessor &&
				!$preprocessor->preprocess( $meta_info, $data )
			) {
				continue;
			}
			
			IO_File::write( $file_path, serialize( $data ) );
		}
		
		
		return $dir;
	}
	
	public static function import( string $dir_path, ?DataModel_ImportExport_ImportPreprocessor $preprocessor=null ): void
	{
		$meta_info_file_path = $dir_path.'/'.DataModel_ImportExport_MetaInfo::META_INFO_FILE_NAME;
		
		if(!IO_File::exists( $meta_info_file_path )) {
			throw new DataModel_ImportExport_Exception("Meta info file '$meta_info_file_path' does not exist");
			return;
		}
		
		if(!IO_File::isReadable( $meta_info_file_path )) {
			throw new DataModel_ImportExport_Exception("Meta info file '$meta_info_file_path' is not readable");
			return;
		}
		
		try {
			$meta_info = DataModel_ImportExport_MetaInfo::read( $meta_info_file_path );
		} catch( Error|\Exception $error ) {
			throw new DataModel_ImportExport_Exception("Error during meta info file '$meta_info_file_path' reading: '{$error->getMessage()}'");
			return;
		}
		
		$data_model_class_name = $meta_info->getDataModelClassName();
		$definition = DataModel_Definition::get( $data_model_class_name );
		
		$path = '';
		try {
			$files = IO_Dir::getFilesList( $dir_path, '*.dat' );
			foreach( $files as $path => $name ) {
				
				/**
				 * @var DataModel_LoadedData $data
				 */
				$data = unserialize( IO_File::read( $path ) );
				
				if(
					$preprocessor &&
					!$preprocessor->preprocess( $meta_info, $data )
				) {
					continue;
				}
				
				$main_data = $data->getMainData();
				$related_data = $data->getRelatedData();
				
				$item = new $data_model_class_name();
				$obj = $data_model_class_name::initByData( $main_data, $related_data );
				$obj->setIsNew( true );
				$obj->save();
				
			}
		} catch( Error|\Exception $error ) {
			throw new DataModel_ImportExport_Exception("Error during data import: '{$error->getMessage()}', data file: '$path'");
			
		}
	}
	
}