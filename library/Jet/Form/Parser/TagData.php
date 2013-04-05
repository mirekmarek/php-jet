<?php
/**
 *
 *
 *
 * Form handle exception
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Parser_TagData extends Object {
	/**
	 * @var string
	 */
	protected $tag = "";

	/**
	 * @var string
	 */
	protected $name = "";

	/**
	 * @var string
	 */
	protected $original_string = "";

	/**
	 * @var array
	 */
	protected $properties = array();

	/**
	 * @param array $regexp_match
	 */
	public function __construct( array $regexp_match ) {
		$this->original_string = $regexp_match[0];
		$tag = $regexp_match[1];

		if(!$tag) {
			$this->tag = "form";
		} else {
			$this->tag = substr($tag, 1);
		}

		$this->properties = array();
		$this->name = false;
		$_properties = substr(trim($regexp_match[2]), 0, -1);

		do {
			$_properties = str_replace( "  ", " ", $_properties );
		} while( strpos( "  ", $_properties ) !== false );

		$_properties = explode( '" ', $_properties );

		foreach( $_properties as $property ) {
			if( !$property || strpos($property, "=")===false ) {
				continue;
			}

			$property = explode("=", $property);

			$property_name = array_shift($property);
			$property_value = implode('=', $property);

			$property_name = strtolower($property_name);
			$property_value = str_replace("\"", "", $property_value);

			if($property_name=="name") {
				$this->name=$property_value;
			} else {
				$this->properties[$property_name] = $property_value;
			}
		}
	}

	/**
	 * @return string
	 */
	public function getTag() {
		return $this->tag;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getOriginalString() {
		return $this->original_string;
	}

	/**
	 * @return array
	 */
	public function getProperties() {
		return $this->properties;
	}

	/**
	 * @param string $property_name
	 * @param mixed $default_value (optional)
	 * @return mixed
	 */
	public function getProperty( $property_name, $default_value=null ) {
		$property_name = strtolower($property_name);

		if( !isset($this->properties[$property_name]) ) {
			return $default_value;
		}

		return $this->properties[$property_name];
	}

	/**
	 * @param $property_name
	 * @return bool
	 */
	public function getPropertyIsSet( $property_name ) {
		return !empty( $this->properties[$property_name] );
	}


	/**
	 * @param string $property_name
	 * @param string $value
	 */
	public function setProperty( $property_name, $value ) {
		$property_name = strtolower($property_name);

		$this->properties[$property_name] = (string)$value;
	}

	/**
	 * @param string $property_name
	 */
	public function unsetProperty( $property_name ) {
		$property_name = strtolower($property_name);

		if( isset($this->properties[$property_name]) ) {
			unset($this->properties[$property_name]);
		}
	}



}