<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Bootstrap_Field_Int
 * @package Jet
 */
class Form_Renderer_Bootstrap_Field_Int extends Form_Renderer_Bootstrap_Field_Input
{

	/**
	 * @var string
	 */
	protected $_input_type = 'number';


	/**
	 * @param array &$tag_options
	 */
	protected function initTagOptions( array &$tag_options )
	{
		/**
		 * @var Form_Field_Int $fl
		 */
		$fl = $this->_field;

		if( $fl->getMinValue()!==null ) {
			$tag_options['min'] = $fl->getMinValue();
		}
		if( $fl->getMaxValue()!==null ) {
			$tag_options['max'] = $fl->getMaxValue();
		}
		if( $fl->getStep()!==null ) {
			$tag_options['step'] = $fl->getStep();
		}

	}


}