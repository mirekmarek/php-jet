<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

class ClassCreator_ActualizeDecisionMaker {

	/**
	 * @var callable
	 */
	public $update_class_annotation;


	/**
	 * @var callable|null
	 */
	public $update_constant;

	/**
	 * @var callable|null
	 */
	public $remove_constant;


	/**
	 * @var callable|null
	 */
	public $update_property;

	/**
	 * @var callable|null
	 */
	public $remove_property;



	/**
	 * @var callable|null
	 */
	public $update_method;

	/**
	 * @var callable|null
	 */
	public $remove_method;

}