<?php
/**
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license EUPL 1.2  https://eupl.eu/1.2/en/
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplication;

use Jet\Application_Service_List;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_Select;
use Jet\Http_Headers;
use Jet\Logger;
use Jet\Tr;
use Jet\UI_messages;

abstract class Admin_ControlCentre_ServicesHandler
{
	protected Application_Service_List $list;
	protected ?Form $form = null;
	/**
	 * @var array<string,mixed>
	 */
	protected array $changes = [];
	
	
	public function getList(): Application_Service_List
	{
		return $this->list;
	}
	
	public function getForm(): ?Form
	{
		return $this->form;
	}
	
	
	public function getEditForm() : Form
	{
		if($this->form === null) {
			$this->form = new Form('ervice_edit_form', []);
			
			$config = $this->list->getConfig();
			
			foreach($this->list->getServicesMetaInfo() as $service) {
				
				$name = 'service_'.md5($service->getInterfaceClassName());
				
				$modules = Application_Service_List::findPossibleModules( $service->getInterfaceClassName(), $service->getModuleNamePrefix() );
				
				$scope = [];
				if(!$service->isMultipleMode()) {
					if(
						!$service->isMandatory() ||
						!$modules
					) {
						$scope[''] = '';
					}
					$current = $config[$service->getInterfaceClassName()]??'';
					$select = new Form_Field_Select($name, '');
					$select->setDefaultValue( $current );
					
					$select->setFieldValueCatcher(function() use ($service, $select, $current) {
						$new = $select->getValue();
						if($current==$new) {
							return;
						}
						
						$this->list->setServiceConfig( $service->getInterfaceClassName(), $new );
						$this->changes[$service->getInterfaceClassName()] = $new;
						
					});
					
				} else {
					$current = $config[$service->getInterfaceClassName()]??[];
					$select = new Form_Field_MultiSelect($name, '');
					$select->setDefaultValue( $current );
					$select->input()->addCustomCssStyle('height:200px');
					
					$select->setFieldValueCatcher(function() use ($service, $select, $current) {
						$new = $select->getValue();
						/**
						 * @var array<string> $current
						 */
						if(implode(',', $new)==implode(',', $current)) {
							return;
						}
						
						$this->list->setServiceConfig( $service->getInterfaceClassName(), $new );
						$this->changes[$service->getInterfaceClassName()] = implode(',', $new);
						
					});
					
				}
				
				
				foreach($modules as $module) {
					$scope[$module->getModuleManifest()->getName()] = $module->getModuleManifest()->getLabel();
				}
				
				
				$select->setSelectOptions( $scope );
				
				$this->form->addField( $select );
				
				
			}
		}
		return $this->form;
	}
	
	public function handleControlCentre() : void
	{
		if($this->getEditForm()->catch()) {
			if($this->changes) {
				$ok = true;
				try {
					$this->list->saveCfg();
				} catch( Exception $e ) {
					$ok = false;
					UI_messages::danger( Tr::_('Error during configuration saving: ').$e->getMessage(), context: 'CC' );
				}
				
				if($ok) {
					foreach($this->changes as $ifc => $modules) {
						Logger::info(
							event: 'manager_set',
							event_message: 'Service '.$ifc.' has been set to '.$modules ,
							context_object_id: $ifc,
							context_object_data: [
								'interface_class_name' => $ifc,
								'manager' => $modules
							]
						);
					}
					
					UI_messages::success( Tr::_('Configuration has been saved'), context: 'CC' );
				}
				
			}
			Http_Headers::reload();
		}
		
		
	}
	
}