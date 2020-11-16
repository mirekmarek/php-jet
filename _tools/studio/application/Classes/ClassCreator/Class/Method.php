<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;
use Jet\BaseObject_Exception;

class ClassCreator_Class_Method extends BaseObject
{

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var bool
	 */
	protected $is_abstract = false;

	/**
	 * @var bool
	 */
	protected $is_static = false;

	/**
	 * @var string
	 */
	protected $access = 'public';

	/**
	 * @var ClassCreator_Class_Method_Parameter[]
	 */
	protected $parameters = [];

	/**
	 * @var string
	 */
	protected $return_type = '';

	/**
	 * @var array
	 */
	protected $body = [];


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
	 * @return bool
	 */
	public function isStatic()
	{
		return $this->is_static;
	}

	/**
	 * @param bool $is_static
	 */
	public function setIsStatic( $is_static )
	{
		$this->is_static = $is_static;
	}



	/**
	 * @return string
	 */
	public function getAccess()
	{
		return $this->access;
	}

	/**
	 * @param string $access
	 *
	 * @return $this
	 */
	public function setAccess( $access )
	{
		$this->access = $access;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getReturnType()
	{
		return $this->return_type;
	}

	/**
	 * @param string $return_type
	 *
	 * @return $this
	 */
	public function setReturnType( $return_type )
	{
		$this->return_type = $return_type;

		return $this;
	}



	/**
	 * @param string $name
	 *
	 * @return ClassCreator_Class_Method_Parameter
	 */
	public function addParameter( $name )
	{
		if( isset($this->parameters[$name]) ) {
			throw new BaseObject_Exception('Parameter '.$name.' already defined');
		}

		$parameter = new ClassCreator_Class_Method_Parameter( $name );

		$this->parameters[$name] = $parameter;

		return $parameter;
	}

	/**
	 *
	 */
	public function clearBody()
	{
		$this->body = [];
	}

	/**
	 * @param int $padding_left
	 * @param string $line
	 */
	public function line( $padding_left, $line )
	{
		$this->body[] = [
			'padding_left' => $padding_left,
			'line' => $line
		];
	}

	/**
	 * @param string $ident
	 * @param string $nl
	 *
	 * @return string
	 */
	public function toString( $ident, $nl )
	{
		$res = '';

		$res .= $ident.'/**'.$nl;
		$res .= $ident.' *'.$nl;
		foreach( $this->parameters as $param ) {
			$res .= $ident.' * '.$param->createClass_getAsAnnotation().$nl;
		}
		if( $this->getReturnType() ) {
			$res .= $ident.' *'.$nl;
			$res .= $ident.' * @return '.$this->getReturnType().$nl;
		}

		$res .= $ident.' *'.$nl;
		$res .= $ident.' */'.$nl;

		$res .= $ident;

		if($this->isAbstract()) {
			$res .= 'abstract ';
		}
		$res .= $this->getAccess().' ';


		if($this->isStatic()) {
			$res .= 'static ';
		}
		$res .= 'function '.$this->getName().'(';

		$parameters = [];
		foreach( $this->parameters as $param ) {
			$parameters[] = $param->getAsMethodParam();
		}
		if($parameters) {
			$res .= ' '.implode(', ', $parameters).' ';
		}

		$res .= ')'.$nl;
		$res .= $ident.'{'.$nl;
		foreach( $this->body as $l ) {
			$padding_left = $l['padding_left'];
			$line = $l['line'];

			$res .= str_repeat( $ident, $padding_left+1 );
			$res .= $line;
			$res .= $nl;
		}
		$res .= $ident.'}'.$nl;

		return $res;
	}

}