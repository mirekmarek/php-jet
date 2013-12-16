<?php
/**
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
 * @abstract
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Section
 */
namespace Jet;

abstract class Config_Section extends Config {
	/**
	 * @var array
	 */
	protected $_data;

	/**
	 * @param Config $configuration
	 * @param array $data
	 */
	public function __construct( Config $configuration, array $data ) {
		$this->config_file_path = $configuration->getConfigFilePath();
		$this->soft_mode = $configuration->getSoftMode();
		$this->_data = $data;
		$data = new Data_Array($this->_data);
		$this->setData( $data );
	}

}