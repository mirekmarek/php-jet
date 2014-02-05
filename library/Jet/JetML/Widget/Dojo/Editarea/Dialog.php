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

class JetML_Widget_Dojo_Editarea_Dialog extends JetML_Widget_Dojo_Abstract {

	/**
	 *
	 * @var string
	 */
	protected $dojo_type = 'dijit.layout.BorderContainer';

	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {
		$prefix = $this->parser->getLayout()->getUIContainerIDPrefix();
		$ID = $prefix.$this->node->getAttribute('area_id').'_dialog';

		$this->node->setAttribute('id', $ID);
		$this->node->setAttribute('region', 'leading');
		$this->node->setAttribute('gutters', 'false');
		$this->node->setAttribute('style', 'display:none');

		return parent::getReplacement();
	}
}