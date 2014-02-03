<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

/**
 * Class Mvc_Pages_Page_URL_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Mvc_Pages_URLs'
 * @JetDataModel:parent_model_class_name = 'Jet\\Mvc_Pages_Page_Default'
 */
class Mvc_Pages_Page_URL_Default extends Mvc_Pages_Page_URL_Abstract {

	/**
	 * @JetDataModel:related_to = 'main.site_ID'
	 */
	protected $site_ID;

	/**
	 * @JetDataModel:related_to = 'main.ID'
	 */
	protected $page_ID;

	/**
	 * @JetDataModel:related_to = 'main.locale'
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:is_key = true
	 *
	 * @var string
	 */
	protected $URL = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 *
	 * @var bool
	 */
	protected $is_default = false;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 *
	 * @var bool
	 */
	protected $is_SSL = false;


	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function  toString() {
		return $this->URL;
	}

	/**
	 * @return string
	 */
	public function getURL() {
		return $this->URL;
	}

	/**
	 * @param string $URL
	 */
	public function setURL($URL) {
		$this->URL = $URL;
	}

	/**
	 * @return bool
	 */
	public function getIsDefault() {
		return $this->is_default;
	}

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault($is_default) {
		$this->is_default = (bool)$is_default;
	}

	/**
	 * @return bool
	 */
	public function getIsSSL() {
		return $this->is_SSL;
	}

	/**
	 * @param bool $is_SSL
	 */
	public function setIsSSL($is_SSL) {
		$this->is_SSL = (bool)$is_SSL;
	}

}