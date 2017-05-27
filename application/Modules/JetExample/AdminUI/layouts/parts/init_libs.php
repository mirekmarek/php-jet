<?php
use Jet\Mvc_Layout;

/**
 * @var Mvc_Layout $this
 */


$this->requireCssFile( BOOTSTRAP_CSS_URL );
$this->requireCssFile( FONT_AWESOME_CSS_URL );
$this->requireCssFile( FLAGS_CSS_URL );
$this->requireCssFile( 'styles/admin_main.css' );

$this->requireJavascriptFile( JQUERY_JS_URL );
$this->requireJavascriptFile( BOOTSTRAP_JS_URL );
$this->requireJavascriptFile( JET_AJAX_FORM_JS_URL );