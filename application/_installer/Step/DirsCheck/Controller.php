<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

class Installer_Step_DirsCheck_Controller extends Installer_Step_Controller {

	public function main() {
		if(Http_Request::POST()->exists("go")) {
			$this->installer->goNext();
		}


		$this->render("default");
	}

	public function getLabel() {
		return Tr::_("Check directories permissions", array(), "DirsCheck");
	}

}
