<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Form;

/**
 */
interface DataModel_Definition_Model_Interface
{
	/**
	 * @return bool
	 */
	public function isAbstract(): bool;

	/**
	 * @return bool
	 */
	public function canHaveRelated(): bool;

	/**
	 * @return ClassCreator_Class|null
	 */
	public function createClass(): ClassCreator_Class|null;

	/**
	 * @param ClassCreator_Class $class
	 * @param string $default
	 *
	 * @return string
	 */
	public function createClass_getExtends( ClassCreator_Class $class, string $default ): string;


	/**
	 *
	 * @param string $option
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	public function getIDControllerOption( string $option, mixed $default_value ): mixed;

	/**
	 *
	 * @param string $option
	 * @param mixed $value
	 */
	public function setIDControllerOption( string $option, mixed $value ): void;

	/**
	 * @return DataModel_Definition_Relation_External[]
	 */
	public function getExternalRelations(): array;


	/**
	 * @return DataModel_Definition_Property_Interface[]|\Jet\DataModel_Definition_Property[]
	 */
	public function getProperties(): array;


	/**
	 * @return string
	 */
	public function getInternalType(): string;


	/**
	 * @return string
	 */
	public function getModelName(): string;

	/**
	 * @param string $model_name
	 */
	public function setModelName( string $model_name ): void;

	/**
	 * @return string
	 */
	public function getDatabaseTableName(): string;

	/**
	 * @param string $database_table_name
	 */
	public function setDatabaseTableName( string $database_table_name ): void;

	/**
	 * @return string
	 */
	public function getClassName(): string;

	/**
	 * @return string
	 */
	public function getExtends(): string;

	/**
	 * @return array
	 */
	public function getImplements(): array;

	/**
	 * @return array
	 */
	public function getExtendsScope(): array;

	/**
	 * @return Form
	 */
	public function getEditForm(): Form;

	/**
	 * @return bool
	 */
	public function catchEditForm(): bool;


	/**
	 * @param DataModel_Definition_Property_Interface $property
	 */
	public function addProperty( DataModel_Definition_Property_Interface $property ): void;


	/**
	 * @param DataModel_Definition_Key $key
	 */
	public function addCustomNewKey( DataModel_Definition_Key $key ): void;

	/**
	 * @param string $key_name
	 *
	 * @return DataModel_Definition_Key|null
	 */
	public function getCustomKey( string $key_name ): DataModel_Definition_Key|null;

	/**
	 * @return DataModel_Definition_Key[]
	 */
	public function getCustomKeys(): array;

	/**
	 * @param string $key_name
	 */
	public function deleteCustomKey( string $key_name ): void;


	/**
	 * @param DataModel_Definition_Relation_External $relation
	 */
	public function addExternalRelation( DataModel_Definition_Relation_External $relation ): void;

	/**
	 * @param string $relation_id
	 *
	 * @return DataModel_Definition_Relation_External|null
	 */
	public function getExternalRelation( string $relation_id ): DataModel_Definition_Relation_External|null;


	/**
	 * @param string $relation_id
	 */
	public function deleteExternalRelation( string $relation_id ): void;

	/**
	 * @return bool
	 */
	public function save(): bool;

	/**
	 * @return bool
	 */
	public function create(): bool;

}