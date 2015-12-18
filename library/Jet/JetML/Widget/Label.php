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

class JetML_Widget_Label extends JetML_Widget_Dojo_Abstract {
	/**
	 *
	 * @var string|array|bool $dojo_type
	 */
	protected $dojo_type = false;

	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = 'label';

	/**
	 * @return \DOMElement|void
	 */
	public function getReplacement() {

		$for = $this->node->getAttribute('for');

		$attributes = $this->getNodeAttributes();
		if($for) {
			$for = $this->parser->getLayout()->getUIContainerIDPrefix().$for;

			$attributes['for'] = $for;
		}

		return $this->createNode('label', true, $attributes);
	}

}