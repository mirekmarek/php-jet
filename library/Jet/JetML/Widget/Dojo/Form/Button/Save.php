<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package JetML
 */
namespace Jet;

class JetML_Widget_Dojo_Form_Button_Save extends JetML_Widget_Dojo_Form_Button_Busy {


	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {
		if(!$this->node->hasAttribute("busyLabel")) {
			$this->node->setAttribute("busyLabel", Tr::_("Saving ..."));
		}
		if(!$this->node->hasAttribute("title")) {
			$this->node->setAttribute("title", Tr::_("Save") );
		}
		if(!$this->node->hasAttribute("icon")) {
			$this->node->setAttribute("icon", "save" );
		}

		return parent::getReplacement();
	}

}