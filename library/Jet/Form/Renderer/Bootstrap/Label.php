<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Renderer_Bootstrap_Label extends Form_Renderer_Abstract_Label {

	/**
	 * @var string
	 */
	protected $base_css_class;

	public function getBaseCssClass()
	{
		if($this->base_css_class) {
			return $this->base_css_class;
		}

		return 'col-'.$this->getSize().'-'.$this->getWidth().' control-label';
	}

}