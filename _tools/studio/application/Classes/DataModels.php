<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\Form_Field_Input;
use Jet\Http_Request;
use Jet\SysConf_URI;
use Jet\Tr;
use Jet\DataModel_IDController_AutoIncrement;
use Jet\DataModel_IDController_UniqueString;
use Jet\DataModel_IDController_Name;
use Jet\DataModel_IDController_Passive;

/**
 *
 */
class DataModels extends BaseObject implements Application_Part
{

	/**
	 * @var array
	 */
	protected static array $id_controllers = [
		DataModel_IDController_AutoIncrement::class => 'AutoIncrement',
		DataModel_IDController_UniqueString::class  => 'UniqueString',
		DataModel_IDController_Name::class          => 'Name',
		DataModel_IDController_Passive::class       => 'Passive',
	];

	const MODEL_TYPE_MAIN = 'Main';

	const MODEL_TYPE_RELATED_1TON = 'Related_1toN';
	const MODEL_TYPE_RELATED_1TO1 = 'Related_1to1';
	const MODEL_TYPE_RELATED_MTON = 'Related_MtoN';


	/**
	 * @var array
	 */
	protected static array $types = [
		self::MODEL_TYPE_RELATED_1TON => 'Related DataModel 1toN',
		self::MODEL_TYPE_RELATED_1TO1 => 'Related DataModel 1to1',
		self::MODEL_TYPE_RELATED_MTON => 'Related DataModel MtoN',

	];


	/**
	 * @var DataModel_Class|null|bool
	 */
	protected static DataModel_Class|null|bool $current_class = null;

	/**
	 * @var DataModel_Definition_Property_Interface|\Jet\DataModel_Definition_Property|null|bool
	 */
	protected static DataModel_Definition_Property_Interface|\Jet\DataModel_Definition_Property|null|bool $current_property = null;

	/**
	 * @var DataModel_Definition_Key|null|bool
	 */
	protected static DataModel_Definition_Key|null|bool $current_key = null;

	/**
	 * @var DataModel_Definition_Relation_External|null|bool
	 */
	protected static DataModel_Definition_Relation_External|null|bool $current_relation = null;


	/**
	 * @var DataModel_Class[]
	 */
	protected static ?array $classes = null;

	/**
	 * @var DataModel_Namespace[]
	 */
	protected static ?array $namespaces = null;


	/**
	 * @return array
	 */
	public static function load_getDirs(): array
	{
		$dirs = [
			ProjectConf_Path::getApplicationClasses(),
			ProjectConf_Path::getApplicationModules()
		];


		return $dirs;
	}


	/**
	 * @param bool $reload
	 * @return DataModel_Class[]
	 */
	public static function load( bool $reload = false ): array
	{
		if( $reload ) {
			static::$classes = null;
		}

		if( static::$classes === null ) {
			static::$classes = [];

			$finder = new DataModels_ClassFinder(
				static::load_getDirs()
			);

			$finder->find();

			static::$classes = $finder->getClasses();
		}

		return static::$classes;
	}

	/**
	 * @return DataModel_Class[]
	 */
	public static function getProblematicClasses(): array
	{
		static::load();

		$problems = [];

		foreach( static::$classes as $class ) {
			if( $class->getError() ) {
				$problems[] = $class;
			}
		}

		return $problems;
	}

	/**
	 * @return DataModel_Namespace[]
	 */
	public static function getNamespaces(): array
	{

		if( static::$namespaces === null ) {
			static::$namespaces = [];
			$app_ns = new DataModel_Namespace(
				Project::getApplicationNamespace(),
				ProjectConf_Path::getApplicationClasses()
			);

			static::$namespaces[$app_ns->getNamespace()] = $app_ns;

			foreach( Modules::getModules() as $module ) {
				$ns = new DataModel_Namespace(
					rtrim( $module->getNamespace(), '\\' ),
					$module->getModuleDir()
				);

				static::$namespaces[$ns->getNamespace()] = $ns;
			}
		}

		return static::$namespaces;
	}


	/**
	 * @return DataModel_Class[]
	 */
	public static function getClasses(): array
	{
		return static::load();
	}


