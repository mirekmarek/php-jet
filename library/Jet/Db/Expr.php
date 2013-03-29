<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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