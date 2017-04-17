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

abstract class Form_Renderer_Bootstrap_Field_Abstract extends Form_Renderer_Abstract_Field_Abstract {

	/**
	 * @var string
	 */
	protected $base_css_class = 'form-control';

	/**
	 * @var bool
	 */
	protected $container_disabled = false;

	/**
	 * @return $this
	 */
	public function noContainer() {
		$this->container_disabled = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		if($this->container_disabled) {
			return parent::render();
		}

		return $this->render_containerStart().parent::render().$this->render_containerEnd();
	}

	/**
	 * @return string
	 */
	protected function render_containerStart() {
		return '<div class="col-'.$this->getSize().'-'.$this->getWidth().'">';
	}

	/**
	 * @return string
	 */
	protected function render_containerEnd() {
		return '</div>';
	}

}