	/**
	 * @param string $action
	 *
	 * @return string
	 */
	public static function getActionUrl( string $action ): string
	{

		$get_params = [];

		if( static::getCurrentClassName() ) {
			$get_params['class'] = static::getCurrentClassName();
		}

		if( static::getCurrentPropertyName() ) {
			$get_params['property'] = static::getCurrentPropertyName();
		}

		if( static::getCurrentKeyName() ) {
			$get_params['key'] = static::getCurrentKeyName();
		}

		if( static::getCurrentRelationName() ) {
			$get_params['relation'] = static::getCurrentRelationName();
		}

		$get_params['action'] = $action;

		return SysConf_URI::getBase() . 'data_model.php?' . http_build_query( $get_params );
	}


	/**
	 * @param string $name
	 *
	 * @return DataModel_Class|null
	 */
	public static function getClass( string $name ): DataModel_Class|null
	{
		static::load();

		if( !isset( static::$classes[$name] ) ) {
			return null;
		}

		return static::$classes[$name];
	}

	/**
	 * @return DataModel_Class|null|bool
	 */
	public static function getCurrentClass(): DataModel_Class|null|bool
	{
		if( static::$current_class === null ) {
			$id = Http_Request::GET()->getString( 'class' );

			static::$current_class = false;

			if(
				$id &&
				($item = static::getClass( $id ))
			) {
				static::$current_class = $item;
			}
		}

		return static::$current_class;
	}

