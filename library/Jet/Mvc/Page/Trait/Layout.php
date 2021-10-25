<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected string $layout_script_name = '';

	/**
	 * @return string
	 */
	public function getLayoutScriptName(): string
	{

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
	public function setLayoutScriptName( string $layout_script_name ): void
	{
		$this->layout_script_name = $layout_script_name;
	}

	/**
	 * @return string
	 */
	public function getLayoutsPath(): string
	{
		return $this->getBase()->getLayoutsPath();
	}

	/**
	 *
	 */
	public function initializeLayout(): void
	{
		Mvc_Layout::setCurrentLayout(
			Factory_Mvc::getLayoutInstance(
				$this->getLayoutsPath(),
				$this->getLayoutScriptName()
			)
		);

	}

}