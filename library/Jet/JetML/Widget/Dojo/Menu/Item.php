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

class JetML_Widget_Dojo_Menu_Item extends JetML_Widget_Dojo_Abstract {
		
	/**
	 *
	 * @var string
	 */
	protected $dojo_type = "dijit.MenuItem";

	/**
	 * @return \DOMElement|\DOMElement[]
	 */
	protected function _getTagContent() {
		return $this->getIcon();
	}
}