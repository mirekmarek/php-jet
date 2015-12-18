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

class JetML_Widget_Icon extends JetML_Widget_Dojo_Abstract {

	/**
	 * @var bool|string
	 */
	protected $dojo_type = false;

	/**
	 * @var string
	 */
	protected $widget_container_tag = 'img';


	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {
		$icon = $this->getNodeAttribute('icon');
		$size = $this->getNodeAttribute('size', $this->parser->getIconDefaultSize());

		$icon_URL = $this->getIconURL('icon', $icon, $size);

		$icon_data = $this->parser->getIconSizeData($size);

		$attributes = $this->getNodeAttributes();

		$attributes['src'] = $icon_URL;
		$attributes['width'] = $icon_data['width'];
		$attributes['height'] = $icon_data['height'];

		return $this->createNode('img', false, $attributes);

	}

}