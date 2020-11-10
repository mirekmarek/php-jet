<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Debug;

class ClassParser {

	/**
	 * @var string
	 */
	protected $script_data = '';

	/**
	 * @var ClassParser_Token[]
	 */
	public $tokens = [];

	/**
	 * @var int
	 */
	public $index = 0;

	/**
	 * @var ClassParser_Token
	 */
	public $_last_doc_comment_token;

	/**
	 * @var ClassParser_Token
	 */
	public $_abstract_class_token;

	/**
	 * @var ClassParser_Token
	 */
	public $start_token;

	/**
	 * @var ClassParser_Namespace
	 */
	public $namespace;

	/**
	 * @var ClassParser_UseClass[]
	 */
	public $use_classes = [];

	/**
	 * @var ClassParser_Class[]
	 */
	public $classes = [];

	/**
	 *
	 * @param string $script_data
	 */
	public function __construct( $script_data )
	{
		if($script_data) {
			$this->setScriptData( $script_data );
		}
	}

	/**
	 * @param string $script_data
	 */
	public function setScriptData( $script_data )
	{
		//debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		//echo 'setScriptData:'.PHP_EOL;
		//echo $script_data;
		//echo PHP_EOL.'------------------------------'.PHP_EOL.PHP_EOL;


		$this->script_data = $script_data;

		$this->tokens = [];
		$this->index = 0;
		$this->_last_doc_comment_token = null;
		$this->_abstract_class_token = null;
		$this->namespace = null;
		$this->use_classes = [];
		$this->classes = [];

		$this->parse();
	}

	/**
	 *
	 */
	public function parse()
	{
		$tokens = token_get_all( $this->script_data );

		$this->tokens = [];

		$last_doc_comment_token = null;


		foreach ($tokens as $token) {
			if (is_string($token)) {

				$id = $token;
				$text = $token;

			} else {
				list($id, $text) = $token;

			}

			$index = count($this->tokens);

			$token = new ClassParser_Token();

			$token->index = $index;
			$token->id    = $id;
			$token->text  = $text;


			$this->tokens[$index] = $token;

		}


		$this->_abstract_class_token = null;
		for( $this->index=0; $this->index<count($this->tokens); $this->index++ ) {
			$token = $this->tokens[$this->index];

			if(
				!$this->start_token &&
				$token->id==T_OPEN_TAG
			) {
				$this->start_token = $token;
			}

			if( $token->ignore(false) ) {
				continue;
			}

			switch( $token->id ) {

				case T_DOC_COMMENT:
					$this->_last_doc_comment_token = $token;

					if(
						!$this->namespace &&
						!$this->use_classes
					) {
						$this->start_token = $token;
					}

					break;
				case T_NAMESPACE:
					if($this->_abstract_class_token) {
						$this->parseError();
					}

					if(
						count($this->classes) ||
						count($this->use_classes)
					) {
						$this->parseError();
					}

					ClassParser_Namespace::parse( $this );
					$this->_last_doc_comment_token = null;
					$this->_abstract_class_token = null;
					break;

				case T_USE:
					if($this->_abstract_class_token) {
						$this->parseError();
					}

					ClassParser_UseClass::parse( $this );
					$this->_last_doc_comment_token = null;
					$this->_abstract_class_token = null;
					break;

				case T_ABSTRACT:
					if($this->_abstract_class_token) {
						$this->parseError();
					}

					$this->_abstract_class_token = $token;
					break;

				case T_CLASS:
					ClassParser_Class::parse( $this );
					break;

				default:
					//echo $token->debug_getInfo();
					break;

			}
		}

		//$this->debug_showTokens();
		//$this->debug_showResult();
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		$res = '';
		foreach( $this->tokens as $index=>$token ) {
			$res .= $token->text;
		}

		return $res;
	}

	/**
	 * @throws ClassParser_Exception
	 */
	public function parseError()
	{
		$exception = new ClassParser_Exception('Parse error');

		$code = '';
		for($i=0;$i<=$this->index; $i++) {
			$code .= $this->tokens[$i]->text;
		}

		$exception->setInvalidSourceCode( $code );


		throw $exception;
	}

	/**
	 * @param int|ClassParser_Token $from
	 * @param int|ClassParser_Token $to
	 *
	 * @return string
	 */
	public function getTokenText( $from, $to )
	{
		if($from instanceof ClassParser_Token) {
			$from = $from->index;
		}

		if($to instanceof ClassParser_Token) {
			$to = $to->index;
		}

		$res = '';
		for( $i=$from; $i<=$to; $i++ ) {
			$res .= $this->tokens[$i]->text;
		}

		return $res;
	}


