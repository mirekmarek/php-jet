<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

/**
 *
 */
class ClassParser
{
	const VISIBILITY_PUBLIC = 'public';
	const VISIBILITY_PROTECTED = 'protected';
	const VISIBILITY_PRIVATE = 'private';


	/**
	 * @var string
	 */
	public string $script_data = '';

	/**
	 * @var ClassParser_Token[]
	 */
	public array $tokens = [];

	/**
	 * @var int
	 */
	public int $index = 0;

	/**
	 * @var ?ClassParser_Token
	 */
	public ?ClassParser_Token $_last_doc_comment_token = null;

	/**
	 * @var ?ClassParser_Token
	 */
	public ?ClassParser_Token $_abstract_class_token = null;

	/**
	 * @var ?ClassParser_Token
	 */
	public ?ClassParser_Token $start_token = null;

	/**
	 * @var ?ClassParser_Namespace
	 */
	public ?ClassParser_Namespace $namespace = null;

	/**
	 * @var ClassParser_UseClass[]
	 */
	public array $use_classes = [];

	/**
	 * @var ClassParser_Class[]
	 */
	public array $classes = [];

	/**
	 * @var ClassParser_Attribute[]
	 */
	public array $__attributes = [];

	/**
	 *
	 * @param string $script_data
	 */
	public function __construct( string $script_data )
	{
		if( $script_data ) {
			$this->setScriptData( $script_data );
		}
	}

