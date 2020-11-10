<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Reflection;

class DataModels_Parser_Class {

	/**
	 * @var Project_Namespace
	 */
	protected $namespace;

	/**
	 * @var string
	 */
	protected $class_name = '';

	/**
	 * @var string
	 */
	protected $base_class = '';

	/**
	 * @var bool
	 */
	protected $is_abstract = false;

	/**
	 * @var string
	 */
	protected $extends = '';

	/**
	 * @var array
	 */
	protected $implements = [];

	/**
	 * @var DataModels_Parser_Parameter[]
	 */
	protected $class_parameters = [];

	/**
	 * @var DataModels_Parser_Class_Property[]
	 */
	protected $properties = [];

	/**
	 *
	 * @param Project_Namespace $namespace
	 * @param $class_name
	 */
	public function __construct( Project_Namespace $namespace, $class_name )
	{
		$this->namespace = $namespace;
		$this->class_name = $class_name;
	}

	/**
	 * @return string
	 */
	public function getParentClass()
	{
		if(isset($this->class_parameters['parent_model_class_name'])) {
			return $this->class_parameters['parent_model_class_name']->getValue();
		}

		return '';
	}

	/**
	 * @return bool
	 */
	public function isMainDataModel()
	{
		return $this->base_class=='Jet\DataModel';
	}

	/**
	 * @return bool
	 */
	public function isAbstract()
	{
		return $this->is_abstract;
	}

	/**
	 * @param bool $is_abstract
	 */
	public function setIsAbstract( $is_abstract )
	{
		$this->is_abstract = $is_abstract;
	}

	/**
	 * @return string
	 */
	public function getExtends()
	{
		return $this->extends;
	}

	/**
	 * @param string $extends
	 */
	public function setExtends( $extends )
	{
		$this->extends = $extends;
	}

	/**
	 * @return array
	 */
	public function getImplements()
	{
		return $this->implements;
	}

	/**
	 * @param array $implements
	 */
	public function setImplements( $implements )
	{
		$this->implements = $implements;
	}



	/**
	 * @return string
	 */
	public function getBaseClass()
	{
		return $this->base_class;
	}

	/**
	 * @param string $base_class
	 */
	public function setBaseClass( $base_class )
	{
		$this->base_class = $base_class;
	}

	/**
	 * @return Project_Namespace
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}



	/**
	 * @param DataModels_Parser $parser
	 * @param ClassParser_Class $parse_class
	 * @param ClassParser_Class $target_class
	 */
	public function parse( DataModels_Parser $parser, ClassParser_Class $parse_class, ClassParser_Class $target_class )
	{

		if($parse_class->doc_comment) {
			$matches = [];
			preg_match_all( Reflection::getParserRegexp(), $parse_class->doc_comment->text, $matches, PREG_SET_ORDER );

			foreach( $matches as $m ) {
				if($m[1]!='JetDataModel') {
					continue;
				}

				$param = new DataModels_Parser_Class_Parameter( $parser, $parse_class, $m[2], $m[3], $parse_class->name, $parse_class->name!=$target_class->name );

				switch( $param->getName() ) {
					case 'key':
						$value = $param->getValue();

						if(!isset($this->class_parameters[$param->getName()])) {
							$param->setValue([]);
							$this->class_parameters[$param->getName()] = $param;
							$keys = [];
						} else {
							$keys = $this->class_parameters[$param->getName()]->getValue();
						}

						$keys[$value[0]] = [
							'name' => $value[0],
							'type' => $value[1],
							'property_names' => $value[2],
						];

						$this->class_parameters[$param->getName()]->setValue( $keys );

						break;
					case 'relation':
						$value = $param->getValue();

						if(!isset($this->class_parameters[$param->getName()])) {
							$param->setValue([]);
							$this->class_parameters[$param->getName()] = $param;
							$relations = [];
						} else {
							$relations = $this->class_parameters[$param->getName()]->getValue();
						}

						if(!isset($value[3])) {
							$value[3] = [];;
						}


						$relations[$value[0]] = [
							'related_to_class_name' => $parse_class->parser->getFullClassName($value[0]),
							'join_by_properties'    => $value[1],
							'join_type'             => $value[2],
							'required_relations'    => $value[3]
						];

						$this->class_parameters[$param->getName()]->setValue( $relations );

						break;

					default:
						$this->class_parameters[$param->getName()] = $param;
						break;
				}
			}
		}

		foreach( $parse_class->properties as $property) {
			if(!$property->doc_comment) {
				continue;
			}

			$property_name = $property->name;

			$matches = [];
			preg_match_all( Reflection::getParserRegexp(), $property->doc_comment->text, $matches, PREG_SET_ORDER );

			foreach( $matches as $m ) {
				if($m[1]!='JetDataModel') {
					continue;
				}

				if(!isset($this->properties[$property_name])) {
					$p = new DataModels_Parser_Class_Property( $property_name, $parse_class->name );
					if($parse_class->name!=$target_class->name) {
						$p->setIsInherited( true );
						$p->setInheritedClassName( $parse_class->name );
					}

					$this->properties[$property_name] = $p;
				} else {
					$p = $this->properties[$property_name];

					if($p->getDeclaredInClass()!=$parse_class->name) {
						$p->setIsInherited( true );
						$p->setInheritedClassName( $p->getDeclaredInClass() );
						$p->setDeclaredInClass( $parse_class->name );
						$p->setOverload( true );
					}
				}


				$param = new DataModels_Parser_Class_Property_Parameter( $parser, $parse_class, $m[2], $m[3], $parse_class->name, $parse_class->name==$target_class->name );

				$p->setParameter( $param->getName(), $param );
			}

		}
	}



	/**
	 * @return string
	 */
	public function getFullClassName()
	{
		return $this->class_name;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return substr( $this->getFullClassName(), strlen($this->getNamespace()->getNamespace())+1 );
	}

	/**
	 * @return DataModels_Parser_Parameter[]
	 */
	public function getClassParameters()
	{
		return $this->class_parameters;
	}

	/**
	 * @return DataModels_Parser_Class_Property[]
	 */
	public function getProperties()
	{
		return $this->properties;
	}



}
