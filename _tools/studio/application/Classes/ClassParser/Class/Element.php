<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

/**
 *
 */
abstract class ClassParser_Class_Element extends ClassParser_Element {

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