	/**
	 * @param string $script_data
	 */
	public function setScriptData( string $script_data ) : void
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
	public function parse(): void
	{
		$tokens = token_get_all( $this->script_data );

		$this->tokens = [];

		$last_doc_comment_token = null;


		foreach( $tokens as $token ) {
			if( is_string( $token ) ) {

				$id = $token;
				$text = $token;

			} else {
				[
					$id,
					$text
				] = $token;

			}

			$index = count( $this->tokens );

			$token = new ClassParser_Token();

			$token->index = $index;
			$token->id = $id;
			$token->text = $text;


			$this->tokens[$index] = $token;

		}


		$this->_abstract_class_token = null;
		for( $this->index = 0; $this->index < count( $this->tokens ); $this->index++ ) {
			$token = $this->tokens[$this->index];

			if(
				!$this->start_token &&
				$token->id == T_OPEN_TAG
			) {
				$this->start_token = $token;
			}

			if( $token->ignore( false ) ) {
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
					if( $this->_abstract_class_token ) {
						$this->parseError();
					}

					if(
						count( $this->classes ) ||
						count( $this->use_classes )
					) {
						$this->parseError();
					}

					ClassParser_Namespace::parse( $this );
					$this->_last_doc_comment_token = null;
					$this->_abstract_class_token = null;
					break;

				case T_USE:
					if( $this->_abstract_class_token ) {
						$this->parseError();
					}

					ClassParser_UseClass::parse( $this );
					$this->_last_doc_comment_token = null;
					$this->_abstract_class_token = null;
					break;

				case T_ABSTRACT:
					if( $this->_abstract_class_token ) {
						$this->parseError();
					}

					$this->_abstract_class_token = $token;
					break;

				case T_CLASS:

					if( $this->tokens[$this->index - 1]->text != '::' ) {
						ClassParser_Class::parse( $this );
					}

					break;
				case T_ATTRIBUTE:
					ClassParser_Attribute::parse( $this );
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
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		$res = '';
		foreach( $this->tokens as $index => $token ) {
			$res .= $token->text;
		}

		return $res;
	}

	/**
	 * @throws ClassParser_Exception
	 */
	public function parseError(): void
	{
		$exception = new ClassParser_Exception( 'Parse error' );

		$code = '';
		for( $i = 0; $i <= $this->index; $i++ ) {
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
	public function getTokenText( int|ClassParser_Token $from, int|ClassParser_Token $to ): string
	{
		if( $from instanceof ClassParser_Token ) {
			$from = $from->index;
		}

		if( $to instanceof ClassParser_Token ) {
			$to = $to->index;
		}

		$res = '';
		for( $i = $from; $i <= $to; $i++ ) {
			$res .= $this->tokens[$i]->text;
		}

		return $res;
	}


	/**
	 * @param int|ClassParser_Token $from_index
	 * @param int|ClassParser_Token $to_index
	 */
	public function removeTokens( int|ClassParser_Token $from_index, int|ClassParser_Token $to_index ): void
	{
		if( $from_index instanceof ClassParser_Token ) {
			$from_index = $from_index->index;
		}

		if( $to_index instanceof ClassParser_Token ) {
			$to_index = $to_index->index;
		}

		$index_after = $to_index + 1;

		if( isset( $this->tokens[$index_after] ) ) {
			$this->tokens[$index_after]->text = ltrim( $this->tokens[$index_after]->text );
		}

		$i = 0;
		foreach( $this->tokens as $token ) {
			if(
				$i >= $from_index && $i <= $to_index
			) {
				$token->id = 'DELETED';
				$token->text = '';
			}

			$i++;
		}
	}


	/**
	 * @param int|ClassParser_Token $from_index
	 * @param int|ClassParser_Token $to_index
	 * @param string $new_text
	 */
	public function replaceTokens( int|ClassParser_Token $from_index, int|ClassParser_Token $to_index, string $new_text ): void
	{
		if( $from_index instanceof ClassParser_Token ) {
			$from_index = $from_index->index;
		}

		if( $to_index instanceof ClassParser_Token ) {
			$to_index = $to_index->index;
		}

		$str = '';

		$i = 0;
		foreach( $this->tokens as $token ) {
			if(
				$i < $from_index || $i > $to_index
			) {
				$str .= $token->text;
			}

			if( $i == $from_index ) {
				$str .= $new_text;
			}

			$i++;
		}

		$this->setScriptData( $str );
	}


	/**
	 * @param ClassParser_Token $after_token
	 * @param string $code
	 */
	public function insertAfter( ClassParser_Token $after_token, string $code ): void
	{
		$str = '';

		foreach( $this->tokens as $token ) {
			if( !$token ) {
				continue;
			}

			$str .= $token->text;
			if( $token->index == $after_token->index ) {
				$str .= $code;
			}

		}

		$this->setScriptData( $str );
	}


	/**
	 * @param ClassParser_Token $before_token
	 * @param string $code
	 */
	public function insertBefore( ClassParser_Token $before_token, string $code ): void
	{
		$str = '';

		foreach( $this->tokens as $token ) {
			if( !$token ) {
				continue;
			}

			if( $token->index == $before_token->index ) {
				$str .= $code;
			}

			$str .= $token->text;
		}

		$this->setScriptData( $str );
	}

	/**
	 * @param string $code
	 */
	public function addUseClass( string $code ): void
	{

		$after = $this->start_token;

		if( $this->namespace ) {
			$after = $this->namespace->end_token;
		}

		foreach( $this->use_classes as $use ) {
			if( $use->end_token->index > $after->index ) {
				$after = $use->end_token;
			}
		}

		$this->insertAfter( $after, $code );

	}

	/**
	 * @param string $class_name
	 * @return string
	 */
	public function getFullClassName( string $class_name ): string
	{

		if( str_contains( $class_name, '\\' ) ) {
			return $class_name;
		}


		foreach( $this->use_classes as $use ) {
			if( $use->as == $class_name ) {
				return $use->class;
			}
		}

		return $this->namespace->namespace . '\\' . $class_name;
	}

	/**
	 * @param string $class_name
	 * @param ClassCreator_Attribute $attribute
	 */
	public function actualize_setAttribute( string $class_name, ClassCreator_Attribute $attribute ): void
	{
		$class = $this->classes[$class_name];


		$name = $attribute->getName();

		foreach( $class->attributes as $c_a ) {
			if( $c_a->name == $attribute->getName() ) {
				$this->removeTokens( $c_a->start_token, $c_a->end_token );
			}
		}


		$this->insertBefore( $class->declaration_start, ltrim( $attribute->toString() ) );
	}

	/**
	 * @param string $class_name
	 * @param string $class_annotation
	 *
	 * @return string
	 */
	public function actualize_setClassAnnotation( string $class_name, string $class_annotation ): string
	{
		$class = $this->classes[$class_name];

		$_class_annotation = trim( $class_annotation );

		if( $class->doc_comment ) {
			if( $_class_annotation != $class->doc_comment->text ) {
				$class->doc_comment->text = $_class_annotation;

				return 'Class annotation updated';
			}
		} else {
			$this->insertBefore( $class->start_token, $class_annotation );

			return 'Class annotation added';
		}

		return '';
	}

	/**
	 * @param ClassCreator_UseClass[] $uses
	 */
	public function actualize_setUse( array $uses ): void
	{
		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();

		foreach( $uses as $use ) {
			$use_class = $use->getNamespace() . '\\' . $use->getClass();

			foreach( $this->use_classes as $c_use_class ) {
				if( $c_use_class->class == $use_class ) {
					continue 2;
				}
			}

			$this->addUseClass( $nl . $use->toString() );
		}
	}

	/**
	 * @param string $class_name
	 * @param ClassCreator_Class_Property $property
	 *
	 * @return string
	 */
	public function actualize_addProperty( string $class_name, ClassCreator_Class_Property $property ): string
	{
		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();
		$class = $this->classes[$class_name];

		if( isset( $class->properties[$property->getName()] ) ) {
			return '';
		}

		$class->addProperty( $nl . $nl . $property );

		return 'Property ' . $property->getName() . ' added';
	}

	/**
	 * @param string $class_name
	 * @param ClassCreator_Class_Property $property
	 *
	 * @return string
	 */
	public function actualize_updateProperty( string $class_name, ClassCreator_Class_Property $property ): string
	{
		$class = $this->classes[$class_name];

		if( !isset( $class->properties[$property->getName()] ) ) {
			return '';
		}

		$property_str = trim( (string)$property );
		$new_property = $property;
		$current_property = $class->properties[$new_property->getName()];

		if( $property_str != $current_property->toString() ) {
			$current_property->replace( $property_str );

			return 'Property ' . $property->getName() . ' updated';
		}

		return '';
	}


	/**
	 * @param string $class_name
	 * @param ClassCreator_Class_Method $method
	 *
	 * @return string
	 */
	public function actualize_addMethod( string $class_name, ClassCreator_Class_Method $method ): string
	{
		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();
		$class = $this->classes[$class_name];

		if( isset( $class->methods[$method->getName()] ) ) {
			return '';
		}

		$method_str = $method->toString( $ident, $nl );
		$class->addMethod( $nl . $nl . $method_str );

		return 'Method ' . $method->getName() . ' added';
	}


	/**
	 *
	 */
	public function debug_showTokens(): void
	{
		foreach( $this->tokens as $index => $token ) {
			echo $token->debug_getInfo();
		}
	}

	/**
	 *
	 */
	public function debug_showResult(): void
	{
		if( $this->namespace ) {
			$this->namespace->debug_showResult();
			echo '==========================================================' . PHP_EOL . PHP_EOL;
		}

		foreach( $this->use_classes as $use_class ) {
			$use_class->debug_showResult();
			echo '==========================================================' . PHP_EOL . PHP_EOL;
		}

		foreach( $this->classes as $class ) {
			$class->debug_showResult();
			echo '==========================================================' . PHP_EOL . PHP_EOL;
		}

	}


}
