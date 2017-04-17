<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Renderer_Bootstrap_Container extends Form_Renderer_Abstract_Container  {

	/**
	 * @var string
	 */
	protected $base_css_class;

    /**
     * @return string
     */
    public function getBaseCssClass()
	{
		if($this->base_css_class) {
			$class = $this->base_css_class;
		} else {
			$class = 'form-group row';
		}

		if($this->_field->getLastError()) {
			$class .= ' has-error has-feedback';
		}

		return $class;
	}
}