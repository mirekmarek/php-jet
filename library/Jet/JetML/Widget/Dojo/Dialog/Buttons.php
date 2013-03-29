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

class JetML_Widget_Dojo_Dialog_Buttons extends JetML_Widget_Dojo_Abstract {
	/**
	 * @var bool|string
	 */
	protected $dojo_type = false;

	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {
		$this->node->setAttribute("class", "dijitDialogPaneActionBar");

		return parent::getReplacement();
	}

}