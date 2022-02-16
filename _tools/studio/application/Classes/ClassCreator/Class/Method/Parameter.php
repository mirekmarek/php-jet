<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;

/**
 *
 */
class ClassCreator_Class_Method_Parameter extends BaseObject
{
	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var string
	 */
	protected string $type = '';


	/**
	 * @var bool
	 */
	protected bool $optional = false;

	/**
	 * @var mixed
	 */
	protected mixed $default_value = null;

	/**
	 * @var string
	 */
	protected string $comment = '';

	/**
	 * @param string $name
	 */
	public function __construct( string $name )
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 *
	 * @return static
	 */
	public function setType( string $type ): static
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getIsOptional(): bool
	{
		return $this->optional;
	}

	/**
	 * @param bool $optional
	 *
	 * @return static
	 */
	public function setIsOptional( bool $optional ): static
	{
		$this->optional = $optional;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue(): mixed
	{
		return $this->default_value;
	}

	/**
	 * @param mixed $default_value
	 *
	 * @return static
	 */
	public function setDefaultValue( mixed $default_value ): static
	{
		$this->default_value = $default_value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getComment(): string
	{
		return $this->comment;
	}

	/**
	 *
	 * @param string $comment
	 *
	 * @return static
	 */
	public function setComment( string $comment ): static
	{
		$this->comment = $comment;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAsMethodParam(): string
	{
		$res = '';
		$res .= $this->getType() . ' ';


		$res .= '$' . $this->getName();

		if( $this->getIsOptional() ) {
			$res .= '=' . var_export( $this->getDefaultValue(), true );
		}

		return $res;
	}

	/**
	 * @return string
	 */
	public function createClass_getAsAnnotation(): string
	{
		$res = '@param ' . $this->getType() . ' $' . $this->getName();

		if( $this->getComment() ) {
			$res .= ' ' . $this->getComment();
		}

		return $res;
	}


}