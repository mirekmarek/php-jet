<?php
namespace JetStudio;

require 'application/init.php';

Application::setCurrentPart( 'forms' );
Application::handleAction();
Application::output( Application::getView()->render('main') );
Application::renderLayout();
