<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;

/**
 *
 */
class ClassCreator_Class_Property extends BaseObject
{

	/**
	 * @var string
	 */
	protected string $visibility = ClassCreator_Class::VISIBILITY_PROTECTED;

	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var string
	 */
	protected string $type = '';

	/**
	 * @var mixed
	 */
	protected mixed $default_value = null;

	/**
	 * @var ClassCreator_Annotation[]
	 */
	protected array $annotations = [];


	/**
	 * @param string $name
	 * @param string $type
	 */
	public function __construct( string $name, string $type )
	{
		$this->name = $name;
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getVisibility() : string
	{
		return $this->visibility;
	}

	/**
	 * @param string $visibility
	 */
	public function setVisibility(string $visibility) : void
	{
		$this->visibility = $visibility;
	}

	/**
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) : void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getType() : string
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType( string $type ) : void
	{
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue() : mixed
	{
		return $this->default_value;
	}

	/**
	 * @param mixed $default_value
	 */
	public function setDefaultValue( mixed $default_value ) : void
	{
		$this->default_value = $default_value;
	}

	/**
	 * @param ClassCreator_Annotation $annotation
	 */
	public function addAnnotation( ClassCreator_Annotation $annotation ) : void
	{
		$this->annotations[] = $annotation;
	}

	/**
	 * @return string
	 */
	public function toString() : string
	{
		$res = '';

		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();

		$res .= $ident.'/**'.$nl;
		$res .= $ident.' * '.$nl;
		foreach( $this->annotations as $annotation) {
			$res .= $ident.' * '.$annotation.$nl;
		}
		$res .= $ident.' * '.$nl;
		$res .= $ident.' * @var '.$this->type.$nl;
		$res .= $ident.' * '.$nl;
		$res .= $ident.' */ '.$nl;
		if($this->default_value!==null) {
			$res .= $ident.$this->visibility.' $'.$this->name.' = '.var_export( $this->default_value, true).';'.$nl;
		} else {
			$res .= $ident.$this->visibility.' $'.$this->name.';'.$nl;
		}

		return $res;
	}

	/**
	 * @return string
	 */
	public function __toString() : string
	{
		return $this->toString();
	}


}