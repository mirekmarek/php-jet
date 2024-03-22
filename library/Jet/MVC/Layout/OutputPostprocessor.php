<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace Jet;

abstract class MVC_Layout_OutputPostprocessor {
	
	protected MVC_Layout $layout;
	protected string $output;
	
	/**
	 * @param MVC_Layout $layout
	 */
	public function __construct( MVC_Layout $layout )
	{
		$this->layout = $layout;
	}
	
	abstract public function getId() : string;
	
	/**
	 * @param string $output
	 * @return string
	 */
	abstract public function process( string $output ) : string;
}