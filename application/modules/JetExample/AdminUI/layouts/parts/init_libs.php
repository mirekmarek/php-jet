<?php
use Jet\Mvc_Layout;

/**
 * @var Mvc_Layout $this
 */

$this->requireCssFile( 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' );
$this->requireCssFile( 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
$this->requireCssFile( '%JET_PUBLIC_URI%styles/admin_main.css' );
$this->requireCssFile( '%JET_PUBLIC_URI%styles/flags.css' );

$this->requireJavascriptFile( 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js' );
$this->requireJavascriptFile( 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js' );

$this->requireJavascriptFile( '%JET_PUBLIC_URI%scripts/admin_main.js' );