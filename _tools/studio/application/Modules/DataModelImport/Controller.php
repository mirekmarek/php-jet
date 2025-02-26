<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\DataModelImport;

use Error;
use Exception;
use Jet\DataModel_ImportExport;
use Jet\DataModel_ImportExport_MetaInfo;
use Jet\Http_Request;
use Jet\IO_Dir;
use Jet\IO_File;
use JetStudio\JetStudio_Conf_Path;
use JetStudio\JetStudio_Module_Controller;

class Controller extends JetStudio_Module_Controller
{
	protected function resolve(): string
	{
		$action = Http_Request::GET()->getString( 'action' );
		return $action ? : 'default';
	}
	
	public function default_Action(): void
	{
		
		$dir = JetStudio_Conf_Path::getTmp().'_data_model_imports/';
		if(!IO_Dir::exists($dir)) {
			IO_Dir::create($dir);
		}
		
		$this->view->setVar( 'imports_dir', $dir );
		
		$_imports = IO_Dir::getSubdirectoriesList( $dir );
		
		$imports = [];
		$import_dirs = [];
		foreach($_imports as $path=>$dir_name) {
			$meta_info_file_path = $path.DataModel_ImportExport_MetaInfo::META_INFO_FILE_NAME;
			
			if(!IO_File::isReadable($meta_info_file_path)) {
				continue;
			}
			
			$imports[$dir_name] = DataModel_ImportExport_MetaInfo::read( $meta_info_file_path );
			$import_dirs[$dir_name] = $path;
		}
		
		$selected_import_name = Http_Request::GET()->getString( 'import', valid_values: array_keys($imports) );
		
		$this->view->setVar( 'imports', $imports );
		$this->view->setVar( 'selected_import_name', $selected_import_name );
		
		
		if(
			$selected_import_name &&
			Http_Request::GET()->getString('perform_import')=='do'
		) {
			try {
				DataModel_ImportExport::import( $import_dirs[$selected_import_name] );
				
				$this->output('done');
				
			} catch( Error|Exception $e ) {
				$this->view->setVar('error', $e->getMessage());
				$this->output('error');
			}
			
		} else {
			
			$this->output('main');
			
		}
		
		
		
	}
	
}