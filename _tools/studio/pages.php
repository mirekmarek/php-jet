<?php
namespace JetStudio;

require 'application/init.php';

Application::setCurrentPart( 'pages' );
Application::handleAction();
Application::output( Application::getView()->render('main') );
Application::renderLayout();