	/**
	 * @param int|ClassParser_Token $from_index
	 * @param int|ClassParser_Token $to_index
	 */
	public function removeTokens( $from_index, $to_index )
	{
		if($from_index instanceof ClassParser_Token) {
			$from_index = $from_index->index;
		}

		if($to_index instanceof ClassParser_Token) {
			$to_index = $to_index->index;
		}

		$str = '';

		$i=0;
		foreach( $this->tokens as $token ) {
			if(
				$i<$from_index || $i>$to_index
			) {
				$str .= $token->text;
			}

			$i++;
		}

		$this->setScriptData( $str );
	}


	/**
	 * @param int|ClassParser_Token $from_index
	 * @param int|ClassParser_Token $to_index
	 * @param string $new_text
	 */
	public function replaceTokens( $from_index, $to_index, $new_text )
	{
		if($from_index instanceof ClassParser_Token) {
			$from_index = $from_index->index;
		}

		if($to_index instanceof ClassParser_Token) {
			$to_index = $to_index->index;
		}

		$str = '';

		$i=0;
		foreach( $this->tokens as $token ) {
			if(
				$i<$from_index || $i>$to_index
			) {
				$str .= $token->text;
			}

			if($i==$from_index) {
				$str .= $new_text;
			}

			$i++;
		}

		$this->setScriptData( $str );
	}



	/**
	 * @param ClassParser_Token $after_token
	 * @param $code
	 */
	public function insertAfter( ClassParser_Token $after_token, $code )
	{
		$str = '';

		foreach( $this->tokens as $token ) {
			if(!$token) {
				continue;
			}

			$str .= $token->text;
			if($token->index==$after_token->index) {
				$str .= $code;
			}

		}

		$this->setScriptData( $str );
	}


	/**
	 * @param ClassParser_Token $before_token
	 * @param $code
	 */
	public function insertBefore( ClassParser_Token $before_token, $code )
	{
		$str = '';

		foreach( $this->tokens as $token ) {
			if(!$token) {
				continue;
			}

			if($token->index==$before_token->index) {
				$str .= $code;
			}

			$str .= $token->text;
		}

		$this->setScriptData( $str );
	}

	/**
	 * @param string $code
	 */
	public function addUseClass( $code )
	{

		$after = $this->start_token;

		if($this->namespace) {
			$after = $this->namespace->end_token;
		}

		foreach( $this->use_classes as $use ) {
			if( $use->end_token->index>$after->index ) {
				$after = $use->end_token;
			}
		}

		$this->insertAfter( $after, $code );

	}

	/**
	 * @param string $class_name
	 * @return string
	 */
	public function getFullClassName( $class_name )
	{

		if(strpos($class_name, '\\')!==false) {
			return $class_name;
		}


		foreach( $this->use_classes as $use ) {
			if($use->as==$class_name) {
				return $use->class;
			}
		}

		return $this->namespace->namespace.'\\'.$class_name;
	}

	/**
	 * @param ClassCreator_Class $definition
	 */
	public function actualizeClass( ClassCreator_Class $definition )
	{

		//$c = 0;
		while( ($res=$this->_actualizeClass( $definition )) ) {
			/*
			var_dump($res);
			$c++;
			if($c>=1000) {
				break;
			}
			*/
		}
	}

