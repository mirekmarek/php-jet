<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

interface Admin_ControlCentre_Module_Interface {
	
	public function getControlCentreGroup() : string;
	
	public function getControlCentreTitle() : string;
	
	public function getControlCentreTitleTranslated() : string;
	
	public function getControlCentreIcon() : string;
	
	public function getControlCentrePriority() : int;
	
	public function getControlCentreSpecificationMode() : bool;
	
	public function getControlCentreSpecificationList() : array;
	
	public function handleControlCentre( ?string $specification_id ) : string;
	
}