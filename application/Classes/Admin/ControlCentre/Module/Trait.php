<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Factory_MVC;
use Jet\Tr;
use Jet\Translator;

/**
 * @var string $control_centre_group;
 * @var string $control_centre_title;
 * @var string $control_centre_icon;
 * @var int $control_centre_priority;
 * @var bool $control_cnter_specification_mode;
 */
trait Admin_ControlCentre_Module_Trait {
	
	
	public function getControlCentreGroup() : string
	{
		return $this->control_centre_group;
	}
	
	public function getControlCentreTitle() : string
	{
		return $this->control_centre_title;
	}
	
	public function getControlCentreIcon() : string
	{
		return $this->control_centre_icon;
	}
	
	public function getControlCentrePriority() : int
	{
		return $this->control_centre_priority;
	}
	
	public function getControlCentreSpecificationMode() : bool
	{
		return $this->control_cnter_specification_mode;
	}
	
	public function getControlCentreSpecificationList() : array
	{
		return [];
	}
	
	
	public function getControlCentreTitleTranslated() : string
	{

		return Translator::setCurrentDictionaryTemporary( $this->getModuleManifest()->getName(), function() : string {
			return Tr::_( $this->getControlCentreTitle() );
		} );
	}
	
	public function handleControlCentre( ?string $specification_id=null ) : string
	{
		return Translator::setCurrentDictionaryTemporary( $this->getModuleManifest()->getName(), function() use ($specification_id) : string {
			$page_content = Factory_MVC::getPageContentInstance();
			
			$page_content->setModuleName( $this->getModuleManifest()->getName() );
			$page_content->setControllerName( 'ControlCentre' );
			$page_content->setParameter( 'specification_id', $specification_id );
			
			$page_content->dispatch();
			
			/** @phpstan-ignore return.type */
			return $page_content->getOutput();
		});
	}
	
	
}