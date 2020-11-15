<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Model_Related_1to1 as Jet_DataModel_Definition_Model_Related_1to1;
use Jet\Tr;

/**
 */
class DataModel_Definition_Model_Related_1to1 extends Jet_DataModel_Definition_Model_Related_1to1 implements DataModel_Definition_Model_Related_Interface{

	use DataModel_Definition_Model_Related_Trait;

	/**
	 * @var string
	 */
	protected $internal_type = DataModels::MODEL_TYPE_RELATED_1TO1;


	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass()
	{

		$class = new ClassCreator_Class();

		$class->setName( $this->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_1to1') );

		$class->setExtends( $this->createClass_getExtends($class, 'DataModel_Related_1to1') );

		if($this->_implements) {
			foreach( $this->_implements as $i ) {
				$use = ClassCreator_UseClass::createByClassName($i);
				$class->addUse( $use );

				$class->addImplements( $use->getClass() );
			}
		}

		return $class;
	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_main( ClassCreator_Class $class )
	{

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'name', var_export($this->getModelName(), true)) )
		);

		if($this->getDatabaseTableName()) {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'database_table_name', var_export($this->getDatabaseTableName(), true)) )
			);
		} else {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'database_table_name', var_export($this->getModelName(), true)) )
			);
		}


		$parent_class = $this->parent_model_class_name;
		if(!$parent_class) {
			$parent_class = $this->main_model_class_name;
		}

		$parent_class = DataModels::getClass( $parent_class );
		if(!$parent_class) {
			$class->addError( Tr::_('Fatal: unknown parent class!') );

			return;
		}

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'parent_model_class_name', var_export($parent_class->getClassName(), true) ))
		);

	}

}