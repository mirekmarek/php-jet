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
use Jet\Translator;

abstract class JetStudio_Module extends BaseObject {

	protected JetStudio_Module_Manifest $manifest;
	protected ?MVC_View $view = null;
	
	public function __construct( JetStudio_Module_Manifest $manifest )
	{
		$this->manifest = $manifest;
	}
	
	public function getManifest(): JetStudio_Module_Manifest
	{
		return $this->manifest;
	}
	
	public function getName() : string
	{
		return $this->manifest->getName();
	}
	
	
	
	public function getView() : MVC_View
	{
		if(!$this->view) {
			$this->view = new MVC_View( $this->manifest->getBaseDir().'/views/' );
		}
		
		return $this->view;
	}
	
	public function output( string $view_script ) : void
	{
		JetStudio::output( $this->getView()->render( $view_script ) );
		JetStudio::renderLayout();
		
	}
	
	public function handle() : void
	{
		Translator::setCurrentDictionary( $this->manifest->getDictionaryName() );
		
		$controller_class_name = $this->manifest->getNamespace().'\\Controller';
		/**
		 * @var JetStudio_Module_Controller $controller
		 */
		$controller = new $controller_class_name( $this );
		
		$controller->handle();
	}
}