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

class JetML_Widget_Dojo_Form_Button_Dropdown extends JetML_Widget_Dojo_Abstract {
	
	/**
	 *
	 * @var string
	 */
	protected $dojo_type = 'dijit.form.DropDownButton';

	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = 'div';

	/**
	 * @return \DOMElement|\DOMElement[]
	 */
	protected function _getTagContent() {
		$icon = $this->getIcon();

		if(!$icon) {
			return null;
		}

		$span = $this->parser->getDOMDocument()->createElement('span');
		if(!is_array($icon)) {
			$span->appendChild( $icon );

		} else {
			$span->appendChild( $icon[0] );
			$span->appendChild( $icon[1] );

		}

		return $span;
	}

}