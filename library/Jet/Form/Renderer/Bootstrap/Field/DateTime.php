<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Bootstrap_Field_DateTime
 * @package Jet
 */
class Form_Renderer_Bootstrap_Field_DateTime extends Form_Renderer_Bootstrap_Field_Input
{

	/**
	 * @var string
	 */
	protected $_input_type = 'datetime-local';

	/**
	 * @param array &$tag_options
	 */
	protected function initTagOptions( array &$tag_options )
	{
		/**
		 * @var Form_Field_Checkbox $fl
		 */
		$fl = $this->_field;

		$value = '';
		if( $fl->getValue() ) {
			$date = new \DateTime( $fl->getValue() );

			if( $date ) {
				$value = $date->format( 'Y-m-d\TH:i' );
			}
		}

		$tag_options['value'] = $value;
	}

}