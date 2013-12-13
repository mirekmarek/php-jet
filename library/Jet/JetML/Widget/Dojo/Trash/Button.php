<?php
/**
 *
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

class JetML_Widget_Dojo_Trash_Button extends JetML_Widget_Dojo_Abstract {
	/**
	 * @var string
	 */
	protected $dojo_type = "dijit.form.Button";

	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = "button";

	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {

		$this->node->setAttribute("id", $this->node->getAttribute("id")."_button");
		$this->node->setAttribute("icon", "trash" );

		if(!$this->node->hasAttribute("title")) {
			$this->node->setAttribute("title", "Delete" );
		}

		return parent::getReplacement();
	}


	/**
	 * @return \DOMElement|\DOMElement[]
	 */
	protected function _getTagContent() {
		return $this->getIcon();
	}

}