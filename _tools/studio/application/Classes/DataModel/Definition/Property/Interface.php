<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Form;
use Jet\Form_Field;

/**
 *
 */
interface DataModel_Definition_Property_Interface
{

	/**
	 * @var DataModel_Class $_class
	 */
	public function setClass( DataModel_Class $_class ): void;

	/**
	 * @return string
	 */
	public function getDeclaringClassName(): string;

	/**
	 * @return bool
	 */
	public function isInherited(): bool;

	/**
	 * @return bool
	 */
	public function isOverload(): bool;

	/**
	 * @return string
	 */
	public function getHeadCssClass(): string;

	/**
	 * @return string
	 */
	public function getTypeDescription(): string;

	/**
	 * @return string
	 */
	public function getIcons(): string;


	/**
	 * @return Form
	 */
	public function getEditForm(): Form;

	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( array &$fields ): void;

	/**
	 * @return bool|DataModel_Definition_Property_Interface
	 */
	public function catchEditForm(): bool|DataModel_Definition_Property_Interface;
	
	/**
	 * @return string
	 */
	public function getType(): string;

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void;

	/**
	 * @return null|string
	 */
	public function getRelatedToClassName(): null|string;

	/**
	 * @param null|string $related_to_class_name
	 */
	public function setRelatedToClassName( ?string $related_to_class_name ): void;

	/**
	 * @return null|string
	 */
	public function getRelatedToPropertyName(): null|string;

	/**
	 * @param null|string $related_to_property_name
	 */
	public function setRelatedToPropertyName( ?string $related_to_property_name ): void;

	/**
	 * @param string $database_column_name
	 */
	public function setDatabaseColumnName( string $database_column_name ): void;

	/**
	 * @return bool
	 */
	public function getIsId(): bool;

	/**
	 * @param bool $is_id
	 */
	public function setIsId( bool $is_id ): void;

	/**
	 * @return bool
	 */
	public function getIsKey(): bool;

	/**
	 * @param bool $is_key
	 */
	public function setIsKey( bool $is_key ): void;

	/**
	 * @return bool
	 */
	public function getIsUnique(): bool;

	/**
	 * @param bool $is_unique
	 */
	public function setIsUnique( bool $is_unique ): void;


	/**
	 * @return bool
	 */
	public function isDoNotExport(): bool;

	/**
	 * @param bool $do_not_export
	 */
	public function setDoNotExport( bool $do_not_export ): void;

	/**
	 * @return mixed
	 */
	public function getDefaultValue(): mixed;


	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class ): ClassCreator_Class_Property;

	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return array
	 */
	public function createClassMethods( ClassCreator_Class $class ): array;

	/**
	 *
	 */
	public function prepare(): void;

	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function update( DataModel_Class $class ): bool;


	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function add( DataModel_Class $class ): bool;

}