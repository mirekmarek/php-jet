<?php
/**
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

class JetML_Widget_Dojo_Datagrid extends JetML_Widget_Dojo_Abstract {

	/**
	 *
	 * @var string
	 */
	protected $dojo_type = array(
		"dojox.grid.EnhancedGrid",
		"dojox.grid.enhanced.plugins.Pagination",
		"dojox.grid.enhanced.plugins.IndirectSelection",
	);
	
	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = "table";
	
	/**
	 *
	 * @var array 
	 */
	protected $required_css = array(
			"dojox/grid/enhanced/resources/%THEME%/EnhancedGrid.css",
			"dojox/grid/enhanced/resources/EnhancedGrid_rtl.css",
		);


	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {

		if(!$this->node->hasAttribute("plugins")) {
			$plugins = array(
				"pagination" => array(
					"pageSizes" => array('25', '50', '100'),
					"description" => true,
					"sizeSwitch" => true,
					"pageStepper" => true,
					"gotoButton" => true,
					"maxPageStep" => 10,
					"position" => "bottom"
				),
				"indirectSelection" => array(
					"headerSelector" => true,
					"name" => "Selection",
					"width" => "20px",
					"styles" => "text-align: center;"
				)
			);

			$plugins = str_replace("\"", "'", json_encode($plugins));
			$this->node->setAttribute("plugins", $plugins);
		}

		if(!$this->node->hasAttribute("rowsperpage")) {
			$this->node->setAttribute("rowsperpage", 25);
		}


		return parent::getReplacement();
	}

	/**
	 * @return \DOMElement
	 */
	protected function _getTagContent() {
		$dom = $this->parser->getDOMDocument();

		$thead = $dom->createElement("thead");
		$tr = $dom->createElement("tr");

		$thead->appendChild($tr);

		$child_nodes = array();
		foreach( $this->node->childNodes as $child ) {
			$child_nodes[] = $child;
		}

		foreach( $child_nodes as $child ) {
			$tr->appendChild($child);
		}

		return $thead;


	}
}