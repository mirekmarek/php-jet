<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\BaseObject_Exception;

/**
 *
 */
class ClassCreator_Class_Method extends BaseObject
{

	/**
	 * @var string
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var bool
	 */
	protected bool $is_abstract = false;

	/**
	 * @var bool
	 */
	protected bool $is_static = false;

	/**
	 * @var string
	 */
	protected string $access = 'public';

	/**
	 * @var ClassCreator_Class_Method_Parameter[]
	 */
	protected array $parameters = [];

	/**
	 * @var string
	 */
	protected string $return_type = '';
	
	/**
	 * @var string
	 */
	protected string $return_type_for_doc = '';
	
	/**
	 * @var bool
	 */
	protected bool $return_type_no_inspection = false;

	/**
	 * @var array
	 */
	protected array $body = [];


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
	 * @return bool
	 */
	public function isAbstract(): bool
	{
		return $this->is_abstract;
	}

	/**
	 * @param bool $is_abstract
	 */
	public function setIsAbstract( bool $is_abstract ): void
	{
		$this->is_abstract = $is_abstract;
	}

	/**
	 * @return bool
	 */
	public function isStatic(): bool
	{
		return $this->is_static;
	}

	/**
	 * @param bool $is_static
	 */
	public function setIsStatic( bool $is_static ): void
	{
		$this->is_static = $is_static;
	}


	/**
	 * @return string
	 */
	public function getAccess(): string
	{
		return $this->access;
	}

	/**
	 * @param string $access
	 *
	 * @return static
	 */
	public function setAccess( string $access ): static
	{
		$this->access = $access;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getReturnType(): string
	{
		return $this->return_type;
	}

	/**
	 * @param string $return_type
	 *
	 * @return static
	 */
	public function setReturnType( string $return_type ): static
	{
		$this->return_type = $return_type;

		return $this;
	}
	
	
	/**
	 * @param string $return_type_for_doc
	 */
	public function setReturnTypeForDoc( string $return_type_for_doc ): void
	{
		$this->return_type_for_doc = $return_type_for_doc;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getReturnTypeForDoc() : string
	{
		
		if($this->return_type_for_doc) {
			return $this->return_type_for_doc;
		}
		
		return $this->return_type;
	}
	
	/**
	 * @return bool
	 */
	public function getReturnTypeNoInspection(): bool
	{
		return $this->return_type_no_inspection;
	}
	
	/**
	 * @param bool $return_type_no_inspection
	 */
	public function setReturnTypeNoInspection( bool $return_type_no_inspection ): void
	{
		$this->return_type_no_inspection = $return_type_no_inspection;
	}
	
	

	/**
	 * @param string $name
	 *
	 * @return ClassCreator_Class_Method_Parameter
	 */
	public function addParameter( string $name ): ClassCreator_Class_Method_Parameter
	{
		if( isset( $this->parameters[$name] ) ) {
			throw new BaseObject_Exception( 'Parameter ' . $name . ' already defined' );
		}

		$parameter = new ClassCreator_Class_Method_Parameter( $name );

		$this->parameters[$name] = $parameter;

		return $parameter;
	}

	/**
	 *
	 */
	public function clearBody(): void
	{
		$this->body = [];
	}

	/**
	 * @param int $padding_left
	 * @param string $line
	 */
	public function line( int $padding_left, string $line ): void
	{
		$this->body[] = [
			'padding_left' => $padding_left,
			'line'         => $line
		];
	}

	/**
	 * @param string $ident
	 * @param string $nl
	 *
	 * @return string
	 */
	public function toString( string $ident, string $nl ): string
	{
		$res = '';

		$res .= $ident . '/**' . $nl;
		foreach( $this->parameters as $param ) {
			$res .= $ident . ' * ' . $param->createClass_getAsAnnotation() . $nl;
		}
		
		if( $this->getReturnTypeForDoc() ) {
			if($this->getReturnTypeNoInspection()) {
				$res .= $ident . ' * @noinspection PhpDocSignatureInspection' . $nl;
			}
			$res .= $ident . ' * @return ' . $this->getReturnTypeForDoc() . $nl;
			
		}
		$res .= $ident . ' */' . $nl;

		$res .= $ident;

		if( $this->isAbstract() ) {
			$res .= 'abstract ';
		}
		$res .= $this->getAccess() . ' ';


		if( $this->isStatic() ) {
			$res .= 'static ';
		}
		$res .= 'function ' . $this->getName() . '(';

		$parameters = [];
		foreach( $this->parameters as $param ) {
			$parameters[] = $param->getAsMethodParam();
		}
		if( $parameters ) {
			$res .= ' ' . implode( ', ', $parameters ) . ' ';
		}

		$res .= ')';
		if( $this->getReturnType() ) {
			$res .= ' : ' . $this->getReturnType();
		} else {
			$res .= ' : void';
		}
		$res .= $nl;

		$res .= $ident . '{' . $nl;
		foreach( $this->body as $l ) {
			$padding_left = $l['padding_left'];
			$line = $l['line'];

			$res .= str_repeat( $ident, $padding_left + 1 );
			$res .= $line;
			$res .= $nl;
		}
		$res .= $ident . '}';

		return $res;
	}

}