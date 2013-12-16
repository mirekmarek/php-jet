<?php
/**
 *
 *
 *
 * Default sites handler (@see Mvc_Sites, @see Mvc_Sites_Handler_Abstract)
 *
 * A class can be replaced by another class (@see Factory), but they must expand Mvc_Sites_Handler_Abstract
 *
 * @see Factory
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

class Mvc_Sites_Handler_Default extends Mvc_Sites_Handler_Abstract {

	/**
	 * @var string
	 */
	protected $templates_dir;

	/**
	 *
	 */
	public function  __construct() {
		$this->templates_dir = JET_TEMPLATES_SITES_PATH;
	}

	/**
	 *
	 * @param Mvc_Sites_Site_Abstract $site_data
	 * @param string $template
	 * @param bool $activate (optional, default:true)
	 *
	 *
	 */
	public function createSite( Mvc_Sites_Site_Abstract $site_data, $template, $activate=true  ) {

		IO_Dir::copy($this->templates_dir . $template, $site_data->getBasePath());
		$site_data->setIsActive($activate);
		$site_data->save();

		foreach( $site_data->getLocales() as $locale ) {
			Mvc_Pages::actualizePages( $site_data->getID(), $locale );
		}
		Mvc::truncateRouterCache();

	}
    
	/**
	 * Drop site
	 *
	 * @param Mvc_Sites_Site_ID_Abstract $ID
	 */
	public function dropSite( Mvc_Sites_Site_ID_Abstract $ID ) {
		$site = $this->_getSite($ID);

		foreach( $site->getLocales() as $locale ) {
			Mvc_Pages::dropPages( $ID , $locale );
		}

		IO_Dir::remove( $site->getBasePath() );
		
		$site = $this->_getSite($ID);

		$site->delete();
		Mvc::truncateRouterCache();
	}

	/**
	 * Activate site
	 *
	 * @param Mvc_Sites_Site_ID_Abstract $ID
	 */
	public function activateSite( Mvc_Sites_Site_ID_Abstract  $ID ) {
		$site = $this->_getSite($ID);
		$site->setIsActive(true);
		$site->validateProperties();
		$site->save();
		Mvc::truncateRouterCache();
	}

	/**
	 * Deactivate site
	 *
	 * @param Mvc_Sites_Site_ID_Abstract $ID
	 */
	public function deactivateSite( Mvc_Sites_Site_ID_Abstract  $ID  ) {
		$site = $this->_getSite($ID);
		$site->setIsActive(false);
		$site->validateProperties();
		$site->save();
		Mvc::truncateRouterCache();
	}

}