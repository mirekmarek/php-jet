<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\DataModelExport;

use Error;
use Exception;
use Jet\DataModel_ImportExport;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Http_Request;
use Jet\IO_Dir;
use JetStudio\JetStudio;
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
		
		$default_dir = JetStudio_Conf_Path::getTmp().'_data_model_exports/';
		if(!IO_Dir::exists($default_dir)) {
			IO_Dir::create($default_dir);
		}
		
		$data_model_classes = JetStudio::getModule_DataModel()?->getDataModelClasses()??[];
		
		$options = [];
		foreach($data_model_classes as $class_name => $class) {
			$options[$class_name] = $class->getFullClassName();
		}
		
		$select_class = new Form_Field_Select('class', 'DataModel Class:');
		$select_class->setSelectOptions( $options );
		
		$dir = new Form_Field_Input('dir', 'Export dir:');
		$dir->setIsRequired( true );
		$dir->setDefaultValue( $default_dir );
		$dir->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter export directory path',
			'dir_does_not_exist' => "Directory '%DIR%' does not exist",
			'dir_is_writable' =>  "Directory '%DIR%' is not writable",
		]);
		$dir->setValidator( function( Form_Field_Input $input ) : bool {
			$value = $input->getValueRaw();
			
			if(!IO_Dir::exists($value)) {
				$input->setError('dir_does_not_exist', ['DIR' => $value]);
				return false;
			}
			
			if(!IO_Dir::isWritable($value)) {
				$input->setError('dir_is_writable', ['DIR' => $value]);
				return false;
			}
			
			return true;
		} );
		
		$form = new Form('export', [$select_class, $dir]);
		
		$this->view->setVar( 'form', $form );
		
		if($form->catch()) {
			$dir = $form->field('dir')->getValue();
			$class = $form->field('class')->getValue();
			
			set_time_limit(0);
			
			try {
				$where_is_export = DataModel_ImportExport::export( $class, $dir );
				
				$this->view->setVar('where_is_export', $where_is_export);
				$this->output('done');
				
			} catch( Error|Exception $e ) {
				
				$this->view->setVar('error', $e->getMessage());
				$this->output('error');
				
			}
			
			
		} else {
			$this->output( 'main' );
		}
		
	}
	
}