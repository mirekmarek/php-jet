<?php
use Jet\Mvc_Layout;
use Jet\SysConf_URI;

/**
 * @var Mvc_Layout $this
 */


$this->requireMainCssFile( 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' );
$this->requireMainCssFile( 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css' );
$this->requireMainCssFile( 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
$this->requireMainCssFile( SysConf_URI::getPublic().'css/flags.css' );
$this->requireMainCssFile( SysConf_URI::getPublic().'css/main.css?v=8' );

$this->requireMainJavascriptFile( 'https://code.jquery.com/jquery-3.5.1.js' );
$this->requireMainJavascriptFile( 'https://code.jquery.com/ui/1.11.4/jquery-ui.js' );
$this->requireMainJavascriptFile( 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js' );
$this->requireMainJavascriptFile( 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js' );
$this->requireMainJavascriptFile( SysConf_URI::getPublic().'js/JetAjaxForm.js?v=1' );



