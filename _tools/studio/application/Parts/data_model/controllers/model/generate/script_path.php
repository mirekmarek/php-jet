<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;

$GET = Http_Request::GET();

$namespace = $GET->getString( 'namespace' );
$class_name = $GET->getString( 'class_name' );

$path = DataModels::generateScriptPath( $namespace, $class_name );

AJAX::commonResponse( ['path' => $path] );
