<?php
/**
 *
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

class JetML_Widget_Dojo_Editarea extends JetML_Widget_Abstract {

	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {
		$dom = $this->parser->getDOMDocument();

		$ID = $this->getNodeAttribute('id');

        $Dojo = new Javascript_Lib_Dojo();
        $Dojo->requireComponent('dijit.layout.BorderContainer');
        $Dojo->requireComponent('dijit.layout.ContentPane');
        $Dojo->requireComponent('dijit.Toolbar');
        $this->parser->getLayout()->requireJavascriptLib($Dojo);


		$border_container = $dom->createElement('div');
		$border_container->setAttribute('id', $ID.'_container');
		$border_container->setAttribute('data-dojo-type', 'dijit.layout.BorderContainer');
		$border_container->setAttribute('data-dojo-props', 'region:\'center\',gutters:false');

		$toolbar = $dom->createElement('div');
		$toolbar->setAttribute('id', $ID.'_toolbar');
		$toolbar->setAttribute('data-dojo-type', 'dijit.Toolbar');
		$toolbar->setAttribute('data-dojo-props', 'region:\'top\'');

		if(
			$this->getNodeAttribute('hidden', 'false')=='true'
		) {
			$toolbar->setAttribute('style', 'display:none;');
		}

		$border_container->appendChild($toolbar);

		$b_navigation = $dom->createElement('div');
		$b_navigation->setAttribute('class', 'editAreaBn');

		$toolbar->appendChild($b_navigation);



		$list_title_opened = $dom->createElement('span');
		$list_title_opened->setAttribute('id', $ID.'_list_title_opened');
		$list_title_opened->setAttribute('style', 'display:none');

		$a_js = $dom->createElement('a');
		$a_js->setAttribute('href', 'javascript:'.$this->getNodeAttribute('onclose'));
		$icon = $this->getIcon();
		if($icon) {
			if(!is_array($icon)) {
				$icon = [$icon];
			}
			foreach($icon as $ic) {
				$a_js->appendChild($ic);
			}
		}
		$list_title_opened->appendChild($a_js);
		$separator = $dom->createElement('span', $this->getNodeAttribute('separator', '&nbsp;&gt;&nbsp;'));
		$separator->setAttribute('class', 'editAreaBnSeparator');
		$list_title_opened->appendChild($separator);
		$b_navigation->appendChild($list_title_opened);


		$list_title_closed = $dom->createElement('span');
		$list_title_closed->setAttribute('id', $ID.'_list_title_closed');
		$list_title_closed->setAttribute('class', 'editAreaBnActiveTitle');
		$icon = $this->getIcon();
		if($icon) {
			if(!is_array($icon)) {
				$icon = [$icon];
			}
			foreach($icon as $ic) {
				$list_title_closed->appendChild($ic);
			}
		}
		$b_navigation->appendChild($list_title_closed);

		$item_title = $dom->createElement('span');
		$item_title->setAttribute('id', $ID.'_item_title');
		$item_title->setAttribute('class', 'editAreaBnActiveTitle');
		$b_navigation->appendChild($item_title);

		$child_nodes = [];
		foreach( $this->node->childNodes as $child ) {
			$child_nodes[] = $child;
		}

		foreach( $child_nodes as $child ) {
			$border_container->appendChild($child);
		}

		return $border_container;
	}

}