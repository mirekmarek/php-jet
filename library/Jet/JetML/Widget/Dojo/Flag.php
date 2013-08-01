<?php
/**
 *
 *
 *
 * Window preloader (blank page with icon in the middle..)
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

class JetML_Widget_Dojo_Flag extends JetML_Widget_Dojo_Abstract {

	/**
	 * @var bool|string
	 */
	protected $dojo_type = false;

	/**
	 * @var string
	 */
	protected $widget_container_tag = "img";


	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {
		$locale = $this->getNodeAttribute("flag");
		$title = $this->getNodeAttribute("title", (new Locale($locale))->getName() );
		$size = $this->getNodeAttribute("size", $this->parser->getIconDefaultSize());

		$icon_URL = $this->getIconURL("flag", $locale, $size);

		$icon_data = $this->parser->getIconSizeData("flag_".$size);

		$this->node->setAttribute("src", $icon_URL);
		$this->node->setAttribute("title", $title);
		$this->node->setAttribute("width", $icon_data["width"]);
		$this->node->setAttribute("height", $icon_data["height"]);


		return parent::getReplacement();
	}

}
