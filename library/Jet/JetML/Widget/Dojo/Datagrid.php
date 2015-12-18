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

class JetML_Widget_Dojo_Datagrid extends JetML_Widget_Dojo_Abstract {

	/**
	 *
	 * @var string
	 */
	protected $dojo_type = [
		'dojox.grid.EnhancedGrid',
		'dojox.grid.enhanced.plugins.Pagination',
		'dojox.grid.enhanced.plugins.IndirectSelection',
	];
	
	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = 'table';
	
	/**
	 *
	 * @var array 
	 */
	protected $required_css = [
			'dojox/grid/enhanced/resources/%THEME%/EnhancedGrid.css',
			'dojox/grid/enhanced/resources/EnhancedGrid_rtl.css',
	];


	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {

		if(!$this->node->hasAttribute('plugins')) {
			$plugins = [];

			if($this->_getProp('pagination', true)) {
				$plugins['pagination'] = [
					'pageSizes' => $this->_getProp('pagination_pageSizes', ['25', '50', '100']),
					'defaultPageSize' => (int)$this->_getProp('pagination_defaultPageSize', 25),
					'description' => (bool)$this->_getProp('pagination_description', true),
					'sizeSwitch' => (bool)$this->_getProp('pagination_sizeSwitch', true),
					'pageStepper' => (bool)$this->_getProp('pagination_pageStepper', true),
					'gotoButton' => (bool)$this->_getProp('pagination_gotoButton', true),
					'maxPageStep' => (int)$this->_getProp('pagination_maxPageStep', 25),
					'position' => $this->_getProp('pagination_position', 'bottom')
				];
			}

			if($this->_getProp('indirectSelection', true)) {
				$plugins['indirectSelection'] = [
					'headerSelector' => (bool)$this->_getProp('indirectSelection_headerSelector', true),
					'name' => $this->_getProp('indirectSelection_name', 'Selection'),
					'width' => $this->_getProp('indirectSelection_width', '20px'),
					'styles' => $this->_getProp('indirectSelection_style', 'text-align: center;')
				];
			}

			if($plugins) {
				$plugins = str_replace('"', '\'', json_encode($plugins));
				$this->node->setAttribute('plugins', $plugins);
			}

		}



		return parent::getReplacement();
	}

	/**
	 * @param string $key
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	protected function _getProp( $key, $default_value ) {
		$val = $this->node->getAttribute( $key );
		if($val) {
			$val = json_decode($val);
		} else {
			$val = $default_value;
		}

		return $val;
	}

	/**
	 * @return \DOMElement
	 */
	protected function _getTagContent() {
		$dom = $this->parser->getDOMDocument();

		$thead = $dom->createElement('thead');
		$tr = $dom->createElement('tr');

		$thead->appendChild($tr);

		$child_nodes = [];
		foreach( $this->node->childNodes as $child ) {
			$child_nodes[] = $child;
		}

		foreach( $child_nodes as $child ) {
			$tr->appendChild($child);
		}

		return $thead;


	}
}