<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

/**
 *
 */
abstract class ClassParser_Class_Element extends ClassParser_Element
{

	/**
	 * @var ?ClassParser_Class
	 */
	protected ?ClassParser_Class $__class = null;

	/**
	 *
	 * @param ClassParser $parser
	 * @param ClassParser_Class $class
	 */
	public function __construct( ClassParser $parser, ClassParser_Class $class )
	{
		parent::__construct( $parser );

		$this->__class = $class;
	}


}
