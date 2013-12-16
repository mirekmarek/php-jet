<?php
/**
 *
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

class JetML_Widget_Dojo_Layout_Tabs_Tab extends JetML_Widget_Dojo_Layout_Pane {

	/**
	 *
	 * @var string
	 */
	protected $dojo_type = "dijit.layout.BorderContainer";


	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {
		if($this->node->hasAttribute("gutters")) {
			$this->node->setAttribute("gutters", "false");
		}

		return parent::getReplacement();
	}
}