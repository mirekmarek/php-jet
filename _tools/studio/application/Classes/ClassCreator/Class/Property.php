<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\Data_Array;

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
	 * @var string
	 */
	protected string $doc_block_type = '';

	/**
	 * @var string
	 */
	protected string $declared_type = '';
	
	/**
	 * @var string
	 */
	protected string $hooks = '';

	/**
	 * @var mixed
	 */
	protected mixed $default_value = null;
	
	/**
	 * @var bool
	 */
	protected bool $doc_block_is_needed = false;


	/**
	 * @var ClassCreator_Attribute[]
	 */
	protected array $attributes = [];


	/**
	 * @param string $name
	 * @param string $type
	 * @param string $declared_type
	 */
	public function __construct( string $name, string $type, string $declared_type )
	{
		$this->name = $name;
		$this->type = $type;
		$this->declared_type = $declared_type;
	}

	/**
	 * @return string
	 */
	public function getVisibility(): string
	{
		return $this->visibility;
	}

	/**
	 * @param string $visibility
	 */
	public function setVisibility( string $visibility ): void
	{
		$this->visibility = $visibility;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
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
	 */
	public function setType( string $type ): void
	{
		$this->type = $type;
	}
	
	public function getDocBlockType(): string
	{
		return $this->doc_block_type;
	}
	
	public function setDocBlockType( string $doc_block_type ): void
	{
		$this->doc_block_type = $doc_block_type;
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
	 */
	public function setDefaultValue( mixed $default_value ): void
	{
		$this->default_value = $default_value;
	}
	
	public function getHooks(): string
	{
		return $this->hooks;
	}
	
	public function setHooks( string $hooks ): void
	{
		$this->hooks = $hooks;
	}
	
	/** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
	public function createStdHook(
		string  $set_type,
		?string $set_code=null
	) : void
	{
		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();
		
		$this->setVisibility( ClassCreator_Class::VISIBILITY_PUBLIC );
		
		if(!$set_code) {
			$set_code = "\$this->{$this->getName()} = \$value;";
		}
		
		$hooks = " {{$nl}";
		$hooks .= "{$ident}{$ident}get{{$nl}";
		$hooks .= "{$ident}{$ident}{$ident}return \$this->{$this->getName()};{$nl}";
		$hooks .= "{$ident}{$ident}}{$nl}";
		$hooks .= "{$ident}{$ident}set( $set_type \$value ){{$nl}";
		$hooks .= "{$ident}{$ident}{$ident}$set_code{$nl}";
		$hooks .= "{$ident}{$ident}}{$nl}";
		$hooks .= "{$ident}}{$nl}";
		
		$this->setHooks(
			$hooks
		);
		
	}
	
	
	
	public function getDocBlockIsNeeded(): bool
	{
		return $this->doc_block_is_needed;
	}
	
	public function setDocBlockIsNeeded( bool $doc_block_is_needed ): void
	{
		$this->doc_block_is_needed = $doc_block_is_needed;
	}
	
	/**
	 * @return ClassCreator_Attribute[]
	 */
	public function getAttributes(): array
	{
		return $this->attributes;
	}
	
	


	/**
	 * @param string $name
	 * @param string $argument
	 * @param mixed $argument_value
	 */
	public function setAttribute( string $name, string $argument, mixed $argument_value ): void
	{
		if( !isset( $this->attributes[$name] ) ) {
			$this->attributes[$name] = new ClassCreator_Attribute( $name );
		}

		$this->attributes[$name]->setArgument( $argument, $argument_value );
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		$res = '';

		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();

		$type = $this->type;
		$declared_type = $this->declared_type;

		if(
			$this->default_value === null &&
			$this->type != 'mixed'
		) {
			if( str_contains( $type, '|' ) ) {
				$type .= '|null';

			} else {
				$type = '?' . $type;
			}
			if( $declared_type ) {
				if( str_contains( $declared_type, '|' ) ) {
					$declared_type .= '|null';
				} else {
					$declared_type = '?' . $declared_type;
				}
			}
		}

		if( $declared_type ) {
			$declared_type = ' ' . $declared_type;
		}

		if(
			$this->doc_block_is_needed ||
			ClassCreator_Config::getAddDocBlocksAlways()
		) {
			$res .= $ident . '/**' . $nl;
			$res .= $ident . ' * @var ' . ($this->doc_block_type?:$type) . $nl;
			$res .= $ident . ' */ ' . $nl;
		}

		foreach( $this->attributes as $attribute ) {
			$res .= $attribute->toString( 1 );
		}

		if( $this->default_value !== null ) {
			if( is_array( $this->default_value ) ) {
				if( count( $this->default_value ) > 0 ) {
					$res .= $ident . $this->visibility . $declared_type . ' $' . $this->name . ' = ' . Data_Array::_export( $this->default_value, 1 );
				} else {
					$res .= $ident . $this->visibility . $declared_type . ' $' . $this->name . ' = []';
				}
			} else {
				$res .= $ident . $this->visibility . $declared_type . ' $' . $this->name . ' = ' . var_export( $this->default_value, true );
			}

		} else {
			$res .= $ident . $this->visibility . $declared_type . ' $' . $this->name . ' = null';
		}
		
		if($this->hooks) {
			$res .= $this->hooks;
		} else {
			$res .= ';';
		}

		return $res;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}


}