	/**
	 * @return string|bool
	 */
	public static function getCurrentClassName() : string|bool
	{
		if( static::getCurrentClass() ) {
			return static::getCurrentClass()->getFullClassName();
		}

		return false;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN|null
	 */
	public static function getCurrentModel() : DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN|null
	{
		$class = static::getCurrentClass();
		if( !$class ) {
			return null;
		}

		return $class->getDefinition();
	}

	/**
	 * @return DataModel_Definition_Property_Interface|DataModel_Definition_Property|null
	 */
	public static function getCurrentProperty() : DataModel_Definition_Property_Interface|DataModel_Definition_Property|null
	{
		if( static::$current_property === null ) {
			static::$current_property = false;

			if( ($model = static::getCurrentModel()) ) {
				$name = Http_Request::GET()->getString( 'property' );

				if(
					$name &&
					$model->hasProperty( $name ) &&
					($item = $model->getProperty( $name ))
				) {
					static::$current_property = $item;
				}

			}
		}

		return static::$current_property;
	}

	/**
	 * @return string|null
	 */
	public static function getCurrentPropertyName(): string|null
	{
		$current = static::getCurrentProperty();

		if( !$current ) {
			return null;
		}

		return $current->getName();
	}


	/**
	 * @return DataModel_Definition_Key|bool
	 */
	public static function getCurrentKey(): DataModel_Definition_Key|bool
	{
		if( static::$current_key === null ) {
			static::$current_key = false;

			if( ($model = static::getCurrentModel()) ) {
				$name = Http_Request::GET()->getString( 'key' );

				if(
					$name &&
					($item = $model->getCustomKey( $name ))
				) {
					static::$current_key = $item;
				}

			}
		}

		return static::$current_key;
	}

	/**
	 * @return string|null
	 */
	public static function getCurrentKeyName(): string|null
	{
		$current = static::getCurrentKey();

		if( !$current ) {
			return null;
		}

		return $current->getName();
	}


	/**
	 * @return string|null
	 */
	public static function getCurrentRelationName(): string|null
	{
		$current = static::getCurrentRelation();

		if( !$current ) {
			return null;
		}

		return $current->getName();
	}


	/**
	 * @return DataModel_Definition_Relation_External|bool
	 */
	public static function getCurrentRelation(): DataModel_Definition_Relation_External|bool
	{
		if( static::$current_relation === null ) {
			static::$current_relation = false;

			if( ($model = static::getCurrentModel()) ) {
				$id = Http_Request::GET()->getString( 'relation' );

				if(
					$id &&
					($item = $model->getExternalRelation( $id ))
				) {
					static::$current_relation = $item;
				}

			}
		}

		return static::$current_relation;
	}


	/**
	 * @return string|null
	 */
	public static function getCurrentWhatToEdit(): string|null
	{
		if( !static::getCurrentModel() ) {
			return null;
		}
		if( static::getCurrentKey() ):
			return 'key';
		elseif( static::getCurrentRelation() ):
			return 'relation';
		elseif( static::getCurrentProperty() ):
			return 'property';
		else:
			return 'model';
		endif;
	}

	/**
	 * @return array
	 */
	public static function getIDControllers(): array
	{
		$id_controllers = [];

		foreach( static::$id_controllers as $class => $label ) {
			$id_controllers[$class] = Tr::_( $label );
		}

		return $id_controllers;

	}

	/**
	 * @return array
	 */
	public static function getDataModelTypes(): array
	{
		$types = [];

		foreach( static::$types as $type => $label ) {
			$types[$type] = Tr::_( $label );
		}

		return $types;
	}

	/**
	 * @param Form_Field_Input $field
	 * @param DataModel_Definition_Model_Interface|null $model
	 *
	 * @return bool
	 */
	public static function checkModelName( Form_Field_Input $field, ?DataModel_Definition_Model_Interface $model = null ): bool
	{
		$name = $field->getValue();

		if( !$name ) {
			$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
			return false;
		}

		if( !preg_match( '/^[a-z0-9_]{2,}$/i', $name ) ) {
			$field->setError( Form_Field_Input::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		return true;

	}


	/**
	 * @param Form_Field_Input $field
	 * @param DataModel_Definition_Model_Interface|null $model
	 *
	 * @return bool
	 */
	public static function checkClassName( Form_Field_Input $field, ?DataModel_Definition_Model_Interface $model = null ): bool
	{
		$name = $field->getValue();

		if( !$name ) {
			$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
			return false;
		}

		if(
			!preg_match( '/^[a-z0-9_]{2,}$/i', $name ) ||
			str_contains( $name, '__' )
		) {
			$field->setError( Form_Field_Input::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		foreach( DataModels::getClasses() as $class ) {

			if( $class->getFullClassName() == $name ) {
				$field->setCustomError(
					Tr::_( 'DataModel with the same class name already exists' ),
					'data_model_class_is_not_unique'
				);

				return false;
			}
		}

		return true;

	}


	/**
	 * @param Form_Field_Input $field
	 * @param DataModel_Definition_Model_Interface|null $model
	 *
	 * @return bool
	 */
	public static function checkTableName( Form_Field_Input $field, ?DataModel_Definition_Model_Interface $model = null ): bool
	{
		$name = $field->getValue();

		if( !$name ) {
			return true;
		}


		if(
			!preg_match( '/^[a-z0-9_]{2,}$/i', $name ) ||
			str_contains( $name, '__' )
		) {
			$field->setError( Form_Field_Input::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		$exists = false;

		if( $model ) {
			foreach( DataModels::getClasses() as $class ) {
				$m = $class->getDefinition();

				if(
					$class->getFullClassName() != $model->getClassName() &&
					(
						$m->getDatabaseTableName() == $name ||
						$m->getModelName() == $name
					)
				) {
					$exists = true;
					break;
				}
			}
		} else {
			foreach( DataModels::getClasses() as $class ) {
				$m = $class->getDefinition();

				if(
					$m->getDatabaseTableName() == $name ||
					$m->getModelName() == $name
				) {
					$exists = true;
					break;
				}
			}

		}

		if( $exists ) {
			$field->setCustomError(
				Tr::_( 'DataModel with the same table name already exists' ),
				'data_model_table_is_not_unique'
			);

			return false;
		}

		return true;

	}


	/**
	 * @param string $namespace
	 * @param string $class_name
	 *
	 * @return string
	 */
	public static function generateScriptPath( string $namespace, string $class_name ): string
	{
		if( !isset( static::getNamespaces()[$namespace] ) ) {
			return '';
		}

		$namespace = static::getNamespaces()[$namespace];

		$class_name = str_replace( '__', '_', $class_name );
		$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );
		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );

		return $namespace->getRootDir() . $class_name . '.php';
	}


	/**
	 * @param DataModel_Definition_Model_Interface $model
	 */
	public static function addModel( DataModel_Definition_Model_Interface $model ): void
	{
		static::load();

		static::$classes[$model->getClassName()] = $model;
	}

}