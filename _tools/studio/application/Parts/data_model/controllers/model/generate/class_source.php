<?php

namespace JetStudio;

$current = DataModels::getCurrentModel();
if( !$current ) {
	die();
}

header( 'Content-Type: text/plain' );

$class = $current->createClass();

if( !$class ) {
	die();
}

echo $class;

die();