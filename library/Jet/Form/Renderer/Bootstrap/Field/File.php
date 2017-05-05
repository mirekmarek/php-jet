<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Bootstrap_Field_File
 * @package Jet
 */
class Form_Renderer_Bootstrap_Field_File extends Form_Renderer_Bootstrap_Field_Input
{

	/**
	 * @var string
	 */
	protected $_input_type = 'file';

	/**
	 * @param array &$tag_options
	 */
	protected function initTagOptions( array &$tag_options )
	{
		/**
		 * @var Form_Field_File $fl
		 */
		$fl = $this->_field;

		if( $fl->getAllowedMimeTypes() ) {
			$tag_options['accept'] = implode( ',', $fl->getAllowedMimeTypes() );
		}

	}

}