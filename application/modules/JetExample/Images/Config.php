<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\JetExample\Images
 */
namespace JetApplicationModule\JetExample\Images;
use Jet;

/**
 *
 */
class Config extends Jet\Config {
	/**
	 * @JetConfig:type = Jet\Config::TYPE_INT
	 * @JetConfig:default_value = 100
	 * @JetConfig:is_required = false
	 *
	 * @var int
	 */
	protected $default_thb_max_w;

	/**
	 * @JetConfig:type = Jet\Config::TYPE_INT
	 * @JetConfig:default_value = 100
	 * @JetConfig:is_required = false
	 *
	 * @var int
	 */
	protected $default_thb_max_h;

	/**
	 * @return int
	 */
	public function getDefaultThbMaxH() {
		return $this->default_thb_max_h;
	}

	/**
	 * @return int
	 */
	public function getDefaultThbMaxW() {
		return $this->default_thb_max_w;
	}


}