<?php
use Jet\Mvc_Layout;
use Jet\Application_Modules;

/**
 * @var Mvc_Layout $this
 */

$this->requireCssFile('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css');
$this->requireCssFile('https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
$this->requireCssFile( '%JET_PUBLIC_URI%styles/admin_main.css' );
//$this->requireCssFile( '%JET_PUBLIC_URI%styles/admin_flags.css' );

$this->requireJavascriptFile('https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js');
$this->requireJavascriptFile('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js');
$this->requireJavascriptFile( '%JET_PUBLIC_URI%scripts/jquery-ui.min.js' );
$this->requireJavascriptFile( '%JET_PUBLIC_URI%scripts/jquery.form.js');

$this->requireJavascriptFile( '%JET_PUBLIC_URI%scripts/admin_main.js' );