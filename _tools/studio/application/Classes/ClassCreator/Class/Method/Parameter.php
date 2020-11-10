<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;

class ClassCreator_Class_Method_Parameter extends BaseObject
{
	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $type = '';

	/**
	 * @var bool
	 */
	protected $type_hinting = false;

	/**
	 * @var bool
	 */
	protected $optional = false;

	/**
	 * @var mixed
	 */
	protected $default_value;

	/**
	 * @var string
	 */
	protected $comment = '';

	/**
	 * @param string $name
	 */
	public function __construct( $name )
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function setType( $type )
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getTypeHinting()
	{
		return $this->type_hinting;
	}

	/**
	 * @param bool $type_hinting
	 *
	 * @return $this
	 */
	public function setTypeHinting( $type_hinting )
	{
		$this->type_hinting = (bool)$type_hinting;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getIsOptional()
	{
		return $this->optional;
	}

	/**
	 * @param bool $optional
	 *
	 * @return $this
	 */
	public function setIsOptional( $optional )
	{
		$this->optional = $optional;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		return $this->default_value;
	}

	/**
	 * @param mixed $default_value
	 *
	 * @return $this
	 */
	public function setDefaultValue( $default_value )
	{
		$this->default_value = $default_value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getComment()
	{
		return $this->comment;
	}

	/**
	 *
	 * @param string $comment
	 *
	 * @return $this
	 */
	public function setComment( $comment )
	{
		$this->comment = $comment;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAsMethodParam()
	{
		$res = '';
		if( $this->getTypeHinting() ) {
			$res .= $this->getType().' ';
		}

		$res .= '$'.$this->getName();

		if($this->getIsOptional()) {
			$res .='='.var_export( $this->getDefaultValue(), true );
		}

		return $res;
	}

	/**
	 * @return string
	 */
	public function getAsAnnotation()
	{
		$res = '@param '.$this->getType().' $'.$this->getName();

		if($this->getComment()) {
			$res .= ' '.$this->getComment();
		}

		return $res;
	}


}