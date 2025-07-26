<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Config;
use Jet\Form;
use Jet\Form_Definition_Interface;
use Jet\Form_Definition_Trait;
use Jet\Http_Headers;
use Jet\Tr;
use Jet\UI_messages;
use Exception;

abstract class JetStudio_Module_Config extends Config implements Form_Definition_Interface {
	use Form_Definition_Trait;
	
	protected JetStudio_Module $module;
	
	protected ?Form $setup_form = null;
	
	public function __construct( JetStudio_Module $module )
	{
		$this->module = $module;
		$this->setData(
			$this->readConfigFileData()
		);
		
	}
	
	public function getConfigFilePath(): string
	{
		return dirname(__DIR__, 3).'/config/'.$this->module->getManifest()->getName().'/configuration.php';
	}
	
	public function getSetupForm(): Form
	{
		if(!$this->setup_form) {
			$this->setup_form = $this->createForm('setup_form');
		}
		
		return $this->setup_form;
	}
	
	public function handleCatchSetupForm() : void
	{
		$form = $this->getSetupForm();
		
		if($form->catch()) {
			$ok = true;
			try {
				$this->saveConfigFile();
			} catch( Exception $e ) {
				$ok = false;
				UI_messages::danger( Tr::_('Error during configuration saving: ').$e->getMessage(), context: 'setup' );
			}
			
			if($ok) {
				UI_messages::success( Tr::_('Configuration has been saved'), context: 'setup' );
			}
			
			Http_Headers::reload();
		}
		
	}

}