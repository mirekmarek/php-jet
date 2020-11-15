<?php
use Jet\Mvc_Layout;
use Jet\SysConf_URI;

/**
 * @var Mvc_Layout $this
 */


$this->requireMainCssFile( 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' );
$this->requireMainCssFile( 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css' );
$this->requireMainCssFile( SysConf_URI::PUBLIC().'css/flags.css' );
$this->requireMainCssFile( SysConf_URI::PUBLIC().'css/admin_main.css?v=1' );

$this->requireMainJavascriptFile( 'https://code.jquery.com/jquery-3.5.1.js' );
$this->requireMainJavascriptFile( 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js' );
$this->requireMainJavascriptFile( SysConf_URI::PUBLIC().'js/JetAjaxForm.js?v=1' );