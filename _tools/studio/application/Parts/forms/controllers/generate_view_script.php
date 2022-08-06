<?php
namespace JetStudio;

$class = Forms::getCurrentClass();
if($class) {
	echo $class->generateViewScript();
}
Application::end();