<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Property;

use Jet\Form_Field;

class DataModels_Property_RelatedTmp extends DataModel_Definition_Property implements DataModels_Property_Interface {

	use DataModels_Property_Trait;

	/**
	 * @var string
	 */
	protected $related_to;

	/**
	 * @return string
	 */
	public function getRelatedTo()
	{
		return $this->related_to;
	}

	/**
	 * @param string $related_to
	 */
	public function setRelatedTo( $related_to )
	{
		$this->related_to = $related_to;
	}



	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( &$fields )
	{
	}

	/**
	 *
	 */
	public function showEditFormFields()
	{
	}

	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class )
	{
		return null;
	}

	/**
	 * @param ClassCreator_Class $class
	 *
	 */
	public function createClassMethods( ClassCreator_Class $class )
	{
	}

}