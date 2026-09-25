<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\MVC_Controller_Default;

abstract class Admin_ControlCentre_Module_Controller extends MVC_Controller_Default
{
	protected string $output = '';
	
	public function getSpecificationId() : ?string
	{
		return $this->getContent()->getParameter('specification_id');
	}
	
	protected function output( string $view_script ): void
	{
		$this->output .= $this->view->render( $view_script );
		
		$this->content->setOutput( $this->output );
	}
	
}