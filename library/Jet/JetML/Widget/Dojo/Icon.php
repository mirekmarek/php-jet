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

class JetML_Widget_Dojo_Icon extends JetML_Widget_Dojo_Abstract {

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

		$this->node->setAttribute('src', $icon_URL);
		foreach($icon_data as $k=>$v ) {
			$this->node->setAttribute($k, $v);
		}

		return parent::getReplacement();
	}

}