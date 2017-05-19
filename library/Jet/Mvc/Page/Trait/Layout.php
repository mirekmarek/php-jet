<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Layout
{

	/**
	 *
	 * @var string
	 */
	protected $custom_layouts_path = '';

	/**
	 *
	 * @var string
	 */
	protected $layout_script_name = '';

	/**
	 * @return string
	 */
	public function getCustomLayoutsPath()
	{
		/**
		 * @var Mvc_Page_Trait_Layout|Mvc_Page $this
		 */

		if(
			!$this->custom_layouts_path &&
			$this->getParent()
		) {
			return $this->getParent()->getCustomLayoutsPath();
		}

		return $this->custom_layouts_path;
	}

	/**
	 * @param string $layouts_dir
	 */
	public function setCustomLayoutsPath( $layouts_dir )
	{
		$this->custom_layouts_path = $layouts_dir;
	}

	/**
	 * @return string
	 */
	public function getLayoutScriptName()
	{
		/**
		 * @var Mvc_Page_Trait_Layout|Mvc_Page $this
		 */

		if(
			!$this->layout_script_name &&
			$this->getParent()
		) {
			return $this->getParent()->getLayoutScriptName();
		}

		return $this->layout_script_name;
	}

	/**
	 * @param string $layout_script_name
	 */
	public function setLayoutScriptName( $layout_script_name )
	{
		$this->layout_script_name = $layout_script_name;
	}


	/**
	 * @throws Exception
	 *
	 */
	public function initializeLayout()
	{
		/**
		 * @var Mvc_Page_Trait_Layout|Mvc_Page $this
		 */
		if( Mvc_Layout::getCurrentLayout() ) {
			return;
		}

		Mvc_Layout::setCurrentLayout(
			Mvc_Factory::getLayoutInstance( $this->getLayoutsPath(), $this->getLayoutScriptName() )
		);

	}

	/**
	 * @return string
	 */
	public function getLayoutsPath()
	{
		/**
		 * @var Mvc_Page_Trait_Layout|Mvc_Page $this
		 */
		if( $this->getCustomLayoutsPath() ) {
			return $this->getCustomLayoutsPath();
		}

		return $this->getSite()->getLayoutsPath();
	}

}