	/**
	 * @param ClassCreator_Class $definition
	 *
	 * @return bool|string
	 */
	protected function _actualizeClass( ClassCreator_Class $definition )
	{
		if( !isset($this->classes[$definition->getName()]) ) {
			throw new \Exception('There is not class '.$definition->getName().' in the script');
		}


		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();

		$decision_maker = $definition->getActualizeDecisionMaker();

		$decide = function( $dm_name, array $dm_params ) use ($decision_maker) {
			if(
				!$decision_maker ||
				!$decision_maker->{$dm_name}
			) {
				return false;
			}

			/**
			 * @var callable $dm
			 */
			$dm = $decision_maker->{$dm_name};

			return call_user_func_array( $dm, $dm_params );
		};


		$class = $this->classes[$definition->getName()];


		foreach( $definition->getUse() as $use ) {
			$use_class = $use->getNamespace().'\\'.$use->getClass();

			foreach( $this->use_classes as $c_use_class ) {

				if($c_use_class->class==$use_class) {
					continue 2;
				}
			}

			$this->addUseClass( $nl.$use->toString() );

			return $use->toString().' added';

		}


		$class_annotation = trim($definition->generateClassAnnotation());

		if( $class->doc_comment ) {
			if(
				$class_annotation!=$class->doc_comment->text &&
				$decide('update_class_annotation', [$this, $class])
			) {
				$class->doc_comment->text = $class_annotation;

				return 'Class annotation updated';
			}
		} else {
			$this->insertBefore( $class->start_token, $definition->generateClassAnnotation() );

			return 'Class annotation added';
		}

		foreach( $definition->getConstants() as $constant ) {

			if(!isset($class->constants[$constant->getName()])) {
				$class->addConstant( $nl.$nl.$constant );

				return 'Constant '.$constant->getName().' added';
			} else {
				$constant_str = trim((string)$constant);
				$new_constant = $constant;
				$current_constant = $class->constants[$new_constant->getName()];

				if(
					$constant_str!=$current_constant->toString() &&
					$decide( 'update_constant', [$new_constant, $current_constant])
				) {
					$current_constant->replace( $constant_str );

					return 'Constant '.$constant->getName().' updated';
				}

			}
		}

		foreach( $definition->getProperties() as $property ) {

			if(!isset($class->properties[$property->getName()])) {
				$class->addProperty( $nl.$nl.$property );

				return 'Property '.$property->getName().' added';
			} else {
				$property_str = trim((string)$property);
				$new_property = $property;
				$current_property = $class->properties[$new_property->getName()];

				if(
					$property_str!=$current_property->toString() &&
					$decide( 'update_property', [$new_property, $current_property])
				) {
					$current_property->replace( $property_str );

					return 'Property '.$property->getName().' updated';
				}

			}
		}


		foreach( $definition->getMethods() as $method ) {


			if(!isset($class->methods[$method->getName()])) {
				$method_str = $method->toString( $ident, $nl );
				$class->addMethod( $nl.$nl.$method_str );

				return 'Method '.$method->getName().' added';
			} else {
				$method_str = trim($method->toString( $ident, $nl ));
				$new_method = $method;
				$current_method = $class->methods[$new_method->getName()];

				if(
					$method_str!=$current_method->toString() &&
					$decide( 'update_method', [$new_method, $current_method])
				) {
					$current_method->replace( $method_str );

					return 'Method '.$method->getName().' updated';
				}
			}
		}

		if($decision_maker) {

			if( $decision_maker->remove_constant ) {
				/**
				 * @var callable
				 */
				$dm = $decision_maker->remove_constant;

				foreach( $class->constants as $current_constant ) {
					if(
						!$definition->hasConstant($current_constant->name) &&
						$dm($current_constant)
					) {
						$current_constant->remove();

						return 'Constant '.$current_constant->name.' removed';
					}
				}
			}

			if( $decision_maker->remove_property ) {
				/**
				 * @var callable
				 */
				$dm = $decision_maker->remove_property;

				foreach( $class->properties as $current_property ) {
					if(
						!$definition->hasProperty($current_property->name) &&
						$dm($current_property)
					) {
						$current_property->remove();

						return 'Property '.$current_property->name.' removed';
					}
				}
			}

			if( $decision_maker->remove_method ) {
				/**
				 * @var callable
				 */
				$dm = $decision_maker->remove_method;

				foreach( $class->methods as $current_method ) {
					if(
						!$definition->hasMethod($current_method->name) &&
						$dm($current_method)
					) {
						$current_method->remove();

						return 'Method '.$current_method->name.' removed';
					}
				}
			}
		}


		return false;
	}



	/**
	 *
	 */
	public function debug_showTokens()
	{
		foreach( $this->tokens as $index=>$token ) {
			echo $token->debug_getInfo();
		}
	}

	/**
	 *
	 */
	public function debug_showResult()
	{
		if($this->namespace) {
			$this->namespace->debug_showResult();
			echo '=========================================================='.SysConf_Jet::EOL().SysConf_Jet::EOL();
		}

		foreach( $this->use_classes as $use_class ) {
			$use_class->debug_showResult();
			echo '=========================================================='.SysConf_Jet::EOL().SysConf_Jet::EOL();
		}

		foreach( $this->classes as $class ) {
			$class->debug_showResult();
			echo '=========================================================='.SysConf_Jet::EOL().SysConf_Jet::EOL();
		}

	}


}
