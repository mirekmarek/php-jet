<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package JetML
 */
namespace Jet;

class JetML_Widget_Dojo_Form_Button_Busy extends JetML_Widget_Dojo_Form_Button {

	/**
	 *
	 * @var string
	 */
	protected $dojo_type = "dojox.form.BusyButton";


	protected function _formatDojoProps( $dojo_props ) {
		if(isset($dojo_props["busylabel"])) {
			$dojo_props["busyLabel"] = $dojo_props["busylabel"];
			unset( $dojo_props["busylabel"] );
		}

		return parent::_formatDojoProps($dojo_props);
	}
}