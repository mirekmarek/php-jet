<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\MVC_View;

abstract class JetStudio_Module_Controller extends BaseObject {

	protected JetStudio_Module $module;
	protected MVC_View $view;
	
	public function __construct( JetStudio_Module $module )
	{
		$this->module = $module;
		$this->view = $module->getView();
	}
	
	public function output( string $view_script ) : void
	{
		$this->module->output( $view_script );
	}
	
	protected function resolve() : string
	{
		return 'default';
	}
	
	public function handle() : void
	{
		$action = $this->resolve();
		
		$method = $action.'_Action';
		
		$this->$method();
	}
	
	
}