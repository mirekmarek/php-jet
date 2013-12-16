<?php
/**
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

class JetML_Widget_Dojo_Form_Button extends JetML_Widget_Dojo_Abstract {
	
	/**
	 *
	 * @var string
	 */
	protected $dojo_type = "dijit.form.Button";

	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = "button";

	/**
	 * @return \DOMElement|\DOMElement[]
	 */
	protected function _getTagContent() {
		return $this->getIcon();
	}

}