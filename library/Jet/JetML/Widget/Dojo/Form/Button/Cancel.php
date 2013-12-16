<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package JetML
 */
namespace Jet;

class JetML_Widget_Dojo_Form_Button_Cancel extends JetML_Widget_Dojo_Form_Button {
	

	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {
		if(!$this->node->hasAttribute("class")) {
			$this->node->setAttribute("class", "buttonCancel");
		}
		if(!$this->node->hasAttribute("title")) {
			$this->node->setAttribute("title", "Cancel" );
		}
		if(!$this->node->hasAttribute("icon")) {
			$this->node->setAttribute("icon", "cancel" );
		}

		return parent::getReplacement();
	}

}