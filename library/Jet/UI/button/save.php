<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class UI_button_save extends UI_button
{

	/**
	 * @var string
	 */
	protected string $type = UI_button::TYPE_SUBMIT;
	
	public function __construct( string $label )
	{
		parent::__construct( $label );
		$this->view_script = SysConf_Jet_UI_DefaultViews::get('button/save' );
	}
	
}