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

class JetML_Widget_Dojo_Trash_Button extends JetML_Widget_Dojo_Abstract {
	/**
	 * @var string
	 */
	protected $dojo_type = 'dijit.form.Button';

	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = 'button';

	/**
	 *
	 * @var array
	 */
	protected $internal_properties = ['icon', 'icon_size', 'flag', 'flag_size', 'dojotype', 'trash_id'];

	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {

		$prefix = $this->parser->getLayout()->getUIContainerIDPrefix();
		$ID = $prefix.$this->node->getAttribute('trash_id').'_button';

		$this->node->setAttribute('id', $ID);
		$this->node->setAttribute('icon', 'trash' );

		if(!$this->node->hasAttribute('title')) {
			$this->node->setAttribute('title', 'Delete' );
		}

		return parent::getReplacement();
	}


	/**
	 * @return \DOMElement|\DOMElement[]
	 */
	protected function _getTagContent() {
		return $this->getIcon();
	}

}