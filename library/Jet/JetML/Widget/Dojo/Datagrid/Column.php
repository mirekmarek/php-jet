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

class JetML_Widget_Dojo_Datagrid_Column extends JetML_Widget_Dojo_Abstract {

	/**
	 * @var bool|string
	 */
	protected $dojo_type = false;

	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = "th";

	/**
	 * @return \DOMElement
	 *
	 * @throws JetML_Exception
	 */
	public function getReplacement() {
		if(!$this->node->hasAttribute("field")) {
			throw new JetML_Exception(
				"Field property is not specified!",
				JetML_Exception::CODE_WIDGET_ERROR
			);
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