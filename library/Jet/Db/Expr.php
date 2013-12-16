<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 */
namespace Jet;

class Db_Expr {
	
	/**
	 *
	 * @var string 
	 */
	protected $expression = "";

	/**
	 *
	 * @param string $expression 
	 */
	public function __construct( $expression ){
		$this->expression=$expression;
	}
	
	/**
	 *
	 * @return string 
	 */
	public function __toString(){
		return $this->expression;
	}
}