<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\DataModel;

use Jet\DataModel;
use Jet\Factory_DataModel;
use JetStudio\ClassCreator_Config;
use JetStudio\ClassMetaInfo;
use JetStudio\JetStudio_Module;
use JetStudio\JetStudio_Module_Manifest;
use JetStudio\JetStudio_Module_Service_DataModel;
use Jet\DataModel_Definition_Model_Main as Jet_DataModel_Definition_Model_Main;
use JetStudio\JetStudio_Module_Service_SetupModule;

class Main extends JetStudio_Module implements JetStudio_Module_Service_DataModel, JetStudio_Module_Service_SetupModule
{
	public function __construct( JetStudio_Module_Manifest $manifest )
	{
		$this->manifest = $manifest;
		
		$property_definition_class_names = [
			DataModel::TYPE_ID               => DataModel_Definition_Property_Id::class,
			DataModel::TYPE_ID_AUTOINCREMENT => DataModel_Definition_Property_IdAutoIncrement::class,
			DataModel::TYPE_STRING           => DataModel_Definition_Property_String::class,
			DataModel::TYPE_BOOL             => DataModel_Definition_Property_Bool::class,
			DataModel::TYPE_INT              => DataModel_Definition_Property_Int::class,
			DataModel::TYPE_FLOAT            => DataModel_Definition_Property_Float::class,
			DataModel::TYPE_LOCALE           => DataModel_Definition_Property_Locale::class,
			DataModel::TYPE_DATE             => DataModel_Definition_Property_Date::class,
			DataModel::TYPE_DATE_TIME        => DataModel_Definition_Property_DateTime::class,
			DataModel::TYPE_CUSTOM_DATA      => DataModel_Definition_Property_CustomData::class,
			DataModel::TYPE_DATA_MODEL       => DataModel_Definition_Property_DataModel::class,
		];
		foreach($property_definition_class_names as $type=>$class_name) {
			Factory_DataModel::setPropertyDefinitionClassName($type, $class_name);
		}
		
		$model_definition_class_names = [
			DataModel::MODEL_TYPE_MAIN         => DataModel_Definition_Model_Main::class,
			DataModel::MODEL_TYPE_RELATED_1TO1 => DataModel_Definition_Model_Related_1to1::class,
			DataModel::MODEL_TYPE_RELATED_1TON => DataModel_Definition_Model_Related_1toN::class,
		];
		
		foreach($model_definition_class_names as $type=>$class_name) {
			Factory_DataModel::setModelDefinitionClassName($type, $class_name);
		}
		
		
		$this->initConfiguration();
	}
	
	protected function initConfiguration(): void
	{
		$cfg = $this->getConfig();
		
		ClassCreator_Config::setAddDocBlocksAlways( $cfg->getAddDocBlocksAlways() );
		ClassCreator_Config::setPreferPropertyHooks( $cfg->getPreferPropertyHooks() );
	}
	
	/**
	 * @return ClassMetaInfo[]
	 */
	public function getDataModelClasses( bool $main_only=true ) : array {
		$res = [];
		
		foreach(DataModels::getClasses() as $class) {
			if($main_only) {
				$model = $class->getDefinition();
				if( !$model instanceof Jet_DataModel_Definition_Model_Main ) {
					continue;
				}
			}
			
			$res[$class->getFullClassName()] = $class;
		}

		return $res;
	}
	
	
	public function handleSetup(): string
	{
		$view = $this->getView();
		
		$config = $this->getConfig();
		$config->handleCatchSetupForm();
		
		$view->setVar('config', $config);
		
		
		return $view->render('setup');
	}
}
